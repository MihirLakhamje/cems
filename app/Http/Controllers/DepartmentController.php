<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Department::query();

            // Filters
            if ($request->filled('search')) {
                $query->where('name', 'LIKE', "%{$request->search}%");
            }

            if ($request->input('owned') === '1' && auth()->check()) {
                $query->where('id', auth()->user()->department_id);
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', (int) $request->is_active);
            }

            if ($request->filled('type')) {
                $query->whereIn('fest_type', $request->type);
            }

            $departments = $query
                ->with([
                    'events:id,department_id,name,start_date,end_date,fees'
                ])
                ->latest()
                ->paginate(8);

            return view('departments.index', [
                'departments' => $departments,
                'search' => $request->search,
            ]);
        } catch (\Exception $th) {
            \Log::error('Fetching departments failed: ' . $th->getMessage());
            return redirect()->back()->with('toast', ['type' => 'error', 'message' => 'Failed to load departments.']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Department::class);
        try {
            return view('departments.create');
        } catch (\Exception $e) {
            \Log::error('Error loading create department form: ' . $e->getMessage());
            return redirect()->back()->with('toast', ['type' => 'error', 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Department::class);

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name', 'min:1'],
            'head_name' => ['required', 'string', 'max:255', 'min:3',],
            'head_email' => ['required', 'email', 'max:255', 'unique:departments,head_email'],
            'head_phone' => ['required', 'string', 'max:15', 'unique:departments,head_phone'],
            'fest_type' => ['required', 'string', 'in:dept_fest,association,clg_fest'],
        ]);

        try {
            $department = Department::create([
                'name' => $request->name,
                'head_name' => $request->head_name,
                'head_email' => $request->head_email,
                'head_phone' => $request->head_phone,
                'fest_type' => $request->fest_type,
            ]);

            return redirect()->route('departments.index')->with('toast', ['type' => 'success', 'message' => 'Department created successfully.']);
        } catch (Exception $e) {
            \Log::error('Failed to create department: ' . $e->getMessage());
            return redirect()->back()->with('toast', ['type' => 'error', 'message' => 'Failed to create department.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        try {
            return view('departments.show', [
                'department' => $department,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error displaying department: ' . $e->getMessage());
            return redirect()->route('departments.index')->with('toast', [
                'type' => 'error',
                'message' => 'Failed to load department details.',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        Gate::authorize('update', $department);
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:1'],
            'head_name' => ['required', 'string', 'max:255', 'min:3'],
            'head_email' => ['required', 'email', 'max:255', 'unique:departments,head_email,' . $department->id],
            'head_phone' => ['required', 'string', 'max:15', 'unique:departments,head_phone,' . $department->id],
            'fest_type' => ['required', 'string', 'in:dept_fest,association,clg_fest'],
            'is_active' => ['required', 'boolean'],
        ]);

        try {
            $department->update([
                'name' => $request->name,
                'head_name' => $request->head_name,
                'head_email' => $request->head_email,
                'head_phone' => $request->head_phone,
                'fest_type' => $request->fest_type,
                'is_active' => $request->is_active,
            ]);

            $department->save();

            return redirect()->route('departments.index')->with('toast', ['type' => 'success', 'message' => 'Department updated successfully.']);
        } catch (Exception $e) {
            \Log::error('Failed to update department: ' . $e->getMessage());
            return redirect()->route('departments.index')->with('toast', ['type' => 'error', 'message' => 'Failed to update department.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        Gate::authorize('delete', $department);
        try {
            $department->events()->delete();
            return redirect()->route('departments.index')->with('toast', ['type' => 'success', 'message' => 'Department and its events deleted successfully.']);
        } catch (Exception $e) {
            \Log::error('Failed to delete associated events: ' . $e->getMessage());
            return redirect()->back()->with('toast', ['type' => 'error', 'message' => 'Failed to delete department events.']);
        }
    }
}
