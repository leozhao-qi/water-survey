<?php

namespace App\Http\Controllers\Deactivations\Api;

use App\User;
use App\Deactivation;
use App\Rules\ValidDeactivation;
use App\Http\Controllers\Controller;
use App\Rules\NotBeforeAppointmentDate;
use App\Rules\NotBeforeDeactivationDate;

class DeactivationController extends Controller
{
    public function __construct ()
    {
        $this->middleware(['role:administrator|manager|head_of_operations']);
    }

    public function store(User $user)
    {
        request()->validate([
            'deactivated_at' => [
                'required', 
                'date', 
                new ValidDeactivation($user), 
                new NotBeforeAppointmentDate($user)
            ],
            'deactivation_rationale' => 'required|min:3'
        ]);

        $user->update([
            'active' => 0
        ]);

        $deactivation = Deactivation::make(request([
            'deactivated_at', 'deactivation_rationale'
        ]));

        $user->deactivations()->save($deactivation);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'User successfully deactivated'
            ]
        ], 200);
    }

    public function activate(User $user)
    {
        request()->validate([
            'reactivated_at' => [
                'required',
                'date',
                new NotBeforeDeactivationDate($user)
            ]
        ]);

        $user->update([
            'active' => 1
        ]);

        $deactivation = Deactivation::where('user_id', $user->id)->latest()->first();

        $deactivation->update([
            'reactivated_at' => request('reactivated_at')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'User successfully reactivated'
            ]
        ], 200);
    }

    public function update(Deactivation $deactivation)
    {
        request()->validate([
            'reactivated_at' => 'sometimes|required|date|after:deactivated_at',
            'deactivated_at' => 'required|date',
            'deactivation_rationale' => 'required|min:3'
        ]);

        $user = User::find($deactivation->user_id);

        if ($deactivation->reactivated_at === null || request()->has(reactivated_at)) {
            $user->update([
                'active' => 1
            ]);
        }

        $deactivation->update(request()->all());

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'User\'s deactivation successfully updated',
                'deactivation' => $deactivation
            ]
        ], 200);
    }

    public function destroy(Deactivation $deactivation)
    {
        $user = User::find($deactivation->user_id);

        if ($deactivation->reactivated_at === null) {
            $user->update([
                'active' => 1
            ]);
        }

        $deactivation->delete();

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Deactivation successfully deleted.'
            ]
        ], 200);
    }
}
