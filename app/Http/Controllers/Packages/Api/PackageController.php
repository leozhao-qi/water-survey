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
        $this->middleware(['role:administrator'])->only(['store', 'add']);
        $this->middleware(['profile'])->only(['show', 'update']);
    }

    public function show(User $user, Package $package)
    {
        return new PackageResource($package);
    }

    public function store(User $user)
    {
        request()->validate([
            'version' => 'required|exists:lesson_versions,id'
        ]);

        $lessons = Lesson::whereLessonVersionId(request('version'))->get();

        foreach ($lessons as $lesson) {
            Package::create([
                'user_id' => $user->id,
                'lesson_id' => $lesson->id
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

    public function update(User $user, Package $package)
    {
        request()->validate([
            'complete' => [
                'sometimes',
                'boolean',
                new IsValidCompletion($user, $package)
            ],
            'theory_status' => [
                'sometimes',
                Rule::in([
                    'incomplete', 'complete_eg3', 'complete_eg4', 'deferred', 'exempt'
                ])
            ],
            'practical_status' => [
                'sometimes',
                Rule::in([
                    'incomplete', 'complete_eg3', 'complete_eg4', 'deferred', 'exempt'
                ])
            ],
            'objectives.*' => [
                'sometimes',
                Rule::in($package->lesson->objectives->pluck('id')->toArray())
            ],
            'recommendation_id' => 'sometimes|nullable|exists:recommendations,id',
            'evaluation_details' => 'sometimes|min:20'
        ]);

        if (auth()->user()->can('update', $package)) {
            $data = request()->all();

            if (request()->has('complete') && request('complete') === 1) {
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

            if (request()->has('recommendation_comment')) {
                $data = array_merge($data, [
                    'recommendation_comment_by' => auth()->id(),
                    'recommendation_comment_at' => Carbon::now()
                ]);
            }

            if (request()->has('evaluation_details')) {
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

        return response()->json([
            'data' => [
                'message' => 'You are not authorized to do that.'
            ]
        ], 403);
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
