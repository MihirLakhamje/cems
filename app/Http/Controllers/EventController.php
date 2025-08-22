<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with('department')->latest()->paginate(8);
        return view('events.index', [
            'events' => $events,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        return view('events.show', [
            'event' => $event,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
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
        try {
            $event->delete();
            return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Event deletion failed: ' . $e->getMessage());
            // dd($e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete event. Please try again.');
        }
    }

    /**
     * Handle event registration.
     */
    public function register(Request $request, Event $event)
    {
        $request->validate([
            'event_id' => ['required', 'exists:events,id'],
        ]);

        try {
            $user = auth()->user();

            // Check if the user is already registered for the event
            if ($user->events()->where('event_id', $event->id)->exists()) {
                return redirect()->back()->with('error', 'You are already registered for this event.');
            }

            // Attach user to event (pivot table)
            $user->events()->attach($event->id, [
                'status' => 'pending', // default registration status
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Successfully registered for the event!');
        } catch (\Exception $e) {
            \Log::error('Event registration failed: ' . $e->getMessage());
            // dd($e->getMessage());
            return redirect()->back()->with('error', 'Failed to register for the event. Please try again.');
        }
    }
}
