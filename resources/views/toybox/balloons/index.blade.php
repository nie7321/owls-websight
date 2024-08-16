<x-guest-layout>
    <div class="space-y-2 pb-8 pt-6 md:space-y-5 text-center">
        <h1
            class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl sm:leading-10 md:text-6xl md:leading-14"
        >
            It's a Party!
        </h1>
    </div>

    <!-- Inspired by https://codepen.io/bennettfeely/pen/DrNgoO -->
    <div class="text-center flex justify-between">
        <x-balloon class="balloon fill-blue-500" style="--balloon-duration: 4.4s"/>
        <x-balloon class="balloon fill-yellow-500" style="--balloon-duration: 3.5s"/>
        <x-balloon class="balloon fill-green-500" style="--balloon-duration: 3.9s"/>
        <x-balloon class="balloon fill-pink-500" style="--balloon-duration: 2.8s"/>
        <x-balloon class="balloon fill-purple-500" style="--balloon-duration: 3.3s"/>
    </div>

    <x-slot:page-styles>
        <style>
            @property --balloon-duration {
                syntax: "<transition-duration>";
                inherits: false;
                initial-value: 4s;
            }

            .balloon {
                background: url("{{ asset('image/toybox/balloon.svg') }}");
                width: 200px;
                height: 200px;

                position: relative;
                margin: 20px 30px;
                transition: transform 0.5s ease;
                animation: balloons var(--balloon-duration) ease-in-out infinite;
                transform-origin: bottom center;
            }

            @keyframes balloons {
                0%, 100% {
                    transform: translateY(0) rotate(-4deg);
                }
                50% {
                    transform: translateY(-25px) rotate(4deg);
                }
            }
        </style>
    </x-slot:page-styles>
</x-guest-layout>
