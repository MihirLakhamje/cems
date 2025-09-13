<x-layout>
    <x-slot:title>Add event | CEMS</x-slot:title>
    <x-slot:metaDescription>Record a new event</x-slot:metaDescription>

    <x-slot:header>
        Add event
    </x-slot:header>

    {{-- Toast Notification --}}
    @if (session('toast'))
        <x-toast :type="session('toast.type')" :message="session('toast.message')" />
    @endif

    <div
        class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
        <form class="w-full max-w-lg flex flex-col gap-5" action="{{ route('events.store') }}" method="POST">
            @csrf

            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name of
                    event</label>

                <input type="text" name="name" id="name"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                    placeholder="e.g. Iterationz" />
                <x-form-error name="name" />
            </div>
            <div>
                <label for="description"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description of
                    event</label>

                <input type="text" name="description" id="description"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                    placeholder="e.g. Iterationz" />
                <x-form-error name="description" />
            </div>
            <div>
                <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Registration Duration</div>
                <div datepicker date-rangepicker datepicker-format="dd/mm/yyyy"
                    class="flex items-center justify-between gap-2">
                    <div class="w-full">
                        <input id="datepicker-range-start" name="start_date" type="text"
                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Start date">
                        <x-form-error name="start_date" />
                    </div>
                    <span class="mx-2 text-gray-500">to</span>
                    <div class="w-full">
                        <input id="datepicker-range-end" name="end_date" type="text"
                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="End date">
                        <x-form-error name="end_date" />
                    </div>
                </div>
            </div>
            <div>
                <div class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Event Duration</div>
                <div datepicker date-rangepicker datepicker-format="dd/mm/yyyy"
                    class="flex items-center justify-between gap-2">
                    <div class="w-full">
                        <input id="event-range-start" name="event_start_date" type="text"
                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Start date">
                        <x-form-error name="event_start_date" />
                    </div>
                    <span class="mx-2 text-gray-500">to</span>
                    <div class="w-full">
                        <input id="event-range-end" name="event_end_date" type="text"
                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="End date">
                        <x-form-error name="event_end_date" />
                    </div>
                </div>
            </div>
            <div>
                <label for="location"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location</label>

                <input type="text" name="location" id="location"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                    placeholder="e.g. sies college" />
                <x-form-error name="location" />
            </div>
            <div>
                <label for="fees" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fees</label>

                <input type="text" name="fees" id="fees"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                    placeholder="e.g. Rs.500" />
                <x-form-error name="fees" />
            </div>

            <div>
                <label for="capacity"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Capacity</label>

                <input type="text" name="capacity" id="capacity"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                    placeholder="e.g. 50" />
                <x-form-error name="capacity" />
            </div>


            <button type="submit"
                class="flex self-start text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">Save</button>
        </form>
    </div>
</x-layout>
