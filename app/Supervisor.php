<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $fillable = [
    	'user_id'
    ];

    public function user()
    {
    	return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function role()
    {
        return $this->user->roles->first();
    }
}
