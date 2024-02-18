@props(['label'])
<dt class="mt-2 flex justify-end align-text-bottom">
    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="fill-current text-gray-700 dark:text-gray-200 mr-2 w-4">
        {{ $slot }}
    </svg>
    {{ $label }}
</dt>
