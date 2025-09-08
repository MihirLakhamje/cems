<x-layout>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <x-slot:title>Users | CEMS</x-slot:title>
    <x-slot:metaDescription>View and manage all your users with the assigned roles</x-slot:metaDescription>

    <x-slot:header>Users</x-slot:header>

    <section class="flex flex-col gap-2" x-data="{userModal: false, selected: {
            id: '{{ old('id') ?? '' }}',
            name: '{{ old('name') ?? '' }}',
            email: '{{ old('email') ?? '' }}',
            role: '{{ old('role') ?? '' }}',
            department_id: '{{ old('department_id') ?? '' }}',
        } }">

        <div class="my-2 max-w-sm">
            <x-form-search :action="'/users'" :name="'search'" :placeholder="'Search user'"/>
        </div>

        <x-data-table>
            <x-slot:column>
                <th class="px-6 py-3">Sr. No.</th>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3">Department associated</th>
                <th class="px-6 py-3">Role</th>
                <th class="px-6 py-3">Action</th>
            </x-slot:column>

            @foreach($users as $user)
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
                        Assign Role
                    </button>
                </td>
            </tr>
            @endforeach

            @if($users->isEmpty())
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
                        @foreach($departments as $department)
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
                    onclick="window.location='{{ route('users.index') }}'" @@click="userModal = false">
                        Cancel
                    </button>
                    <button type="submit" class="cursor-pointer px-4 py-2 bg-green-600 text-white rounded">
                        Save
                    </button>
                </div>
            </form>
        </x-modal>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </section>

</x-layout>