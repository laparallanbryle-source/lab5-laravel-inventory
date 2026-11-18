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
    {{-- Bootstrap 5 CSS galing sa CDN. --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Ating custom CSS (purple theme) na nag-o-override ng Bootstrap. --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    {{-- Purple gradient na background (.auth-wrapper). min-vh-100 = hindi bababa sa taas ng screen. d-flex + justify/align = nasa gitna. p-3 = espasyo sa gilid sa phone. --}}
    <div class="auth-wrapper min-vh-100 d-flex flex-column justify-content-center align-items-center p-3">
        {{-- Pangalan ng system sa itaas ng card. --}}
        <div class="auth-brand text-center mb-4">
            <h1 class="auth-title">Hardware IMS</h1>
            <p class="auth-subtitle">Inventory Management System</p>
        </div>
        {{-- Puting card ng form. w-100 + max-width = buong lapad sa phone, hanggang 420px sa malaki. --}}
        <div class="auth-card w-100 p-4 shadow" style="max-width: 420px;">
            {{-- Dito ilalagay ang laman ng bawat auth page (login, register, atbp.). --}}
            {{ $slot }}
        </div>
    </div>
    {{-- Bootstrap JavaScript. --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>