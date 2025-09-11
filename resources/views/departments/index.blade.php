<x-layout>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <x-slot:title>Departments | CEMS</x-slot:title>
    <x-slot:metaDescription>View and manage all departments</x-slot:metaDescription>

    <x-slot:header>Departments</x-slot:header>

    <section class="flex flex-col gap-2" x-data="{
        departmentEditModal: @json($errors->any()),
        selected: {
            id: '{{ old('id') ?? '' }}',
            name: '{{ old('name') ?? '' }}',
            headName: '{{ old('head_name') ?? '' }}',
            headEmail: '{{ old('head_email') ?? '' }}',
            headPhone: '{{ old('head_phone') ?? '' }}',
            festType: '{{ old('fest_type') ?? '' }}',
            isActive: '{{ old('is_active') ?? '' }}',
            noOfEvents: '{{ old('noOfEvents') ?? '' }}'
        }
    }">

        <x-data-table>
            <x-slot:button>
                @can('create', App\Models\Department::class)
                    <a href="{{ route('departments.create') }}"
                        class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg sm:text-sm text-xs sm:px-4 sm:py-2 px-2 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none">
                        Add department
                    </a>
                @endcan

                <!-- Filter Dropdown -->
                <div>
                    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" title="Filter options"
                        data-dropdown-placement="bottom-start"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm p-2 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
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
                        <form method="GET" action="{{ route('departments.index') }}" class="space-y-3">
                            <div>
                                <label for="view"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">View</label>
                                <select id="view" name="owned"
                                    class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">All</option>
                                    <option value="1" @selected(request('owned') == '1')>Owned</option>
                                </select>
                            </div>
                            <div>
                                <label for="status"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <select id="status" name="is_active"
                                    class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">All</option>
                                    <option value="1" @selected(request('is_active') == '1')>Active</option>
                                    <option value="0" @selected(request('is_active') == '0')>Inactive</option>
                                </select>
                            </div>
                            <div>
                                @php
                                    $types = request('type', []); // array of selected types
                                @endphp

                                <div class="flex items-center mb-2">
                                    <input id="departmental" type="checkbox" name="type[]" value="dept_fest"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        @checked(in_array('dept_fest', $types))>
                                    <label for="departmental"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Departmental
                                        Fest</label>
                                </div>

                                <div class="flex items-center mb-2">
                                    <input id="college" type="checkbox" name="type[]" value="clg_fest"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        @checked(in_array('clg_fest', $types))>
                                    <label for="college"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">College
                                        Fest</label>
                                </div>

                                <div class="flex items-center">
                                    <input id="association" type="checkbox" name="type[]" value="association"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        @checked(in_array('association', $types))>
                                    <label for="association"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Association</label>
                                </div>
                            </div>

                            <div class="flex justify-end gap-2">
                                {{-- Clear Filters --}}
                                <a href="{{ route('departments.index') }}"
                                    class="text-sm px-3 py-2 text-white bg-gray-600 rounded-lg hover:bg-gray-700">Clear
                                    All</a>

                                {{-- Apply Filters --}}
                                <button type="submit"
                                    class="text-sm px-3 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                    Apply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Active filter badges -->
                <div class="flex flex-wrap gap-2">
                    @foreach (['owned' => 'View', 'is_active' => 'Status', 'type' => 'Fest Type', ] as $key => $label)
                        @if (request($key))
                            <a href="{{ request()->fullUrlWithQuery([$key => null]) }}"
                                class="flex items-center bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <span>
                                    {{ $label }}:
                                    @if ($key === 'owned' && request($key) == '1')
                                        Owned
                                    @elseif ($key === 'is_active' && request($key) == '1')
                                        Active
                                    @elseif ($key === 'is_active' && request($key) == '0')
                                        Inactive
                                    @elseif (in_array($key, ['type']) && is_array(request($key)))
                                        {{ implode(', ', array_map(fn($type) => match ($type) {
                                            'dept_fest' => 'Departmental',
                                            'clg_fest' => 'College',
                                            'association' => 'Association',
                                            default => $type,
                                        }, request($key))) }}
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
                <x-form-search :action="'/departments'" :name="'search'" :placeholder="'Search department'" />
            </x-slot:search>

            <x-slot:column>
                <th class="px-6 py-3">Sr. No.</th>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Fest type</th>
                <th class="px-6 py-3">No. of Events</th>
                <th class="px-6 py-3">Fest Status</th>
                <th class="px-6 py-3">Action</th>
            </x-slot:column>

            @foreach ($departments as $department)
                <tr>
                    <td class="px-6 py-4">
                        {{-- (currentPage() - 1) * perPage + index --}}
                        {{-- (1-1) * 10 + 1 = 1 --}}
                        {{-- (2-1) * 10 + 1 = 11 --}}
                        {{-- (3-1) * 10 + 1 = 21 --}}
                        {{ ($departments->currentPage() - 1) * $departments->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-6 py-4">{{ $department->name }}</td>
                    <td class="px-6 py-4">{{ $department->fest_type_name }}</td>
                    <td class="px-6 py-4" title="To Be Determined">
                        {{ $department->events->count() > 0 ? $department->events->count() : 'TBD' }}</td>
                    <td class="px-6 py-4">
                        <span
                            class="{{ $department->is_active ? 'text-green-600' : 'text-red-600' }}">{{ $department->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-5 items-center">
                            <x-link :typeoflink="'link'" href="{{ route('departments.show', $department->id) }}"
                                class="text-blue-600 dark:text-blue-500 me-0">
                                View
                            </x-link>

                            @can('update', $department)
                                {{-- Prevent admin from editing their own department --}}
                                <button type="button"
                                    class="text-green-600 dark:text-green-500 cursor-pointer hover:underline me-0"
                                    @@click="departmentEditModal = true; 
                            selected = { 
                                id: '{{ $department->id }}', 
                                name: '{{ $department->name }}', 
                                headName: '{{ $department->head_name }}', 
                                headEmail: '{{ $department->head_email }}', 
                                headPhone: '{{ $department->head_phone }}', 
                                festType: '{{ $department->fest_type }}', 
                                isActive: '{{ $department->is_active }}', 
                                noOfEvents: '{{ $department->events->count() }}'
                            }">
                                    Edit
                                </button>
                            @endcan

                            @can('delete', $department)
                                {{-- Prevent admin from deleting their own department --}}
                                <form action="{{ route('departments.destroy', $department->id) }}" method="post">
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

            @if ($departments->isEmpty())
                <tr class="bg-white dark:bg-gray-800 text-nowrap">
                    <td class="px-6 py-4 w-0">No department records found</td>
                    <td class="px-6 py-4"> </td>
                    <td class="px-6 py-4"> </td>
                    <td class="px-6 py-4"> </td>
                    <td class="px-6 py-4"> </td>
                    <td class="px-6 py-4"> </td>
                </tr>
            @endif
        </x-data-table>

        <!-- Modal -->
        <x-modal id="departmentEditModal" title="Edit Department" :footer="true">
            <form :action="`/departments/${selected.id}/update`" method="POST"
                class="flex flex-col gap-5 w-3xs sm:w-md">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name of
                        department</label>

                    <input type="text" name="name" id="name" x-model="selected.name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="e.g. Computer Science" />
                    <x-form-error name="name" />
                </div>
                <div>
                    <label for="head_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name
                        of
                        Head</label>

                    <input type="text" name="head_name" id="head_name" x-model="selected.headName"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="e.g. John Doe" />
                    <x-form-error name="head_name" />
                </div>
                <div>
                    <label for="head_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                        address of Head</label>

                    <input type="email" name="head_email" id="head_email" x-model="selected.headEmail"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="e.g. name@example" />
                    <x-form-error name="head_email" />
                </div>
                <div>
                    <label for="head_phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone
                        number of Head</label>

                    <input type="text" name="head_phone" id="head_phone" x-model="selected.headPhone"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                        placeholder="e.g. 1234567890" />
                    <x-form-error name="head_phone" />
                </div>

                <div>
                    <label for="fest_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Types
                        of
                        Fests.</label>
                    <select id="fest_type" name="fest_type" x-model="selected.festType"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="dept_fest">Departmental Fest</option>
                        <option value="clg_fest">College Fest</option>
                        <option value="association">Association</option>
                    </select>
                    <x-form-error name="fest_type" />
                </div>

                <div>
                    <label for="is_active"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                    <select id="is_active" name="is_active" x-model="selected.isActive"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="0">Completed</option>
                        <option value="1">Ongoing</option>
                    </select>
                    <x-form-error name="is_active" />
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" @@click="departmentEditModal = false"
                        onclick="window.location='{{ route('departments.index') }}'"
                        class="px-3 py-2 text-sm font-medium text-center text-white bg-gray-700 rounded-lg hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">Cancel</button>
                    <button type="submit"
                        class="px-3 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Save</button>
                </div>
            </form>
        </x-modal>

        <div class="mt-2">
            {{ $departments->links() }}
        </div>
    </section>

</x-layout>
