<?php

namespace App\Http\Controllers\Logbooks\Api;

use App\Comment;
use App\Logbook;
use App\Package;
use App\Http\Controllers\Controller;
use App\Http\Resources\Comments\CommentResource;

class LogbookCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Logbook $logbook)
    {
        return CommentResource::collection(
            $logbook->comments()
                ->with(['user', 'commentable'])
                ->get()
        );
    }

    public function store(Logbook $logbook)
    {
        if (auth()->user()->hasRole(['administrator', 'supervisor', 'apprentice']) === false) {
            return response()->json([
                'data' => [
                    'message' => 'You are not authorized to do that.'
                ]
            ], 403);
        }

        // Check if any lesson packages have been marked as complete for
        // this logbook. If so, disallow updating.
        $packages = Package::whereIn('id', $logbook->logbookPackages->pluck('package_id')->toArray())->get();

        foreach ($packages as $package) {
            if ($package->complete) {
                return response()->json([
                    'data' => [
                        'message' => 'You are not authorized to do that.'
                    ]
                ], 403);
            }
        }

        request()->validate([
            'body' => 'required|max:5000'
        ]);

        $comment = $logbook->comments()->make([
            'body' => request('body')
        ]);

        auth()->user()->comments()->save($comment);

        return response()->json([
            'data' => [
                'message' => 'Comment successfully added.'
            ]
        ], 200);
    }

    public function update(Logbook $logbook, Comment $comment)
    {
        if (auth()->user()->hasRole(['administrator']) === false && auth()->id() !== $comment->user->id) {
            return response()->json([
                'data' => [
                    'message' => 'You are not authorized to do that.'
                ]
            ], 403);
        }

        // Check if any lesson packages have been marked as complete for
        // this logbook. If so, disallow updating.
        $packages = Package::whereIn('id', $logbook->logbookPackages->pluck('package_id')->toArray())->get();

        foreach ($packages as $package) {
            if ($package->complete) {
                return response()->json([
                    'data' => [
                        'message' => 'You are not authorized to do that.'
                    ]
                ], 403);
            }
        }

        request()->validate([
            'body' => 'required|max:5000'
        ]);

        $comment->update([
            'body' => request('body')
        ]);

        return response()->json([
            'data' => [
                'message' => 'Comment successfully updated.',
                'comment' => new CommentResource($comment)
            ]
        ], 200);
    }

    public function destroy(Logbook $logbook, Comment $comment)
    {
        if (auth()->user()->hasRole(['administrator']) === false && auth()->id() !== $comment->user->id) {
            return response()->json([
                'data' => [
                    'message' => 'You are not authorized to do that.'
                ]
            ], 403);
        }

        // Check if any lesson packages have been marked as complete for
        // this logbook. If so, disallow updating.
        $packages = Package::whereIn('id', $logbook->logbookPackages->pluck('package_id')->toArray())->get();

        foreach ($packages as $package) {
            if ($package->complete) {
                return response()->json([
                    'data' => [
                        'message' => 'You are not authorized to do that.'
                    ]
                ], 403);
            }
        }

        $comment->delete();

        return response()->json([
            'data' => [
                'message' => 'Comment successfully deleted.',
                'comment' => new CommentResource($comment)
            ]
        ], 200);
    }
}
