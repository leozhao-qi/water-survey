<?php

namespace App\Http\Controllers\Logbooks\Api;

use App\User;
use App\Logbook;
use App\Package;
use Carbon\Carbon;
use App\LogbookFile;
use App\LogbookPackage;
use App\LogbookCategory;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\Packages\PackageResource;
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

        // If you are a supervisor, get your apprentice user id's so that they can
        // be used to populate the 'Apprentices' select menu when you are creating 
        // 'Supervisor comment' logbook entries.
        $apprenticeIds = [];

        if (auth()->user()->hasRole(['supervisor'])) {
            $apprenticeIds = auth()->user()->reportingStructure()->flatten(1)->map(function ($user) {
                if ($user['role'] === 'apprentice') {
                    return $user['id'];
                }
            })->filter()->toArray();
        }

        // Get all logbooks of users who work under you
        $usersLogbooks = Logbook::whereIn('user_id', $usersIds)->get();

        // If you are an apprentice, get any logbooks which reference your name
        $referencedLogbooks = Logbook::whereReferences(auth()->id())->get();

        // Get the names of supervisors who reference you in their logbook entries 
        // so that you can filter by their names on the logbook index page.
        $referencedLogbookUserIds = array_unique($referencedLogbooks->pluck('user_id')->toArray());

        if (count($referencedLogbookUserIds)) {
            $users = $users->merge(User::find($referencedLogbookUserIds));
        }

        $logbooks = $logbooks->merge($usersLogbooks)->merge($referencedLogbooks);
        $logbookPackages = LogbookPackage::whereIn('logbook_id', $logbooks->pluck('id')->toArray())->get();
        $packages = Package::whereIn('id', $logbookPackages->pluck('package_id')->toArray())->get();

        return LogbookIndexResource::collection(
            $logbooks->sortByDesc('created')
        )
            ->additional([
                'meta' => [
                    'logbookCategories' => LogbookCategoryResource::collection(
                        LogbookCategory::all()
                    ),
                    'users' => $users->push(auth()->user()),
                    'apprentices' => count($apprenticeIds) ? User::find($apprenticeIds) : [],
                    'packages' => PackageResource::collection($packages)
                ]
            ]);
    }

    public function show(Logbook $logbook) {
        return new LogbookShowResource($logbook);
    }

    public function store()
    {
        if (auth()->user()->hasRole(['supervisor', 'apprentice']) === false) {
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
            'packages.*' => 'exists:packages,id',
            'references' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ((int) request('logbook_category_id') === 4 && !$value) {
                        $fail('If this is a supervisor comment, you must reference an apprentice.');
                    }
                },
                'exists:users,id'
            ]
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
                'message' => 'Logbook entry successfully created'
            ]
        ], 200);
    }

    public function update(Logbook $logbook)
    {
        //  Check if you have the right permissions.
        if (auth()->user()->hasRole(['administrator']) === false && auth()->id() !== $logbook->user_id) {
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
            'logbook_category_id' => 'required|exists:logbook_categories,id',
            'created' => 'required|date',
            'event_description' => 'required|min:3',
            'details_of_event' => 'required|min:20',
            'files' => 'sometimes|array',
            'packages' => 'required|array',
            'packages.*' => 'exists:packages,id'
        ]);
        
        // Set a $data variable so that we can persist other stuff, besides
        // the request data, to the database
        $data = array_merge(request()->all(), [
            'user_id' => auth()->id()
        ]);

        // Needs to be fixed. Delete all logbook packages and then 
        // add them back, via what is in the request.
        $logbook->logbookPackages->each->delete();

        foreach (request('packages') as $packageId) {
            LogbookPackage::firstOrCreate([
                'logbook_id' => $logbook->id,
                'package_id' => $packageId
            ]);
        }

        $logbook->update($data);

        // Any files in the request? Persist those to logbook_files table
        foreach (request('files') as $file) {
            LogbookFile::create([
                'logbook_id' => $logbook->id,
                'actual_filename' => $file['actualFilename'],
                'coded_filename' => $file['codedFilename']
            ]);
        }

        // Nothing on the logbooks table may have been updated, but the
        // pivot tables may have. So, just to make sure, we explicity 
        // change the updated_at column.
        $logbook->update([
            'updated_at' => Carbon::now()
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Logbook entry successfully updated'
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

        $logbook->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Logbook entry successfully deleted'
            ]
        ], 200);
    }
}
