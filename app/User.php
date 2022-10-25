<?php

namespace App;

use App\User;
use App\Comment;
use App\Logbook;
use App\Package;
use App\Objective;
use App\MoodleUser;
use App\Supervisor;
use App\Deactivation;
use App\Traits\HasSupervisors;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'moodle_id', 'password', 'email', 'active', 'appointment_date'
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
        'firstname', 'lastname', 'role', 'fullname'
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

    public function moodleuser()
    {
        return $this->hasOne(MoodleUser::class, 'id', 'moodle_id');
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

    public function getFirstnameAttribute()
    {
        return $this->moodleProfile('firstname');
    }

    public function getLastnameAttribute()
    {
        return $this->moodleProfile('lastname');
    }

    public function getFullnameAttribute()
    {
        return $this->moodleProfile('firstname') . ' ' . $this->moodleProfile('lastname');
    }

    public function getRoleAttribute()
    {
        return $this->roles()->first()->name;
    }

    protected function moodleProfile($column)
    {
        $user = User::find($this->id);

         return MoodleUser::whereId($user->moodle_id)
            ->first()
            ->{$column};
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
