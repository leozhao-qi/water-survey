<?php

namespace App\Http\Controllers\Packages\Api;

use App\User;
use App\Lesson;
use App\Package;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Rules\IsValidCompletion;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\Packages\PackageResource;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['role:administrator'])->only(['store', 'add']);
        $this->middleware(['profile'])->only(['show', 'update']);
    }

    public function show(User $user, Package $package)
    {
        return new PackageResource($package);
    }

    public function store(User $user)
    {
        request()->validate(['version' => 'required|exists:lesson_versions,id']);

        $lessons = Lesson::whereLessonVersionId(request('version'))->get();

        foreach ($lessons as $lesson) {
            Package::create([
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'theory_status' => $lesson->objectives->where('type', 'theory')->count() ? 'incomplete' : null,
                'practical_status' => $lesson->objectives->where('type', 'practical_application')->count() ? 'incomplete' : null
            ]);
        }

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Packages successfully added.',
                'user' => new UserResource(
                    User::find($user->id)
                )
            ]
        ], 200);
    }

    public function update(User $user, Package $package) {
        $status_rule = Rule::in(['incomplete', 'complete_eg3', 'complete_eg4', 'deferred', 'exempt']);

        request()->validate([
            'complete' => ['sometimes', 'nullable', 'max:1', new IsValidCompletion($user, $package)],
            'theory_status' => ['sometimes', $status_rule],
            'practical_status' => ['sometimes', $status_rule],
            'objectives.*' => ['sometimes', Rule::in($package->lesson->objectives->pluck('id')->toArray())],
            'recommendation_id' => 'sometimes|nullable|exists:recommendations,id',
            'evaluation_details' => 'sometimes|nullable|min:8'
        ]);

        if (!auth()->user()->can('update', $package)) {
            // Not authorized to do this
            return response()->json([
                'data' => [
                    'message' => 'You are not authorized to do that.'
                ]
            ], 403);
        }

        // Is authorized; process request
        $data = request()->all();

        if (request()->has('complete') && (request('complete') === 'A' || request('complete') === 'B')) {
            $data = array_merge($data, [
                'signed_off_by' => auth()->id(),
                'signed_off_at' => Carbon::now()
            ]);
        } elseif (request()->has('complete') && request('complete') === 0) {
            $data = array_merge($data, [
                'signed_off_by' => null,
                'signed_off_at' => null
            ]);
        }

        if (Arr::has($data, 'objectives')) {
            $allObjectivesForPackage = $package->lesson->objectives->pluck('id');

            $user->objectives()->detach($allObjectivesForPackage);

            $user->objectives()->attach(request('objectives'));

            Arr::forget($data, 'objectives');
        }

        if (request()->has('comment')) {
            $data = array_merge($data, [
                'commented_by' => auth()->id(),
                'commented_at' => Carbon::now()
            ]);
        }

        if (request()->has('recommendation_id') && request('recommendation_id') !== null) {
            $data = array_merge($data, [
                'recommended_by' => auth()->id(),
                'recommended_on' => Carbon::now()
            ]);
        }

        if (request()->has('recommendation_id') && request('recommendation_id') === null) {
            $data = array_merge($data, [
                'recommended_by' => null,
                'recommended_on' => null
            ]);
        }

        if (request()->has('recommendation_comment') && $package->recommendation_comment === null) {
            $data = array_merge($data, [
                'recommendation_comment_by' => auth()->id(),
                'recommendation_comment_at' => Carbon::now()
            ]);
        }

        if (request()->has('evaluation_details') && $package->evaluation_details === null) {
            $data = array_merge($data, [
                'evaluated_by' => auth()->id(),
                'evaluated_at' => Carbon::now()
            ]);
        }

        $package->update($data);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Package successfully updated.',
                'package' => new PackageResource($package)
            ]
        ], 200);
    }

    public function add(User $user)
    {
        request()->validate([
            'lesson_id' => 'required|exists:lessons,id'
        ]);

        Package::create([
            'lesson_id' => request('lesson_id'),
            'user_id' => $user->id
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Package successfully added.'
            ]
        ], 200);
    }
}
