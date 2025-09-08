<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->role === 'organizer') {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        if (
            $user->role === 'organizer' &&
            $event->department &&
            $event->department_id === $user->department_id
        ) {
            return true;
        }
        return false;
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        if (
            $user->role === 'organizer' &&
            $event->department &&
            $event->department_id === $user->department_id
        ) {
            return true;
        }
        
        if ($user->role === 'admin') {
            return true;
        }
        return false;
    }

    public function eventUsers(User $user, Event $event): bool
    {
        return $user->role === 'admin'
            || ($user->role === 'organizer' && $event->department_id === $user->department_id);
    }
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return false;
    }
}
