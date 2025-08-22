<div x-show="{{ $id }}" :inert="!{{ $id }}"  x-cloak class="fixed inset-0 flex items-center justify-center z-50 p-10"
    @keydown.escape.window="{{ $id }} = false" @@click.stop>
    <!-- Background overlay -->
    <div class="absolute inset-0 bg-black opacity-50" @@click.stop></div>

    <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg  p-6 m-10">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">{{ $title }}</h2>
        {{ $slot }}
    </div>
</div>