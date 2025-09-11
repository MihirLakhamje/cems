<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
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

    public function stats()
    {
        //
    }

    public function profile()
    {
        return view('users.profile');
    }

    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'min:3'],
                'email' => ['required', 'email'],
            ]);

            $user = User::find(Auth::user()->id);
            $userEmail = User::where('email', $request->email)->first();
            if ($userEmail) {
                if ($userEmail->id != Auth::user()->id) {
                    return redirect()->route('users.profile')->with('error', 'Email already exists.');
                }
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return redirect()->route('users.profile')->with('success', 'You have successfully updated your profile.');
        } catch (Exception $e) {
            return redirect()->route('users.profile')->with('error', 'Something went wrong.');
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'password' => ['required', 'min:6', 'confirmed'],
            ]);

            $user = User::find(Auth::user()->id);
            $user->password = bcrypt($request->password);
            $user->save();
            return redirect()->route('users.profile')->with('success', 'You have successfully updated your password.');
        } catch (Exception $e) {
            return redirect()->route('users.profile')->with('error', 'Something went wrong.');
        }
    }

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
            dd($e->getMessage());
        }
    }


    public function show(User $user)
    {
        // return view('', compact('user'));
        dd($user);
    }


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
            return redirect()->route('users.index')->with('success', 'Role assigned successfully.');
        } catch (\Exception $e) {
            \Log::error('Error assigning role: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Something went wrong while assigning role.');
        }
    }

    public function delete(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('login')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Something went wrong while deleting user.');
        }
    }
}
