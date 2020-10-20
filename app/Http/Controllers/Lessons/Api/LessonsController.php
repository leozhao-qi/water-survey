<?php

namespace App\Http\Controllers\Lessons\Api;

use App\Lesson;
use App\LessonVersion;
use App\Rules\UniqueLesson;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Rules\UniqueLessonForVersion;
use App\Http\Resources\Lessons\LessonResource;

class LessonsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:administrator', 'auth']);
    }

    public function index()
    {
        return LessonResource::collection(
            Lesson::all()
        );
    }

    public function store()
    {
        request()->validate([
            'level_id' => 'required|integer|min:1|exists:levels,id',
            'number' => [
                'required',
                new UniqueLessonForVersion
            ],
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'lesson_version_id' => 'sometimes|exists:lesson_versions,id',
            'completed_in_both' => 'sometimes|boolean',
            'topic_id' => 'required|integer|exists:topics,id'
        ]);

        if (request('lesson_version_id')) {
            $this->createLesson(request('lesson_version_id'));
        } else {
            $versions = LessonVersion::all();

            foreach ($versions as $version) {
                $this->createLesson($version->id);
            }
        }

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Lesson successfully created'
            ]
        ], 200);
    }

    public function update(Lesson $lesson)
    {
        request()->validate([
            'level_id' => 'required|integer|min:1|exists:levels,id',
            'number' => [
                'required',
                new UniqueLesson($lesson)
            ],
            'name_en' => 'required|min:3',
            'name_fr' => 'required|min:3',
            'completed_in_both' => 'required|boolean',
            'topic_id' => 'required|integer|exists:topics,id'
        ]);

        $lesson->update([
            'level_id' => request('level_id'),
            'number' => request('number'),
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'completed_in_both' => request('completed_in_both'),
            'topic_id' => request('topic_id')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Lesson successfully updated'
            ]
        ], 200);
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Lesson successfully deleted'
            ]
        ], 200);
    }

    protected function createLesson($versionId)
    {
        return Lesson::create([
            'level_id' => request('level_id'),
            'number' => request('number'),
            'name' => [
                'en' => request('name_en'),
                'fr' => request('name_fr')
            ],
            'lesson_version_id' => $versionId,
            'completed_in_both' => request('completed_in_both'),
            'topic_id' => request('topic_id')
        ]);

    }
}
