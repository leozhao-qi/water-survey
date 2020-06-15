<?php

namespace App\Http\Controllers\Logbooks\Api;

use App\User;
use App\LogbookFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class LogbookFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function meta()
    {
        return uniqid(true);
    }

    public function upload()
    {
        request()->validate([
            'file' => 'required|file|mimes:jpeg,bmp,png,pdf,doc,docx,ppt,pptx,xls,xlsx'
        ]);

        $upload = request()->file('file');

        $extension = strtolower(
            explode('.', request()->file('file')->getClientOriginalName())[1]
        );

        Storage::putFileAs(
            '/public/entries/' . auth()->id(), $upload, request('id') . '.' . $extension
        );

        return [
            'codedFilename' => request('id') . '.' . $upload->getClientOriginalExtension(),
            'actualFilename' => request()->file('file')->getClientOriginalName()
        ];
    }

    public function download()
    {
        $pathToFile = "/public/entries/" . request()->query('user') . "/" . request()->query('file');

        $actualFilename = LogbookFile::whereCodedFilename(request()->query('file'))
            ->first()
            ->actual_filename;

        return Storage::download($pathToFile, $actualFilename);
    }

    public function updateFilename(LogbookFile $file)
    {
        if (auth()->id() !== $file->logbook->user->id) {
            return response()->json([
                'data' => [
                    'message' => 'You are not authorized to do that.'
                ]
            ], 403);
        }

        request()->validate([
            'actual_filename' => 'required|min:7'
        ]);

        $file->update(request()->all());

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Filename successfully changed',
                'file' => $file
            ]
        ], 200);
    }

    public function destroy(LogbookFile $file)
    {
        if (auth()->id() !== $file->logbook->user->id) {
            return response()->json([
                'data' => [
                    'message' => 'You are not authorized to do that.'
                ]
            ], 403);
        }
        
        $pathToFile = "/public/entries/" . auth()->id() . "/{$file->coded_filename}";

        Storage::delete($pathToFile);

        $file->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Filename successfully deleted'
            ]
        ], 200);
    }
}
