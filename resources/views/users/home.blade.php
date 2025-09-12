<x-layout>

	<x-slot:title>Home | CEMS</x-slot:title>
	<x-slot:metaDescription>Overview events</x-slot:metaDescription>

	<x-slot:header>Home</x-slot:header>

	<div class="grid grid-cols-2  sm:grid-cols-3 gap-4">
		
        @can('isAdmin')
		<div
			class="flex justify-between p-4 sm:p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
			<div>
				<p class="text-gray-600 dark:text-gray-400">Users</p>
				<h2 class="text-3xl font-bold text-green-600"> {{ $user_count??0}}</h2>
			</div>
		</div>
        @endcan

		<div
			class="flex justify-between p-4 sm:p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
			<div>
				<p class="text-gray-600 dark:text-gray-400">Events</p>
				<h2 class="text-3xl font-bold text-red-600"> {{ $event_count }}</h2>
			</div>

            @can('create', App\Models\Event::class)
			<div>
				<a href="{{ route('events.create') }}" title="Add Event"
					class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-md text-sm p-px text-center inline-flex items-center  dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
					<svg class="w-6 h-6 text-white dark:text-gray-200" aria-hidden="true"
						xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
						<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M5 12h14m-7 7V5" />
					</svg>

					<span class="sr-only">Icon description</span>
				</a>
			</div>
            @endcan
		</div>

		
		<div class="flex justify-between p-4 sm:p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
			<div>
				<p class="text-gray-600 dark:text-gray-400">Departments</p>
				<h2 class="text-3xl font-bold text-orange-600">{{ $department_count }}</h2>
			</div>

            @can('create', App\Models\Department::class)
			<div>
				<a href="{{ route('departments.create') }}" title="Add Department"
					class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-md text-sm p-px text-center inline-flex items-center  dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
					<svg class="w-6 h-6 text-white dark:text-gray-200" aria-hidden="true"
						xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
						<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M5 12h14m-7 7V5" />
					</svg>

					<span class="sr-only">Icon description</span>
				</a>
			</div>
            @endcan
		</div>
		
	</div>

</x-layout>