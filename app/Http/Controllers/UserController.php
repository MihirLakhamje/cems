<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show the user dashboard with counts.
     */
    public function home()
    {
        $user_count = User::count(); // Get the total user count
        $event_count = Event::count(); // Get the total event count
        $department_count = Department::count(); // Get the total department count
        return view('users.home', [
            'user_count' => $user_count,
            'event_count' => $event_count,
            'department_count' => $department_count
        ]);
    }

    /**
     * Show the user's profile.
     */
    public function profile()
    {
        return view('users.profile');
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', 'exists:users,email' . Auth::id()],
        ]);
        try {
            $user = Auth::user();
            $user->fill($request->only(['name', 'email']));
            $user->save();

            return redirect()->route('users.profile')->with('toast', ['type' => 'success', 'message' => 'You have successfully updated your profile.']);
        } catch (Exception $e) {
            \Log::error('Profile update failed: ' . $e->getMessage());
            return redirect()->route('users.profile')->with('toast', ['type' => 'error', 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ]);
        try {
            $user = Auth::user();
            $user->password = bcrypt($request->password);
            $user->save();
            return redirect()->route('users.profile')->with('toast', ['type' => 'success', 'message' => 'Password updated successfully.']);
        } catch (Exception $e) {
            return redirect()->route('users.profile')->with('toast', ['type' => 'error', 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Display a listing of users with filters and pagination.
     */
    public function index(Request $request)
    {
        try {
            $query = User::query();

            // Select only required columns
            $query->select(['id', 'name', 'email', 'role', 'department_id', 'created_at']);

            // 🔍 Search filter
            if ($request->filled('search')) {
                $query->where('name', 'LIKE', "%{$request->search}%");
            }

            // 🏢 Department assigned filter
            if ($request->exists('department_assigned')) {
                if ($request->department_assigned === 'yes') {
                    $query->whereNotNull('department_id'); // Assigned
                } elseif ($request->department_assigned === 'no') {
                    $query->whereNull('department_id'); // Not assigned
                }
            }

            // 🎭 Role filter (multiple checkboxes)
            if ($request->filled('role')) {
                $query->whereIn('role', $request->role);
            }

            // 📅 Order latest & paginate
            $users = $query->with('department:id,name')
                ->latest('created_at')
                ->paginate(8)
                ->withQueryString();

            // Fetch departments for dropdowns
            $departments = Department::select(['id', 'name'])->latest('created_at')->get();

            return view('users.index', [
                'users' => $users,
                'departments' => $departments,
                'search' => $request->search,
            ]);
        } catch (\Exception $e) {
            \Log::error('Fetching users failed: ' . $e->getMessage());
            return redirect()
                ->route('users.index')
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Failed to load users.',
                ]);
        }
    }

    /**
     * Assign role and department to a user.
     */
    public function assign_role(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:user,organizer'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        try {
            $user->role = $request->role;
            if ($request->role === 'organizer') {
                $user->department_id = $request->department_id;
            } else {
                $user->department_id = null; // Clear department if not an organizer
            }
            $user->save();
            return redirect()->route('users.index')->with('toast', ['type' => 'success', 'message' => 'Role assigned successfully.']);
        } catch (\Exception $e) {
            \Log::error('Error assigning role: ' . $e->getMessage());
            return redirect()->route('users.index')->with('toast', ['type' => 'error', 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Delete a user.
     */
    public function delete(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('login')->with('toast', ['type' => 'success', 'message' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->route('users.index')->with('toast', ['type' => 'error', 'message' => 'Something went wrong.']);
        }
    }
}
