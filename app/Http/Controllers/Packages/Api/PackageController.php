<?php

namespace App\Http\Controllers\Packages\Api;

use App\User;
use App\Lesson;
use App\Package;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use App\Rules\IsAuthorizedToSignOff;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\Packages\PackageResource;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator'])->only(['store', 'add']);
        $this->middleware(['profile'])->only(['show']);
        $this->middleware(['role:head_of_operations|manager|administrator', 'profile'])->only(['update']);
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
            'complete' => 'sometimes|boolean',
            'signed_off_by' => [
                'sometimes',
                new IsAuthorizedToSignOff($user)
            ],
        ]);

        $package->update(request()->all());

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
