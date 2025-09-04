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

        <div class = "flex flex-wrap justify-between items-center">
            
            @can('create', App\Models\Department::class)
                <div class="my-2">
                    <a href="{{ route('departments.create') }}"
                        class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none">
                        Add department
                    </a>
                </div>
            @endcan

            <div class="my-2">
                <x-form-search :action="'/departments'" :name="'search'" :placeholder="'Search department'" />
            </div>
        </div>

        <x-data-table>
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
                    <td class="px-6 py-4">{{ $department->is_active ? 'Ongoing' : 'Completed' }}</td>
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
                    <label for="head_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name of
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
                    <label for="fest_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Types of
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

        <div class="mt-4">
            {{ $departments->links() }}
        </div>
    </section>

</x-layout>
