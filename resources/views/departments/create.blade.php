<x-layout>
    <x-slot:title>Add department | CEMS</x-slot:title>
    <x-slot:metaDescription>Record a new department</x-slot:metaDescription>

    <x-slot:header>
        Add department
    </x-slot:header>

    <div
        class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
        <form class="w-full max-w-lg flex flex-col gap-5" action="{{ route('departments.store') }}" method="POST">
            @csrf

            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name of
                    department</label>

                <input type="text" name="name" id="name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                    placeholder="e.g. Computer Science" />
                <x-form-error name="name" />
            </div>
            <div>
                <label for="head_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name of Head</label>

                <input type="text" name="head_name" id="head_name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                    placeholder="e.g. John Doe" />
                <x-form-error name="head_name" />
            </div>
            <div>
                <label for="head_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email address of Head</label>

                <input type="email" name="head_email" id="head_email"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                    placeholder="e.g. name@example" />
                <x-form-error name="head_email" />
            </div>
            <div>
                <label for="head_phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone number of Head</label>

                <input type="text" name="head_phone" id="head_phone"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                    placeholder="e.g. 1234567890" />
                <x-form-error name="head_phone" />
            </div>

            <div>
                <label for="fest_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Types of Fests. Held in This Department</label>
                <select id="fest_type" name="fest_type"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Choose a type</option>
                    <option value="dept_fest">Departmental Fest</option>
                    <option value="association">College Fest</option>
                    <option value="clg_fest">Association</option>
                </select>
                <x-form-error name="fest_type" />
            </div>


            <button type="submit"
                class="flex self-start text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Save</button>
        </form>
    </div>
</x-layout>