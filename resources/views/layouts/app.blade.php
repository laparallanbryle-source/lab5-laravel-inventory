<!DOCTYPE html>
{{-- Ang lang ay kinukuha sa setting ng Laravel (hal. en_US ay nagiging en-US). --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- Tamang pagbasa ng lahat ng letra at simbolo. --}}
        <meta charset="utf-8">
        {{-- Para responsive: sumusunod ang lapad ng page sa lapad ng device. --}}
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- CSRF token: panangga laban sa pekeng form submission. --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Pamagat sa browser tab. --}}
        <title>{{ config('app.name', 'Hardware IMS') }}</title>

        {{-- @vite = kinukuha ang CSS at JavaScript na ginawa ng "npm run build" sa public/build (Tailwind at Alpine.js). --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        {{-- Buong page: abuhing background, hindi bababa sa taas ng screen. --}}
        <div class="min-h-screen bg-gray-100">
            {{-- Isinasama ang navigation bar (layouts/navigation.blade.php). --}}
            @include('layouts.navigation')

            {{-- Page heading: lalabas lang kung may $header ang page. --}}
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Laman ng page. Ang $slot ay ang nasa loob ng x-app-layout. --}}
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>