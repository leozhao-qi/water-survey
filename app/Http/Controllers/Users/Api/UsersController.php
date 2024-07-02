<?php

namespace App\Http\Controllers\Users\Api;

use App\Mail\UserRegistered;
use App\Supervisor;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function __construct ()
    {
        $this->middleware(['auth']);

        $this->middleware(['role:administrator'])->except(['show']);
    }

    public function index()
    {
        return User::whereActive(1)->get();
    }

    public function inactiveIndex()
    {
        return User::whereActive(0)->get();
    }

    public function show(User $user)
    {
        return new UserResource(User::find($user->id));
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'User successfully deleted'
            ]
        ], 200);
    }

    private function detect_names(string $full_name): array
    {
        $names = explode(' ', $full_name);
        $num = count($names);

        if ($num >= 2) {
            return array(
                "first_name" => implode(' ', array_slice($names, 0, -1)),
                "last_name" => $names[count($names) - 1]
            );
        } else if ($num == 1) {
            return array(
                "first_name" => $names[0],
                "last_name" => null
            );
        } else {
            return array(
                "first_name" => null,
                "last_name" => null
            );
        }
    }

    /**
     * Store a new user.
     *
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        request()->validate([
            'role' => 'required',
            'user' => 'required'
        ]);

        $fullname = $email = $role = null;
        if (request('user')) {
            $fullname = request('user')['full_name'];
            $email = request('user')['email'];
        }
        if (request('role')) {
            $role = request('role');
        }
        if (empty($fullname) || empty($email) || empty($role)) {
            return response()->json([
                'data' => [
                    'type' => 'failure',
                    'message' => 'User could not be added'
                ]
            ], 400);
        }

        $names = $this->detect_names($fullname);

        $newUser = User::create([
            'email' => $email,
            'active' => 1,
            'password' => bcrypt($password = bin2hex(openssl_random_pseudo_bytes(5))),
            'fullname' => $fullname,
            'firstname' => $names["first_name"],
            'lastname' => $names["last_name"]
        ]);
        $newUser->assignRole(
            Role::whereName($role)->get()->first()
        );

        if ($newUser->hasRole(['manager', 'head_of_operations', 'supervisor'])) {
            Supervisor::create([
                'user_id' => $newUser->id
            ]);
        }

        Mail::to($newUser)->send(new UserRegistered($newUser, $password));

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Users successfully added'
            ]
        ], 200);
    }
}
