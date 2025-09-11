<x-layout>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <x-slot:title>Users | CEMS</x-slot:title>
    <x-slot:metaDescription>View and manage all your users with the assigned roles</x-slot:metaDescription>

    <x-slot:header>Users</x-slot:header>

    <section class="flex flex-col gap-2" x-data="{
        userModal: false,
        selected: {
            id: '{{ old('id') ?? '' }}',
            name: '{{ old('name') ?? '' }}',
            email: '{{ old('email') ?? '' }}',
            role: '{{ old('role') ?? '' }}',
            department_id: '{{ old('department_id') ?? '' }}',
        }
    }">


        <x-data-table>
            <x-slot:button>
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
                        <form method="GET" action="{{ route('users.index') }}" class="space-y-3">
                            <div>
                                <label for="department_assigned"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department
                                    Assigned?</label>
                                <select id="department_assigned" name="department_assigned"
                                    class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">All</option>
                                    <option value="yes" @selected(request('department_assigned') == 'yes')>Yes</option>
                                    <option value="no" @selected(request('department_assigned') == 'no')>No</option>
                                </select>
                            </div>
                            <div>
                                @php
                                    $roles = request('role', []); // array of selected types
                                @endphp

                                <div class="flex items-center mb-2">
                                    <input id="admin" type="checkbox" name="role[]" value="admin"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        @checked(in_array('admin', $roles))>
                                    <label for="admin"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Admin</label>
                                </div>

                                <div class="flex items-center mb-2">
                                    <input id="organizer" type="checkbox" name="role[]" value="organizer"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        @checked(in_array('organizer', $roles))>
                                    <label for="organizer"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Organizer</label>
                                </div>

                                <div class="flex items-center">
                                    <input id="user" type="checkbox" name="role[]" value="user"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        @checked(in_array('user', $roles))>
                                    <label for="user"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">User</label>
                                </div>
                            </div>

                            <div class="flex justify-end gap-2">
                                {{-- Clear Filters --}}
                                <a href="{{ route('users.index') }}"
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
                    @foreach (['department_assigned' => 'Department Assigned', 'role' => 'Role'] as $key => $label)
                        @if (request($key))
                            <a href="{{ request()->fullUrlWithQuery([$key => null]) }}"
                                class="flex items-center bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <span>
                                    {{ $label }}:
                                    @if ($key === 'department_assigned' && request($key) == 'yes')
                                        Yes
                                    @elseif ($key === 'department_assigned' && request($key) == 'no')
                                        No
                                    @elseif (in_array($key, ['role']) && is_array(request($key)))
                                        {{ implode(
                                            ', ',
                                            array_map(
                                                fn($role) => match ($role) {
                                                    'admin' => 'Admin',
                                                    'organizer' => 'Organizer',
                                                    'user' => 'User',
                                                    default => $role,
                                                },
                                                request($key),
                                            ),
                                        ) }}
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
                <x-form-search :action="'/users'" :name="'search'" :placeholder="'Search user'" />
            </x-slot:search>
            <x-slot:column>
                <th class="px-6 py-3">Sr. No.</th>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3">Department</th>
                <th class="px-6 py-3">Role</th>
                <th class="px-6 py-3">Action</th>
            </x-slot:column>

            @foreach ($users as $user)
                <tr>
                    <td class="px-6 py-4">
                        {{-- (currentPage() - 1) * perPage + index --}}
                        {{-- (1-1) * 10 + 1 = 1 --}}
                        {{-- (2-1) * 10 + 1 = 11 --}}
                        {{-- (3-1) * 10 + 1 = 21 --}}
                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-6 py-4">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4" title="To Be Determined">{{ $user->department->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $user->role }}</td>
                    <td class="px-6 py-4">
                        <button type="button" class="text-green-600 dark:text-green-500 hover:underline cursor-pointer"
                            @@click="userModal = true; selected = { 
                        id: '{{ $user->id }}', 
                        name: '{{ $user->name }}', 
                        email: '{{ $user->email }}', 
                        role: '{{ $user->role }}',
                        department_id: '{{ $user->department_id }}' 
                    }">
                            Assign Role / Dept
                        </button>
                    </td>
                </tr>
            @endforeach

            @if ($users->isEmpty())
                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 text-nowrap">
                    <td class="px-6 py-4 w-0">No user records found</td>
                    <td class="px-6 py-4"> </td>
                    <td class="px-6 py-4"> </td>
                    <td class="px-6 py-4"> </td>
                    <td class="px-6 py-4"> </td>
                    <td class="px-6 py-4"> </td>
                </tr>
            @endif
        </x-data-table>

        <!-- Modal -->
        <x-modal id="userModal" title="Assign Role" :footer="true">
            <form :action="`/users/${selected.id}/assign-role`" method="POST" class="w-full flex flex-col gap-4">
                @csrf
                @method('PATCH')
                <div>
                    <label for="role"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Role</label>
                    <select id="role" name="role" x-model="selected.role"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="organizer">Organizer</option>
                        <option value="user">User</option>
                    </select>
                    <x-form-error name="role" />
                </div>
                <div>
                    <label for="department_id"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department</label>
                    <select id="department_id" name="department_id" x-model="selected.department_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="dept_fest">Choose a department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                x-bind:selected="selected.department_id == '{{ $department->id }}'">
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-form-error name="department_id" />
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" class="cursor-pointer px-4 py-2 bg-gray-300 rounded"
                        onclick="window.location='{{ route('users.index') }}'"
                        @@click="userModal = false">
                        Cancel
                    </button>
                    <button type="submit" class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded">
                        Save
                    </button>
                </div>
            </form>
        </x-modal>

        <div class="mt-2">
            {{ $users->links() }}
        </div>
    </section>

</x-layout>
