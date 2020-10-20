<?php

namespace App\Http\Controllers\LessonVersions\Api;

use App\LessonVersion;
use App\Classes\PackageVersion;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\LessonVersions\LessonVersionResource;

class LessonVersionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        
        $this->middleware(['role:administrator'])->except(['index']);
    }

    public function index()
    {
        return LessonVersionResource::collection(
            LessonVersion::all()
        );
    }

    public function store()
    {
        request()->validate([
            'version' => 'required|integer|unique:lesson_versions,version',
            'valid_on' => 'required|date'
        ]);

        $lessonVersion = LessonVersion::create([
            'version' => request('version'),
            'valid_on' => request('valid_on')
        ]);

        (new PackageVersion)->new($lessonVersion);

        DB::table('lessons_wip')->truncate();

        DB::table('objectives_wip')->truncate();
    }

    public function update(LessonVersion $lessonVersion)
    {
        request()->validate([
            'version' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($lessonVersion) {
                    if (($value !== $lessonVersion->version) && (LessonVersion::whereVersion($value)->get()->count())) {
                        $fail(ucfirst($attribute) . ' already exists.');
                    }
                }
            ],
            'valid_on' => 'required|date'
        ]);

        $lessonVersion->update([
            'version' => request('version'),
            'valid_on' => request('valid_on')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Lesson version successfully updated'
            ]
        ], 200);
    }
}
