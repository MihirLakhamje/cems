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
        <x-data-table>
            <x-slot:button>
                @can('create', App\Models\Event::class)
                    <a href="{{ route('events.create') }}"
                        class="text-white bg-primary-700 hover:bg-primary-800 font-medium rounded-lg sm:text-sm text-xs sm:px-4 sm:py-2 px-2 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none">
                        Add Event
                    </a>
                @endcan

                <!-- Filter Dropdown -->
                <div>

                    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                        data-dropdown-placement="bottom-start"
                        class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm p-2 text-center inline-flex items-center me-2 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                        type="button"><svg class="w-4 h-4 sm:w-5 sm:h-5 text-white-800 dark:text-gray-200"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M5.05 3C3.291 3 2.352 5.024 3.51 6.317l5.422 6.059v4.874c0 .472.227.917.613 1.2l3.069 2.25c1.01.742 2.454.036 2.454-1.2v-7.124l5.422-6.059C21.647 5.024 20.708 3 18.95 3H5.05Z" />
                        </svg>

                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdown"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-xs dark:bg-gray-700 p-5">
                        <form method="GET" action="{{ route('events.index') }}" class="space-y-3">

                            <div>
                                <label class="text-sm">Start Date</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="text-sm">End Date</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="w-full rounded border-gray-300 dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label for="view"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">View</label>
                                <select id="view" name="owned"
                                    class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="">All</option>
                                    <option value="1" @selected(request('owned') == '1')>Owned</option>
                                </select>
                            </div>
                            <div>
                                <label for="fees"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fees</label>
                                <div class="flex gap-2">
                                    <input type="number" id="small-input" type="number" name="fees_min"
                                        value="{{ request('fees_min') }}" placeholder="Min ₹" id="fees_min"
                                        class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <input type="number" name="fees_max" value="{{ request('fees_max') }}"
                                        placeholder="Max ₹"
                                        class="block w-full p-2 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-xs focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">

                                </div>
                            </div>
                            <div class="flex justify-end gap-2">
                                {{-- Clear Filters --}}
                                <a href="{{ route('events.index') }}"
                                    class="text-sm px-3 py-2 text-white bg-gray-600 rounded-lg hover:bg-gray-700">Clear
                                    All</a>

                                {{-- Apply Filters --}}
                                <button type="submit"
                                    class="text-sm px-3 py-2 text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                                    Apply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Active filter badges -->
                <div class="flex flex-wrap gap-2">
                    @foreach (['start_date' => 'Start', 'end_date' => 'End', 'owned' => 'View', 'fees_min' => 'Min Fees', 'fees_max' => 'Max Fees'] as $key => $label)
                        @if (request($key))
                            <a href="{{ request()->fullUrlWithQuery([$key => null]) }}"
                                class="flex items-center bg-primary-100 text-primary-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <span>
                                    {{ $label }}:
                                    @if ($key === 'owned' && request($key) == '1')
                                        Owned
                                    @elseif (in_array($key, ['start_date', 'end_date']))
                                        {{ \Carbon\Carbon::parse(request($key))->format('d/m/Y') }}
                                    @elseif (in_array($key, ['fees_min', 'fees_max']))
                                        ₹{{ request($key) }}
                                    @else
                                        {{ request($key) }}
                                    @endif

                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1 cursor-pointer"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    @endforeach
                </div>

            </x-slot:button>

            <x-slot:search>
                <x-form-search :action="'/events'" :name="'search'" :placeholder="'Search event'" />
            </x-slot:search>

            <x-slot:column>
                <th class="px-6 py-3">Sr. No.</th>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Registration Duration</th>
                <th class="px-6 py-3">Event Duration</th>
                <th class="px-6 py-3">Department</th>
                <th class="px-6 py-3">Fees</th>
                <th class="px-6 py-3">Action</th>
            </x-slot:column>

            @foreach ($events as $event)
                <tr class="text-nowrap">
                    <td class="px-6 py-4">
                        {{-- (currentPage() - 1) * perPage + index --}}
                        {{-- (1-1) * 10 + 1 = 1 --}}
                        {{-- (2-1) * 10 + 1 = 11 --}}
                        {{-- (3-1) * 10 + 1 = 21 --}}
                        {{ ($events->currentPage() - 1) * $events->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-6 py-4">{{ $event->name }}</td>
                    <td class="px-6 py-4">
                        {{ $event->start_date->format('d/m/y') }} - {{ $event->end_date->format('d/m/y') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $event->event_start_date->format('d/m/y') }} - {{ $event->event_end_date->format('d/m/y') }}
                    </td>
                    <td class="px-6 py-4">{{ $event->department->name }}</td>
                    <td class="px-6 py-4">₹{{ $event->fees }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-4 items-center">
                            <x-link :typeoflink="'link'" href="{{ route('events.show', $event->id) }}"
                                class="text-blue-600 dark:text-blue-500 me-0">
                                View
                            </x-link>
                            @if (auth()->user()->role === 'user' && $event->is_registered)
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">
                                    Registered
                                </span>
                            @endif
                            @can('update', $event)
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

                                    <button type="button"
                                        class="text-green-600 dark:text-green-500 cursor-pointer hover:underline me-0"
                                        @@click="eventEditModal = true; 
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
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name
                                                    of
                                                    event</label>

                                                <input type="text" name="name" id="name" x-model="selected.name"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
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
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
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
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
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
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                            placeholder="Select date end">
                                                        <x-form-error name="end_date" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label for="location"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location</label>

                                                <input type="text" name="location" id="location"
                                                    x-model="selected.location"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                    placeholder="e.g. sies college" />
                                                <x-form-error name="location" />
                                            </div>
                                            <div>
                                                <label for="fees"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fees</label>

                                                <input type="text" name="fees" id="fees"
                                                    x-model="selected.fees"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                                    placeholder="e.g. Rs.500" />
                                                <x-form-error name="fees" />
                                            </div>

                                            <div>
                                                <label for="capacity"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Capacity</label>

                                                <input type="text" name="capacity" id="capacity"
                                                    x-model="selected.capacity"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
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
                                </div>
                            @endcan

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

            @if ($events->isEmpty())
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



        <div class="mt-2">
            {{ $events->links() }}
        </div>
    </section>

</x-layout>
