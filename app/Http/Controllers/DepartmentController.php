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
            //code...
            $query = Department::query();

            // 🔍 Search
            if ($request->filled('search')) {
                $query->where('name', 'LIKE', "%{$request->search}%");
            }

            // 👤 Owned filter
            if ($request->input('owned') === '1' && auth()->check()) {
                $query->where('id', auth()->user()->department_id);
            }

            // 📌 Status filter (boolean)
            if ($request->filled('is_active')) {
                $query->where('is_active', (int) $request->is_active);
            }

            // 🎭 Type filter (multiple checkboxes)
            if ($request->filled('type')) {
                $query->whereIn('fest_type', $request->type);
            }

            // 📅 Order latest & paginate
            $departments = $query
                ->select(['id', 'name', 'is_active', 'fest_type', 'created_at'])
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
            dd($th->getMessage());
            // return redirect()->back()->with('error', 'Failed to fetch departments.');
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Department::class);
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Department::class);
        //STEP 1: Validate the request data
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name', 'min:1'],
            'head_name' => ['required', 'string', 'max:255', 'min:3',],
            'head_email' => ['required', 'email', 'max:255', 'unique:departments,head_email'],
            'head_phone' => ['required', 'string', 'max:15', 'unique:departments,head_phone'],
            'fest_type' => ['required', 'string', 'in:dept_fest,association,clg_fest'],
        ]);

        try {
            //STEP 2: Create a new department instance and save it to the database
            $department = Department::create([
                'name' => $request->name,
                'head_name' => $request->head_name,
                'head_email' => $request->head_email,
                'head_phone' => $request->head_phone,
                'fest_type' => $request->fest_type,
            ]);

            //STEP 3: Redirect to the departments index with a success message
            return redirect()->route('departments.index')->with('success', 'Department created successfully.');
        } catch (Exception $e) {
            //STEP 4: Handle any exceptions that occur during the process
            \Log::error('Failed to create department: ' . $e->getMessage());
            // Redirect back with an error message
            dd($e->getMessage());
            // return redirect()->back()->with('error', 'Failed to create department.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return view('departments.show', [
            'department' => $department,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        Gate::authorize('update', $department);
        // Step 1: Validate the request data
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:1'],
            'head_name' => ['required', 'string', 'max:255', 'min:3'],
            'head_email' => ['required', 'email', 'max:255', 'unique:departments,head_email,' . $department->id],
            'head_phone' => ['required', 'string', 'max:15', 'unique:departments,head_phone,' . $department->id],
            'fest_type' => ['required', 'string', 'in:dept_fest,association,clg_fest'],
            'is_active' => ['required', 'boolean'],
        ]);

        try {
            // Step 2: Update the department with the validated data
            $department->update([
                'name' => $request->name,
                'head_name' => $request->head_name,
                'head_email' => $request->head_email,
                'head_phone' => $request->head_phone,
                'fest_type' => $request->fest_type,
                'is_active' => $request->is_active,
            ]);

            // Step 3: Save the updated department
            $department->save();

            // Step 4: Redirect to the departments index with a success message
            return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
        } catch (Exception $e) {
            // Step 5: Handle any exceptions that occur during the update process
            \Log::error('Failed to update department: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update department.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        Gate::authorize('delete', $department);
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}
