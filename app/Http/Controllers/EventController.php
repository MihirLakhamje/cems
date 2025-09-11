<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Event::query();

            // Select only required columns
            $query->select(['id', 'name', 'start_date', 'end_date', 'fees', 'department_id']);

            // Filters
            if ($request->filled('search')) {
                $query->where('name', 'LIKE', "%{$request->search}%");
            }
            if ($request->filled('start_date')) {
                $query->whereDate('start_date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('end_date', '<=', $request->end_date);
            }
            if ($request->input('owned') === '1' && auth()->check()) {
                $query->where('department_id', auth()->user()->department_id);
            }
            if ($request->filled('fees_min')) {
                $query->where('fees', '>=', $request->fees_min);
            }
            if ($request->filled('fees_max')) {
                $query->where('fees', '<=', $request->fees_max);
            }

            $user = auth()->user();

            $events = $query->with(['department:id,name'])
                ->withCount(['users as is_registered' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }])
                ->orderBy('start_date', 'desc')
                ->paginate(8)
                ->withQueryString();

            return view('events.index', [
                'events' => $events,
                'search' => $request->search,
            ]);
        } catch (\Exception $e) {
            \Log::error('Fetching events failed: ' . $e->getMessage());
            dd($e->getMessage());
        }
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Event::class);
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Event::class);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'location' => ['required', 'string', 'max:255'],
            'fees' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $event = new Event();
            $event->name = $request->name;
            $event->description = $request->description;
            $event->start_date = \Carbon\Carbon::createFromFormat('m/d/Y', $request->start_date)->format('Y-m-d');
            $event->end_date = \Carbon\Carbon::createFromFormat('m/d/Y', $request->end_date)->format('Y-m-d');
            $event->location = $request->location;
            $event->fees = $request->fees;
            $event->capacity = $request->capacity;
            $event->department_id = auth()->user()->department_id;
            $event->save();

            return redirect()->route('events.index')->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            \Log::error('Event creation failed: ' . $e->getMessage());
            dd($e->getMessage());
            // return redirect()->back()->with('error', 'Failed to create event. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $users = $event->users()->paginate(4);
        return view('events.show', [
            'event' => $event,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        Gate::authorize('update', $event);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'start_date' => ['required', 'date_format:d/m/Y'],
            'end_date' => ['required', 'date_format:d/m/Y', 'after_or_equal:start_date'],
            'location' => ['required', 'string', 'max:255'],
            'fees' => ['required', 'numeric', 'min:0'],
            'capacity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $event->update([
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d'),
                'location' => $request->location,
                'fees' => $request->fees,
                'capacity' => $request->capacity,
            ]);

            return redirect()->route('events.index')->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Event update failed: ' . $e->getMessage());
            dd($e->getMessage());
            // return redirect()->back()->with('error', 'Failed to update event. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        Gate::authorize('delete', $event);
        try {
            $event->delete();
            return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Event deletion failed: ' . $e->getMessage());
            // dd($e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete event. Please try again.');
        }
    }
}
