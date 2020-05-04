<?php

namespace App\Http\Controllers\Users\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppointmentDateController extends Controller
{
    public function __construct ()
    {
        $this->middleware(['role:administrator']);
    }
    
    public function update(User $user)
    {
        request()->validate([
            'appointment_date' => 'required|date'
        ]);

        $user->update([
            'appointment_date' => request('appointment_date')
        ]);

        return response()->json([
            'data' => [
                'type' => 'success',
                'message' => 'Appointment date successfully changed'
            ]
        ], 200);
    }
}
