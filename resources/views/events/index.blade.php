<x-layout>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <x-slot:title>Events | CEMS</x-slot:title>
    <x-slot:metaDescription>View and manage all events</x-slot:metaDescription>

    <x-slot:header>Events</x-slot:header>

    <section class="flex flex-col gap-2">

        @can('create', App\Models\Event::class)
        <div class="my-2">
            <a href="{{ route('events.create') }}"
                class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none">
                Add Event
            </a>
        </div>
        @endcan

        <div class="my-2">
            <x-form-search :action="'/events'" :name="'search'" :placeholder="'Search event'"/>
        </div>

        <x-data-table>
            <x-slot:column>
                <th class="px-6 py-3">Sr. No.</th>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Start Date</th>
                <th class="px-6 py-3">End Date of Registration</th>
                <th class="px-6 py-3">Department Associated</th>
                <th class="px-6 py-3">Fees</th>
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
                <td class="px-6 py-4">{{ $event->start_date->format('d/m/Y') }}</td>
                <td class="px-6 py-4">{{ $event->end_date->format('d/m/Y') }}</td>
                <td class="px-6 py-4">{{ $event->department->name }}</td>
                <td class="px-6 py-4">Rs.{{ $event->fees }}</td>
                <td class="px-6 py-4">
                    <div class="flex gap-5 items-center">
                        <x-link :typeoflink="'link'" href="{{ route('events.show', $event->id) }}"
                            class="text-blue-600 dark:text-blue-500 me-0">
                            View
                        </x-link>
                        <div x-data="{
                                eventEditModal: @json($errors->any()), 
                                selected: {
                                    id: '{{ old('id') ?? '' }}',
                                    deptId: '{{ old('department_id') ?? '' }}',
                                    name: '{{ old('name') ?? '' }}',
                                    description: '{{ old('description') ?? '' }}',
                                    startDate: '{{ old('start_date') ?? '' }}',
                                    endDate: '{{ old('end_date') ?? '' }}',
                                    location: '{{ old('location') ?? '' }}',
                                    image: '{{ old('image') ?? '' }}',
                                    fees: '{{ old('fees') ?? '' }}',
                                    capacity: '{{ old('capacity') ?? '' }}'
                                }
                            }">

                            @can('update', $event)
                            <button type="button"
                                class="text-green-600 dark:text-green-500 cursor-pointer hover:underline me-0" @@click="eventEditModal = true; 
                                selected = { 
                                    id: '{{ $event->id }}', 
                                    name: '{{ $event->name }}', 
                                    description: '{{ $event->description }}',
                                    startDate: '{{ $event->start_date->format('d/m/Y') }}',
                                    endDate: '{{ $event->end_date->format('d/m/Y') }}',
                                    location: '{{ $event->location }}',
                                    image: '{{ $event->image }}',
                                    fees: '{{ $event->fees }}',
                                    capacity: '{{ $event->capacity }}'}">
                                Edit
                            </button>
                            <!-- Modal -->
                            <x-modal id="eventEditModal" title="Edit Event" :footer="true">
                                <form :action="`/events/${selected.id}/update`" method="POST"
                                    class="flex flex-col gap-5 w-3xs sm:w-md">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <label for="name"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name of
                                            event</label>

                                        <input type="text" name="name" id="name" x-model="selected.name"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            placeholder="e.g. Iterationz" />
                                        <x-form-error name="name" />
                                    </div>

                                    <div>
                                        <label for="description"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description
                                            of
                                            event</label>

                                        <input type="text" name="description" id="description"
                                            x-model="selected.description"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            placeholder="e.g. Iterationz" />
                                        <x-form-error name="description" />
                                    </div>
                                    <div>
                                        <label for="datepicker-range-start"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Duration
                                            of registration</label>
                                        <div id="date-range-picker" date-rangepicker class="flex items-center">
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                    </svg>
                                                </div>
                                                <input datepicker datepicker-format="dd/mm/yyyy"
                                                    id="datepicker-range-start" name="start_date" type="text"
                                                    x-model="selected.startDate"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="date start">
                                                <x-form-error name="start_date" />
                                            </div>
                                            <span class="mx-4 text-gray-500">to</span>
                                            <div class="relative">
                                                <div
                                                    class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                                    </svg>
                                                </div>
                                                <input datepicker datepicker-format="dd/mm/yyyy"
                                                    id="datepicker-range-end" name="end_date" type="text"
                                                    x-model="selected.endDate"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="Select date end">
                                                <x-form-error name="end_date" />
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="location"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location</label>

                                        <input type="text" name="location" id="location" x-model="selected.location"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            placeholder="e.g. sies college" />
                                        <x-form-error name="location" />
                                    </div>
                                    <div>
                                        <label for="fees"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fees</label>

                                        <input type="text" name="fees" id="fees" x-model="selected.fees"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            placeholder="e.g. Rs.500" />
                                        <x-form-error name="fees" />
                                    </div>

                                    <div>
                                        <label for="capacity"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Capacity</label>

                                        <input type="text" name="capacity" id="capacity" x-model="selected.capacity"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                            placeholder="e.g. 500" />
                                        <x-form-error name="capacity" />
                                    </div>

                                    <div class="flex justify-end space-x-2 mt-4">
                                        <button type="button" @@click="eventEditModal = false"
                                            onclick="window.location='{{ route('events.index') }}'"
                                            class="px-3 py-2 text-sm font-medium text-center text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Cancel</button>
                                        <button type="submit"
                                            class="px-3 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Save</button>
                                    </div>
                                </form>
                            </x-modal>
                            @endcan
                        </div>

                        @can('delete', $event)
                        <form action="{{ route('events.destroy', $event->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <x-link :typeoflink="'button'"
                                onclick="return confirm('Are you sure? This action cannot be undone.')"
                                class="text-red-600 dark:text-red-500">
                                Delete
                            </x-link>
                        </form>
                        @endcan
                    </div>
                </td>
            </tr>
            @endforeach

            @if($events->isEmpty())
            <tr class="bg-white dark:bg-gray-800 text-nowrap">
                <td class="px-6 py-4 w-0">No event records found</td>
                <td class="px-6 py-4"> </td>
                <td class="px-6 py-4"> </td>
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