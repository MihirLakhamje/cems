<x-layout>
    <x-slot:title>{{ $department->name }} | CEMS</x-slot:title>
    <x-slot:metaDescription>View department details</x-slot:metaDescription>
    <x-slot:header>Department Details</x-slot:header>

    {{-- Back button --}}
    <div class="flex items-center gap-4 mb-6">
        <x-link :typeoflink="'link'" href="{{ route('departments.index') }}"
            class="text-blue-600 hover:underline dark:text-blue-400">
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
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-lg dark:bg-green-900 dark:text-green-300">
                                Active
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-lg dark:bg-red-900 dark:text-red-300">
                                Inactive
                            </span>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </section>

   
    {{-- Department Events --}}
    <section class="mt-10 bg-white dark:bg-gray-800 shadow-md rounded-2xl p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Events by this Department</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600 dark:text-gray-400">
                <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Event Name</th>
                        <th class="px-6 py-3">Start Date</th>
                        <th class="px-6 py-3">End Date</th>
                        <th class="px-6 py-3">Location</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($department->events as $index => $event)
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('events.show', $event) }}"
                                   class="text-blue-600 hover:underline dark:text-blue-400">
                                    {{ $event->name }}
                                </a>
                            </td>
                            <td class="px-6 py-4">{{ $event->start_date }}</td>
                            <td class="px-6 py-4">{{ $event->end_date }}</td>
                            <td class="px-6 py-4">{{ $event->location }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No events found for this department
                            </td>
                            <td class="px-6 py-4"> </td>
                            <td class="px-6 py-4"> </td>
                            <td class="px-6 py-4"> </td>
                            <td class="px-6 py-4"> </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-layout>
