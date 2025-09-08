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
            $searchEvent = $request->query('search');
            if ($searchEvent) {
                $events = Event::where('name', 'LIKE', "%{$searchEvent}%")->paginate(8);
            } else {
                $events = Event::latest()->paginate(8);
            }
            $user = auth()->user();

            // Get IDs of events the user has registered for
            $registeredEventIds = $user ? $user->events()->pluck('event_id')->toArray() : [];

            // Step 2: Return the view with the departments data
            return view('events.index', [
                'events' => $events,
                'search' => $searchEvent,
                'registeredEventIds' => $registeredEventIds,
            ]);
        } catch (\Exception $e) {
            \Log::error('Fetching events failed: ' . $e->getMessage());
            dd($e->getMessage());
            // return redirect()->back()->with('error', 'Failed to fetch events. Please try again.');
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
