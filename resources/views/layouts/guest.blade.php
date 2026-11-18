<!DOCTYPE html>
{{-- Sinasabi sa browser na HTML5 ito. Ang lang ay kinukuha sa setting ng Laravel (app locale), at pinapalitan ang _ ng - (hal. en_US ay en-US). --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    {{-- HEAD: impormasyon tungkol sa page na hindi nakikita sa screen. --}}
    <head>
        {{-- UTF-8 = tamang pagbasa ng lahat ng letra at simbolo. --}}
        <meta charset="utf-8">
        {{-- Para responsive: sumusunod ang lapad ng page sa lapad ng device. --}}
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- CSRF token: panangga laban sa pekeng form submission galing sa ibang website. --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Pamagat sa browser tab. Kinukuha sa app name ng config; "Hardware IMS" ang default kung wala. --}}
        <title>{{ config('app.name', 'Hardware IMS') }}</title>

        {{-- Tailwind CSS galing sa CDN (kasama ang forms plugin). Ginamit natin ito imbes na "npm run build" dahil hinarang ng Windows Application Control ang native build ng Vite. --}}
        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
        {{-- Alpine.js galing sa CDN: kailangan ng ilang interactive na bahagi ng Breeze (hal. dropdown). defer = ilo-load pagkatapos ng page. --}}
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
        {{-- Ating custom CSS (purple theme). Ang asset() ay gumagawa ng URL papunta sa public/css/custom.css. --}}
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    </head>
    {{-- BODY: font-sans = default na sans-serif na font. antialiased = mas malinis na pagkaka-render ng letra. --}}
    <body class="font-sans antialiased">
        {{-- Wrapper ng buong page. Ang auth-wrapper ay ang purple gradient na background (custom.css). min-h-screen = hindi bababa sa taas ng screen. flex flex-col items-center = nakaayos pababa at nasa gitna pahalang. sm:justify-center = nasa gitna din patayo sa mas malaking screen. --}}
        <div class="auth-wrapper min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            {{-- BRAND: pangalan ng system sa itaas ng card. text-center = nasa gitna. mb-4 = espasyo sa ibaba. --}}
            <div class="auth-brand text-center mb-4">
                {{-- Malaking pamagat (puti, makapal; ayon sa custom.css). --}}
                <h1 class="auth-title">Hardware IMS</h1>
                {{-- Maliit na paliwanag sa ilalim ng pamagat. --}}
                <p class="auth-subtitle">Inventory Management System</p>
            </div>

            {{-- CARD NG FORM: puting kahon (auth-card). w-full sm:max-w-md = buong lapad sa phone, hanggang katamtamang lapad sa mas malaking screen. px-6 py-6 = espasyo sa loob. shadow-md = anino. sm:rounded-lg = bilugang sulok. --}}
            <div class="auth-card w-full sm:max-w-md mt-2 px-6 py-6 shadow-md overflow-hidden sm:rounded-lg">
                {{-- $slot = dito ilalagay ang laman ng bawat auth page (hal. ang login form sa auth/login.blade.php na gumagamit ng x-guest-layout). --}}
                {{ $slot }}
            </div>
        </div>
    </body>
</html>