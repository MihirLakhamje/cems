<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\EventUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'organizer']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EventUser $eventUser): bool
    {
        return $eventUser->user_id === $user->id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can create models.
     */
    


    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EventUser $eventUser): bool
    {
        if ($user->role === 'organizer' && $eventUser->event->department->id === $user->department->id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EventUser $eventUser): bool
    {
        return $user->id === auth()->id() || $user->role === 'admin';
    }

    
}
