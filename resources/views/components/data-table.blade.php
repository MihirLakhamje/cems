<div class="overflow-x-auto shadow-md rounded-lg">
    <div @class([
        'flex flex-column sm:flex-row flex-wrap items-center justify-between p-2 bg-gray-100 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700',
        'gap-2' => !empty(trim($button ?? '')) && !empty(trim($search ?? ''))
    ])>
        <div class="flex flex-wrap gap-2">
            {{ $button ?? '' }}
        </div>
        <div class="flex-grow max-w-sm">
            {{ $search ?? '' }}
        </div>
    </div>
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-900 dark:text-gray-400">
            <tr>
                {{ $column }}
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 text-nowrap">
            {{ $slot }}
        </tbody>
    </table>
</div>
