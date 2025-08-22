<x-layout>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <x-slot:title>Registered Events | CEMS</x-slot:title>
    <x-slot:metaDescription>View and manage all departments</x-slot:metaDescription>

    <x-slot:header>Registered Events</x-slot:header>

    <section class="flex flex-col gap-2" x-data="{
        registeredEventModal: @json($errors->any()),
        selected: {
            id: '{{ old('id') ?? '' }}',
            eventName: '{{ old('name') ?? '' }}',
            location: '{{ old('location') ?? '' }}',
            status: '{{ old('status') ?? '' }}',
            RegisteredAt: '{{ old('created_at') ?? '' }}',
        }
    }">

        <x-data-table>
            <x-slot:column>
                <th class="px-6 py-3">Event Name</th>
                <th class="px-6 py-3">Location</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Registered Date</th>
                <th class="px-6 py-3">Action</th>
            </x-slot:column>

            @foreach ($events as $event)
                <tr>
                    <td class="px-6 py-4">{{ $event->name }}</td>
                    <td class="px-6 py-4">{{ $event->location ?? 'TBA' }}</td>
                    <td class="px-6 py-4">
                        @if ($event->pivot->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($event->pivot->status === 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $event->pivot->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-5 items-center">

                            <x-link :typeoflink="'link'" href="{{ route('events.show', $event->id) }}"
                                class="text-blue-600 dark:text-blue-500 me-0">
                                View
                            </x-link>

                            <form action="{{ route('registered-events.cancel', $event->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <x-link :typeoflink="'button'"
                                    onclick="return confirm('Are you sure? This action cannot be undone.')"
                                    class="text-red-600 dark:text-red-500">
                                    Cancel registration
                                </x-link>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach

            @if ($events->isEmpty())
                <tr class="bg-white dark:bg-gray-800 text-nowrap">
                    <td class="px-6 py-4 w-0">No registered event records found</td>
                    <td class="px-6 py-4"> </td>
                    <td class="px-6 py-4"> </td>
                    <td class="px-6 py-4"> </td>
                    <td class="px-6 py-4"> </td>
                </tr>
            @endif
        </x-data-table>

        <div class="mt-4">
            {{ $events->links() }}
        </div>
    </section>

</x-layout>
