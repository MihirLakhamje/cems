<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
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
    public function view(User $user, Department $department): bool
    {
        // Admins can view all departments
        if ($user->role === 'admin') {
            return true;
        }
        if ($user->role === 'user') {
            return true;
        }
        // Organizers can view their own department
        if ($user->role === 'organizer' && $user->department && $user->department->id === $department->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create departments
        if ($user->role === 'admin') {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Department $department): bool
    {
        // Only admins can update departments
        if ($user->role === 'admin') {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Department $department): bool
    {
        // Only admins can delete departments
        if ($user->role === 'admin') {
            return true;
        }
        return false;
    }
}
