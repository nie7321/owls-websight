<x-guest-layout>
    <header class="pt-6 xl:pb-6">
        <div class="space-y-2 pt-6 md:space-y-5">
            <h1 class="text-3xl font-extrabold leading-9 tracking-tight text-gray-900 dark:text-gray-100 sm:text-4xl sm:leading-10 md:text-6xl md:leading-14">
                Contact
            </h1>
        </div>
    </header>
    <article>
        <div class="divide-y divide-gray-200 dark:divide-gray-700 xl:col-span-3 xl:row-span-2 xl:pb-0">
            <div class="prose max-w-none pb-8 pt-10 dark:prose-invert">
                <p>If you would like to reach out, I can be found in a variety of places on the internets, from social media platforms to video games. If you want to get in touch, feel free to reach out where ever you feel most at home.</p>

                <p>If you would like to send an encrypted email, my <a href="{{ asset('nick-at-godless-internets-dot-org-2024-02.asc') }}">public key is available for download</a>. The fingerprint is <code>0cb11d9477db14727719690e55e2142e5fe2c0f9</code>.</p>

                <div class="flex flex-wrap w-full md:w-2/3">
                    <x-contact-entry label="Email" icon="brand-icons.email">
                        <a href="mailto:nick@godless-internets.org">nick@godless-internets.org</a>
                    </x-contact-entry>

                    <x-contact-entry label="Matrix" icon="brand-icons.matrix">
                        <a href="https://matrix.to/#/@owls:chat.yshi.org">@owls:chat.yshi.org</a>
                    </x-contact-entry>

                    <x-contact-entry label="Fediverse" icon="brand-icons.mastodon">
                        <a href="https://mastodon.yshi.org/@owls">@owls@yshi.org</a>
                    </x-contact-entry>

                    <x-contact-entry label="Signal" icon="brand-icons.signal">
                        <a href="https://signal.me/#eu/Aynp6drdG_iL9V_KYWVZUp8LRK4kbuF_zkCpeHgK_RpNf-Xl4TdLUZsCxg5cdRD7">owls.01</a>
                    </x-contact-entry>

                    <x-contact-entry label="Codeberg" icon="brand-icons.codeberg">
                        <a href="https://codeberg.org/owls">owls</a>
                    </x-contact-entry>

                    <x-contact-entry label="Discord" icon="brand-icons.discord">
                        <a href="https://discordapp.com/users/147877205949546496">owlmanatt</a>
                    </x-contact-entry>

                    <x-contact-entry label="Steam" icon="brand-icons.steam">
                        <a href="https://steamcommunity.com/id/owlmanatt/">owlmanatt</a>
                    </x-contact-entry>

                    <x-contact-entry label="Guild Wars 2" icon="brand-icons.gw2">
                        owlmanatt.2415
                    </x-contact-entry>

                    <x-contact-entry label="Nintendo" icon="brand-icons.nintendo">
                        SW-2550-8719-1408
                    </x-contact-entry>
                </div>
            </div>
        </div>
    </article>
</x-guest-layout>
