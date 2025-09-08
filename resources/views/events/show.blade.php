<x-layout>
    <x-slot:title>{{ $event->name }} | CEMS</x-slot:title>
    <x-slot:metaDescription>View event details</x-slot:metaDescription>
    <x-slot:header>Event Details</x-slot:header>

    {{-- Back & Delete Buttons --}}
    <div class="flex items-center gap-4 mb-6">
        <x-link :typeoflink="'link'" href="{{ route('events.index') }}"
            class="text-blue-600 hover:underline dark:text-blue-400">
            ← Back
        </x-link>

        @can('delete', $event)
        <form action="{{ route('events.destroy', $event->id) }}" method="post" onsubmit="return confirm('Are you sure? This action cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                Delete
            </button>
        </form>
        @endcan
    </div>

    {{-- Event Card --}}
    <section class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6">
        <div class="grid md:grid-cols-2 gap-6">
            @if ($event->image)
                <img src="{{ asset('storage/' . $event->image) }}"
                    alt="{{ $event->name }}"
                    class="w-full h-64 object-cover rounded-xl">
            @endif

            <div class="flex flex-col justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $event->name }}</h1>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $event->description }}</p>

                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li><strong>Start Date:</strong> {{ $event->start_date->format('d/m/Y') }}</li>
                        <li><strong>End Date:</strong> {{ $event->end_date->format('d/m/Y') }}</li>
                        <li><strong>Location:</strong> {{ $event->location }}</li>
                        <li><strong>Fees:</strong> ₹{{ $event->fees }}</li>
                        <li><strong>Capacity:</strong> {{ $event->capacity }}</li>
                    </ul>
                </div>

                {{-- Registration logic --}}
                @auth
                    @can('registeration', $event)
                        <div class="mt-6">
                            @if ($event->users->contains(auth()->id()))
                                <form action="{{ route('registrations.destroy', $event) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="w-full sm:w-auto px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                                        Cancel Registration
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('registrations.store', $event) }}" method="POST">
                                    @csrf
                                    <button
                                        class="w-full sm:w-auto px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
                                        Register
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endcan
                @endauth
            </div>
        </div>
    </section>

    {{-- Registered Users Table --}}
    @can('eventUsers', $event)
    <section class="mt-10 bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Registered Users</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-3">Sr. No.</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Mobile No.</th>
                        <th class="px-6 py-3">College Name</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">{{ $user->phone }}</td>
                            <td class="px-6 py-4">{{ $user->college_name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No user records found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </section>
    @endcan
</x-layout>
