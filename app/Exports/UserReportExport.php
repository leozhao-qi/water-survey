<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserReportExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'First name',
            'Last name',
            'Appointment date',
            'Role',
            'Active'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all()
        ->sortBy('role')
        ->map(function ($user) {
            return [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'appointment_date' => optional($user->appointment_date)->format('m/d/Y'),
                'role' => ucfirst(str_replace('_', ' ', $user->role)),
                'active' => $user->active ? 'Yes' : 'No'
            ];
        });
    }
}
