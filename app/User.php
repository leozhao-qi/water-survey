<?php

namespace App;


use App\Traits\HasSupervisors;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable,
        HasRoles,
        HasSupervisors;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password', 'email', 'active', 'appointment_date', 'firstname', 'lastname', 'fullname'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'appointment_date' => 'date'
    ];

    protected $appends = [
        'role'
    ];

    public function deactivations()
    {
        return $this->hasMany(Deactivation::class)
            ->latest();
    }

    public function supervisor()
    {
        return $this->hasOne(Supervisor::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function objectives()
    {
        return $this->belongsToMany(Objective::class, 'objective_user');
    }

    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getRoleAttribute()
    {
        return $this->roles()->first()->name;
    }

    public function updateRole($role)
	{
        $this->roles()->detach();

        if ($this->supervisor !== null) {
            $this->supervisor()->delete();
        }

		if ($role !== 'apprentice') {
			Supervisor::create([
				'user_id' => $this->id
			]);
		}

		return $this->assignRole($role);
	}
}
