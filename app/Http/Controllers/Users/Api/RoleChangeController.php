<?php

namespace App\Http\Controllers\Users\Api;

use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class RoleChangeController extends Controller
{
    public function update(User $user)
    {
        request()->validate([
            'role' => 'required|exists:roles,name'
        ]);

        if (optional($user->supervisor) === null) {
            $user->supervisor->users()->detach();
        }
        
        $user->supervisors()->detach();

        $user->updateRole(request('role'));

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Role successfully changed'
            ]
        ], 200);
    }
}
