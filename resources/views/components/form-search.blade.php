@props(['action' => '/', 'name' => 'search', 'placeholder' => 'Search'])
<div class="w-full">
  <form action="{{ $action }}" method="get">
    <div class="flex">
      <label for="{{$name}}" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
      <div class="relative w-full">
        <input type="search" id="{{$name}}"
          class="block p-2 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-primary-500"
          placeholder="{{$placeholder}}" name="{{$name}}" required />

        <button type="submit"
          class="absolute top-0 end-0 p-2.5 h-full text-sm font-medium text-white bg-primary-700 rounded-e-lg border border-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"><svg
            class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
          </svg></button>
      </div>
    </div>
  </form>
</div>