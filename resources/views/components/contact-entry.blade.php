@props(['label', 'icon'])
<div class="w-full md:w-[45%] mt-2 mb-2 md:mb-0 flex align-text-bottom">
    <x-dynamic-component :component="$icon" class="fill-current text-gray-700 dark:text-gray-200 w-8" aria-hidden="true" />
    <div class="ml-4">
        <div class="text-xl">
            {{ $slot }}
        </div>
        <div class="text-gray-600 dark:text-gray-400">
            {{ $label }}
        </div>
    </div>
</div>
