<x-layout>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <x-slot:title>Registered Events | CEMS</x-slot:title>
    <x-slot:metaDescription>View and manage all departments</x-slot:metaDescription>

    <x-slot:header>Registered Events</x-slot:header>

    {{-- Toast Notification --}}
    @if (session('toast'))
        <x-toast :type="session('toast.type')" :message="session('toast.message')" />
    @endif

    <section>
        <x-data-table>
            <x-slot:column>
                <th class="px-6 py-3">Sr. No.</th>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Action</th>
            </x-slot:column>

            @foreach($events as $event)
            <tr>
                <td class="px-6 py-4">
                    {{-- (currentPage() - 1) * perPage + index --}}
                    {{-- (1-1) * 10 + 1 = 1 --}}
                    {{-- (2-1) * 10 + 1 = 11 --}}
                    {{-- (3-1) * 10 + 1 = 21 --}}
                    {{ ($events->currentPage() - 1) * $events->perPage() + $loop->iteration }}
                </td>
                <td class="px-6 py-4">{{ $event->name }}</td>
                <td class="px-6 py-4">
                    <div class="flex gap-5 items-center">
                        <x-link :typeoflink="'link'" href="{{ route('events.show', $event->id) }}"
                            class="text-primary-600 dark:text-primary-500 me-0">
                            View
                        </x-link>
                        <form action="{{ route('registrations.destroy', $event->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <x-link :typeoflink="'button'"
                                onclick="return confirm('Are you sure? This action cannot be undone.')"
                                class="text-red-600 dark:text-red-500">
                                Cancel Registration
                            </x-link>
                        </form>
                        
                    </div>
                </td>
            </tr>
            @endforeach

            @if($events->isEmpty())
            <tr class="bg-white dark:bg-gray-800 text-nowrap">
                <td class="px-6 py-4 w-0">No event records found</td>
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
