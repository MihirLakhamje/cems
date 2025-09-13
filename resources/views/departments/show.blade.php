<x-layout>
    <x-slot:title>{{ $department->name }} | CEMS</x-slot:title>
    <x-slot:metaDescription>View department details</x-slot:metaDescription>
    <x-slot:header>Department Details</x-slot:header>

    {{-- Toast Notification --}}
    @if (session('toast'))
        <x-toast :type="session('toast.type')" :message="session('toast.message')" />
    @endif

    {{-- Back button --}}
    <div class="flex items-center gap-4 mb-4">
        <x-link :typeoflink="'link'" href="{{ route('departments.index') }}"
            class="text-primary-600 hover:underline dark:text-primary-400">
            ← Back
        </x-link>
    </div>

    {{-- Department Card --}}
    <section class="bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6">
        <div class="grid md:grid-cols-2 gap-6">
            {{-- Info --}}
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ $department->name }}
                </h1>

                <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                    <li><strong>Head Name:</strong> {{ $department->head_name }}</li>
                    <li><strong>Head Email:</strong> {{ $department->head_email }}</li>
                    <li><strong>Head Phone:</strong> {{ $department->head_phone }}</li>
                    <li><strong>Fest Type:</strong> {{ $department->fest_type_name }}</li>
                    <li><strong>Status:</strong>
                        @if ($department->is_active)
                            <span
                                class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-lg dark:bg-green-900 dark:text-green-300">
                                Active
                            </span>
                        @else
                            <span
                                class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-lg dark:bg-red-900 dark:text-red-300">
                                Inactive
                            </span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </section>


    {{-- Department Events --}}
        <section class="mt-5">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Events by this Department</h2>

            <div class="overflow-x-auto">
                <x-data-table>
                    <x-slot:column>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Event Name</th>
                        <th class="px-6 py-3">Start Date</th>
                        <th class="px-6 py-3">End Date</th>
                        <th class="px-6 py-3">Location</th>
                    </x-slot:column>
                    @forelse ($department->events as $index => $event)
                        <tr>
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('events.show', $event) }}"
                                    class="text-primary-600 hover:underline dark:text-primary-400">
                                    {{ $event->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">{{ $event->start_date }}</td>
                            <td class="px-6 py-4">{{ $event->end_date }}</td>
                            <td class="px-6 py-4">{{ $event->location }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-start text-gray-500">
                                No events found for this department
                            </td>
                        </tr>
                    @endforelse
                </x-data-table>
            </div>
        </section>
</x-layout>
