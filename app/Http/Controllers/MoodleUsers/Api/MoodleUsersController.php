<?php

namespace App\Http\Controllers\MoodleUsers\Api;

use App\User;
use App\Supervisor;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateRequest;

class MoodleUsersController extends Controller
{
    public function create()
    {
        return DB::connection('mysql2')
            ->table('mdl_user')
            ->select('id', 'firstname', 'lastname', 'email')
            ->where('id', '>', 2)
            ->whereNotIn('id', User::pluck('moodle_id')->toArray())
            ->where('email', 'LIKE', '%@%.%')
            ->get();
    }

    public function store(CreateRequest $request)
    {
        foreach (request()->all() as $user) {
            // Get the email address of the new user
            // from the Moodle database.
            $email = DB::connection('mysql2')
                ->table('mdl_user')
                ->select('email')
                ->where('id', $user['userId'])
                ->get()
                ->first()
                ->email;

            // Create the user
            $newUser = User::create([
                'moodle_id' => $user['userId'],
                'email' => $email,
                'active' => 1,
                'password' => bcrypt('Welcomein_22')
            ]);

            // Attach the role to the user.
            $newUser->assignRole(
                Role::find($user['roleId'])
            );

            if ($newUser->hasRole(['manager', 'head_of_operations', 'supervisor'])) {
                Supervisor::create([ 
                    'user_id' => $newUser->id 
                ]);
            }
        }

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Users successfully added'
            ]
        ], 200);
    }
}
