<?php

namespace App\Http\Controllers\Logbooks\Api;

use App\User;
use App\Logbook;
use App\LogbookFile;
use App\LogbookPackage;
use App\LogbookCategory;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\Logbooks\LogbookShowResource;
use App\Http\Resources\Logbooks\LogbookIndexResource;
use App\Http\Resources\LogbookCategories\LogbookCategoryResource;

class LogbookController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        if (auth()->user()->hasRole(['administrator'])) {
            $users = Logbook::all()->pluck('id')->unique()->toArray();

            return LogbookIndexResource::collection(
                Logbook::all()->sortByDesc('created')
            )
                ->additional([
                    'meta' => [
                        'logbookCategories' => LogbookCategoryResource::collection(
                            LogbookCategory::all()
                        ),
                        'users' => $users
                    ]
                ]);
        }

        // Get the user's logbooks if any
        $logbooks = Logbook::whereUserId(auth()->id())->get();

        // Get all users who work under you
        $usersIds = auth()->user()->reportingStructure()->flatten(1)->map(function ($user) {
            if ($user['rank'] > auth()->user()->roles->first()->rank) {
                return $user['id'];
            }
        })->filter()->toArray();

        $users = User::whereIn('id', $usersIds)->get();

        // Get all logbooks of users who work under you
        $usersLogbooks = Logbook::whereIn('user_id', $usersIds)->get();

        return LogbookIndexResource::collection(
            $logbooks->merge($usersLogbooks)->sortByDesc('created')
        )
            ->additional([
                'meta' => [
                    'logbookCategories' => LogbookCategoryResource::collection(
                        LogbookCategory::all()
                    ),
                    'users' => $users->push(auth()->user())
                ]
            ]);
    }

    public function show(Logbook $logbook) {
        return new LogbookShowResource($logbook);
    }

    public function store()
    {
        if (auth()->user()->hasRole(['administrator', 'supervisor', 'apprentice']) === false) {
            return response()->json([
                'data' => [
                    'message' => 'You are not authorized to do that.'
                ]
            ], 403);
        }

        request()->validate([
            'logbook_category_id' => 'required|exists:logbook_categories,id',
            'created' => 'required|date',
            'event_description' => 'required|min:3',
            'details_of_event' => 'required|min:20',
            'files' => 'sometimes|array',
            'packages' => 'required|array',
            'packages.*' => 'exists:packages,id'
        ]);

        $data = array_merge(request()->all(), [
            'user_id' => auth()->id()
        ]);

        $logbook = Logbook::create($data);

        foreach (request('files') as $file) {
            LogbookFile::create([
                'logbook_id' => $logbook->id,
                'actual_filename' => $file['actualFilename'],
                'coded_filename' => $file['codedFilename']
            ]);
        }

        foreach (request('packages') as $packageId) {
            LogbookPackage::create([
                'logbook_id' => $logbook->id,
                'package_id' => $packageId
            ]);
        }

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Logbook successfully created'
            ]
        ], 200);
    }

    public function update(Logbook $logbook)
    {
        if (auth()->user()->hasRole(['administrator']) === false && auth()->id() !== $logbook->user_id) {
            return response()->json([
                'data' => [
                    'message' => 'You are not authorized to do that.'
                ]
            ], 403);
        }

        request()->validate([
            'logbook_category_id' => 'required|exists:logbook_categories,id',
            'created' => 'required|date',
            'event_description' => 'required|min:3',
            'details_of_event' => 'required|min:20',
            'files' => 'sometimes|array',
            'packages' => 'required|array',
            'packages.*' => 'exists:packages,id'
        ]);

        $data = array_merge(request()->all(), [
            'user_id' => auth()->id()
        ]);

        $logbook->logbookPackages->each->delete();

        foreach (request('packages') as $packageId) {
            LogbookPackage::firstOrCreate([
                'logbook_id' => $logbook->id,
                'package_id' => $packageId
            ]);
        }

        $logbook->update($data);

        foreach (request('files') as $file) {
            LogbookFile::create([
                'logbook_id' => $logbook->id,
                'actual_filename' => $file['actualFilename'],
                'coded_filename' => $file['codedFilename']
            ]);
        }

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Logbook successfully updated'
            ]
        ], 200);
    }

    public function destroy(Logbook $logbook)
    {
        if (auth()->user()->hasRole(['administrator']) === false && auth()->id() !== $logbook->user_id) {
            return response()->json([
                'data' => [
                    'message' => 'You are not authorized to do that.'
                ]
            ], 403);
        }

        $logbook->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Logbook successfully deleted'
            ]
        ], 200);
    }
}
