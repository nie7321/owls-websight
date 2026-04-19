@props(['href'])
<a
    href="{{ $href }}"
    class="flex justify-between w-full px-4 py-2 text-left" role="menuitem"
>
    {{ $slot }}
</a>
