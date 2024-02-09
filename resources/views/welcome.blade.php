<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>owls, of the godless internets</title>
        @vite('resources/css/app.css')
    </head>
    <body class="text-white bg-slate-800 h-screen flex flex-col">
        <div class="container mx-auto h-full flex justify-center items-center">
            <div class="bg-zinc-900 max-w-sm rounded overflow-hidden shadow-lg p-6">
                <img class="block mx-auto" src="https://mastomedia.yshi.org/accounts/avatars/108/239/251/250/937/273/original/680abda5e63ccb5a.png" alt="owls">

                <div class="w-max text-center shadow-lg opacity-50 rounded mt-3 p-1">
                    <h1 class="text-2xl font-semibold">owls</h1>
                    <h2 class="text-sm text-gray-400">of the godless internets</h2>
                </div>
            </div>
        </div>

        <footer class="w-full pb-6">
            <ul class="list-reset flex justify-center">
                <li class="px-6">
                    <a href="https://godless-internets.org/" class="lowercase text-gray-600 underline">blog</a>
                </li>
                <li class="px-6">
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="lowercase text-gray-600 underline">admin</a>
                </li>
            </ul>
        </footer>

        @vite(['resources/js/app.js'])
    </body>
</html>
