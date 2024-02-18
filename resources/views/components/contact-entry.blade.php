@props(['label', 'icon'])
<div class="w-full md:w-[45%] mt-2 flex align-text-bottom">
    <div class="basis-6 flex align-text-bottom">
        <x-dynamic-component :component="$icon" class="fill-current text-gray-700 dark:text-gray-200 w-4"/>
        <span class="sr-only">{{ $label }}</span>
    </div>
    <div>
        {{ $slot }}
    </div>
</div>
