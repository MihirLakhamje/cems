<?php

namespace App\Http\Controllers;

use App\Mail\EventRegistrationConfirmation;
use App\Models\Event;
use App\Models\EventUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EventUserController extends Controller
{
    /**
     * Show all registrations for a specific event (organizer/admin).
     */
    public function index(Event $event)
    {
        try {
            $events = auth()->user()->events;
            return view('registrations.index', compact('events', 'events'));
        } catch (Exception $e) {
            \Log::error('Event registration failed: ' . $e->getMessage());
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Failed to load registrations.',
            ]);
        }
    }

    /**
     * Register current user for an event (status = pending).
     */
    public function store(Request $request, Event $event)
    {
        try {
            Gate::authorize('registeration', $event);
            $user = auth()->user();

            // Check if the user is already registered for the event
            if ($user->events()->where('event_id', $event->id)->exists()) {
                return redirect()->back()->with('toast', [
                    'type' => 'info',
                    'message' => 'You are already registered for this event.',
                ]);
            }

            // Attach user to event (pivot table)
            $user->events()->attach($event->id, [
                'status' => 'confirmed',
            ]);

            Mail::to($user->email)->send(
                new EventRegistrationConfirmation($user, $event)
            );

            return redirect()->route('registrations.my')->with('toast', [
                'type' => 'success',
                'message' => 'Successfully registered for the event. A confirmation email has been sent to your email address.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Event registration failed: ' . $e->getMessage());
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Failed to register for the event.',
            ]);
        }
    }

    /**
     * Cancel a registration (user).
     */
    public function destroy(Event $event)
    {
        try {
            $registration = EventUser::where('user_id', Auth::id())
                ->where('event_id', $event->id)
                ->firstOrFail();

            Gate::authorize('delete', $registration);

            $registration->delete();

            return redirect()->route('events.index')->with('toast', [
                'type' => 'success',
                'message' => 'Registration cancelled successfully.',
            ]);
        } catch (Exception $e) {
            \Log::error('Failed to cancel registration: ' . $e->getMessage());
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Failed to cancel registration.',
            ]);
        }
    }

    /**
     * Show registrations of the logged-in user.
     */
    public function myRegistrations()
    {
        try {
            $events = Auth::user()->events()->paginate(8); // includes pivot data

            return view('registrations.my', compact('events'));
        } catch (Exception $e) {
            return back()->withErrors('Failed to fetch your registrations.');
        }
    }

    /**
     * Update registration status (organizer/admin).
     */
    public function updateStatus(Request $request, EventUser $eventUser)
    {
        try {
            Gate::authorize('update', $eventUser);

            $data = $request->validate([
                'status' => 'required|in:pending,approved,rejected',
            ]);

            $eventUser->update(['status' => $data['status']]);

            return back()->with('success', 'Status updated to ' . $data['status']);
        } catch (Exception $e) {
            return back()->withErrors('Failed to update status.');
        }
    }
}
