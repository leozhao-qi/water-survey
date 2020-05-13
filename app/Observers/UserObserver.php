<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $user->deactivations->each->delete();
        
        if (!$user->hasRole('apprentice')) {
            $user->supervisor->users()->detach();
        }
        
        // If the user is supervised, detach all associations to them.
        $user->supervisors()->detach();

		// Delete the user from the supervisors table.
		if (!$user->hasRole('apprentice')) {
			$user->supervisor->delete();
		}

		// Delete the users role associatons.
		$user->roles()->detach();        
    }
}
