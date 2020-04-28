<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deactivation extends Model
{
    protected $fillable = [
        'reactivated_at',
        'deactivated_at',
        'deactivation_rationale'
    ];
}
