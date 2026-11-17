<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Hardware IMS') }}</title>

        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    </head>
    <body class="font-sans antialiased">
        <div class="auth-wrapper min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="auth-brand text-center mb-4">
                <h1 class="auth-title">Hardware IMS</h1>
                <p class="auth-subtitle">Inventory Management System</p>
            </div>

            <div class="auth-card w-full sm:max-w-md mt-2 px-6 py-6 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>