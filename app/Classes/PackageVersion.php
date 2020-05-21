<?php

namespace App\Classes;

use App\Lesson;
use App\LessonWIP;
use App\Objective;
use App\ObjectiveWIP;
use App\LessonVersion;

class PackageVersion
{
    protected $latestVersion;

    protected $hasWIP;

    public function __construct()
    {
        $this->latestVersion = LessonVersion::orderBy('version', 'desc')->first();

        $this->hasWIP = LessonWIP::count();
    }

    public function new(LessonVersion $lessonVersion)
    {
        $lessons = LessonWIP::all();

        foreach ($lessons as $lesson) {
            $newLesson = Lesson::create([
                'number' => $lesson->number,
                'level_id' => $lesson->level_id,
                'name' => [
                    'en' => $lesson->getTranslation('name', 'en'),
                    'fr' => $lesson->getTranslation('name', 'fr')
                ],
                'lesson_version_id' => $lessonVersion->id
            ]);

            $this->populateObjectives($newLesson);
        }
    }

    public function populateWIP()
    {
        if ($this->hasWIP === 0) {
            $this->populateLessons();
        }
    }

    protected function populateLessons()
    {
        $lessons = Lesson::whereLessonVersionId($this->latestVersion->id)->get();

        foreach ($lessons as $lesson) {
            $lessonWIP = LessonWIP::create([
                'number' => $lesson->number,
                'level_id' => $lesson->level_id,
                'name' => [
                    'en' => $lesson->getTranslation('name', 'en'),
                    'fr' => $lesson->getTranslation('name', 'fr')
                ]
            ]);

            $this->populateWIPObjectives($lessonWIP, $lesson);
        }
    }

    protected function populateWIPObjectives(LessonWIP $lessonWIP, Lesson $lesson)
    {
        $objectives = Objective::whereLessonId($lesson->id)->get();

        foreach ($objectives as $objective) {
            ObjectiveWIP::create([
                'number' => $objective->number,
                'lesson_id' => $lessonWIP->id,
                'name' => [
                    'en' => $objective->getTranslation('name', 'en'),
                    'fr' => $objective->getTranslation('name', 'fr')
                ],
                'type' => $objective->type
            ]);
        }
    }

    protected function populateObjectives(Lesson $lesson)
    {
        $lessonWIP = LessonWIP::whereNumber($lesson->number)->first();

        $objectives = ObjectiveWIP::whereLessonId($lessonWIP->id)->get();

        foreach ($objectives as $objective) {
            Objective::create([
                'number' => $objective->number,
                'lesson_id' => $lesson->id,
                'name' => [
                    'en' => $objective->getTranslation('name', 'en'),
                    'fr' => $objective->getTranslation('name', 'fr')
                ],
                'type' => $objective->type
            ]);
        }
    }
}