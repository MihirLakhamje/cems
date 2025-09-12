<x-layout>
    <x-slot:title>{{ $event->name }} | CEMS</x-slot:title>
    <x-slot:metaDescription>View event details</x-slot:metaDescription>

    <x-slot:header>Event Details</x-slot:header>

    <div class="flex space-x-2 items-center mb-4">
        <x-link :typeoflink="'link'" href="{{ route('events.index') }}" class="text-primary-600 dark:text-primary-500">
            Back
        </x-link>
        <span class="text-gray-800 dark:text-white">|</span>
        <form action="{{ route('events.destroy', $event->id) }}" method="post">
            @csrf
            @method('DELETE')
            <x-link :typeoflink="'button'" onclick="return confirm('Are you sure? This action cannot be undone.')"
                class="text-red-600 dark:text-red-500">
                Delete
            </x-link>
        </form>
    </div>

    <section class=" rounded-lg dark:bg-gray-800 flex flex-col justify-between flex-wrap gap-10 ">
        <div>
            <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                {{ ucfirst($event->name) }}
            </h2>
            <div class="font-normal text-gray-700 dark:text-gray-400 flex flex-col gap-4">
                <p>
                    <span class="font-semibold">
                        Department:
                    </span>
                    {{ $event->department->name }}
                </p>
                <p>
                    <span class="font-semibold">
                        Registration Start Date:
                    </span>
                    {{ $event->start_date->format('d/m/Y') }}
                </p>
                <p>
                    <span class="font-semibold">
                        Registration End Date:
                    </span>
                    {{ $event->end_date->format('d/m/Y') }}
                </p>
                <p>
                    <span class="font-semibold">
                        Location:
                    </span>
                    {{ $event->location }}
                </p>
            </div>
        </div>

        @if (!auth()->user()->events()->where('event_id', $event->id)->exists())
            
        <form action="{{ route('registrations.store', $event->id) }}" method="POST">
            @csrf
            <div class="flex items-center gap-2">
                <button type="submit" onclick="return confirm('Are you sure? You want to register for this event.')"
                class="px-3 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Register</button>
                <span class="text-gray-700 dark:text-gray-400">Registration Fee: Rs.{{ $event->fees }}</span>
            </div>
        </form>
        @else
            <div class="flex items-center gap-2">
                <span class="text-gray-700 dark:text-gray-400">You are already registered for this event.</span>
            </div>
        @endif
    </section>
</x-layout>