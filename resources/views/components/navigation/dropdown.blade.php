@props(['label', 'id'])
<div class="hidden text-gray-900 dark:text-gray-100 sm:block relative text-left dropdown">
    <button
        class="inline-flex justify-center w-full font-medium transition duration-150 ease-in-out"
        type="button"
        aria-haspopup="true"
        aria-expanded="true"
        aria-controls="{{ $id }}-dropdown"
    >
        <span id="{{ $id }}-dropdown-label">{{ $label }}</span>
        <svg class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
        </svg>
    </button>
    <div class="hidden dropdown-menu">
        <div
            class="absolute right-0 w-40 mt-2 origin-top-right bg-white dark:bg-gray-950 divide-y divide-gray-100 dark:divide-slate-50 rounded-md shadow-lg dark:shadow-sm dark:shadow-slate-50 outline-none"
            aria-labelledby="{{ $id }}-dropdown-label"
            id="{{ $id }}-dropdown"
            role="menu"
        >
            {{ $slot }}
        </div>
    </div>
</div>
