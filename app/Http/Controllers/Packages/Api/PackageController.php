<?php

namespace App\Http\Controllers\Packages\Api;

use App\User;
use App\Lesson;
use App\Package;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\Packages\PackageResource;

class PackageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['profile']);
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
}
