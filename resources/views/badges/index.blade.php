<x-guest-layout title="Badges & Buttons">
    <div class="space-y-2 pb-8 pt-6 md:space-y-5 text-center">
        <h1
            class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl sm:leading-10 md:text-6xl md:leading-14"
        >
            Badges &amp; Buttons
        </h1>
        <p class="text-lg leading-7 text-gray-500 dark:text-gray-400">
            These are webrings and other sorts of fun.
        </p>
    </div>

    <div class="grid grid-cols-8 gap-4">
        <div>
            <a href="https://vintagestory.network">
                <img src="{{ asset('image/badges/vs50x50_6.png.png') }}" loading="lazy" alt="Vintage Story Fanlisting">
            </a>
        </div>

        <x-badge.88x31 :image="asset('image/tfnow.gif')" alt="Team Fortress Now!"/>
        <x-badge.88x31 :image="asset('image/php.gif')" alt="Powered by PHP"/>
        <x-badge.88x31 :image="asset('image/petsites.gif')" alt="Top 100 Pet Sites"/>
        <x-badge.88x31 :image="asset('image/internetprivacy.gif')" alt="Internet Privacy Now" link="https://eff.org" />
    </div>
</x-guest-layout>
