<!DOCTYPE html>
{{-- Sinasabi sa browser na HTML5 ang gamit nating bersyon ng HTML. --}}
<html lang="en">
{{-- Ugat ng buong page. lang="en" = English ang wika ng page. --}}
<head>
    {{-- HEAD: mga impormasyon tungkol sa page na hindi nakikita sa mismong screen. --}}
    <meta charset="UTF-8">
    {{-- UTF-8 = para tama ang pagbasa ng lahat ng letra at simbolo (hal. ang peso sign ₱). --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Para responsive: ang lapad ng page ay sumusunod sa lapad ng device (phone, tablet, PC). --}}
    <title>@yield('title', 'Inventory') - Hardware IMS</title>
    {{-- Pamagat sa browser tab. Ang @yield('title') ay kukuha ng title mula sa bawat page; kung wala, "Inventory" ang default. --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- CSRF token: panangga laban sa pekeng form submission galing sa ibang website. Ang csrf_token() ay ginagawa ni Laravel. --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Ikinakabit ang Bootstrap 5 CSS galing sa internet (CDN): ready-made na styles ng grid, card, button, table, at navbar. --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    {{-- Ikinakabit ang sarili nating CSS (purple theme). Nasa ilalim ng Bootstrap para ma-override natin ang styles niya. Ang asset() ay gumagawa ng URL papunta sa public/css/custom.css. --}}
</head>
{{-- Dulo ng head. --}}
<body class="bg-light">
{{-- BODY: lahat ng nakikita sa page. bg-light = mapusyaw na abuhing background. --}}

<nav class="navbar navbar-expand-md navbar-dark app-navbar">
    {{-- NAVIGATION BAR. navbar-expand-md = nagiging hamburger menu sa maliit na screen. navbar-dark = puti ang text. app-navbar = ang purple na kulay mula sa custom.css. --}}
    <div class="container-fluid">
        {{-- Lalagyan ng laman ng navbar na sumasakop sa buong lapad. --}}
        <a class="navbar-brand" href="{{ route('dashboard') }}">Hardware IMS</a>
        {{-- Pangalan ng system sa kaliwa. Kapag pinindot, pupunta sa dashboard. Ang route('dashboard') ay gumagawa ng URL ayon sa pangalan ng route. --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            {{-- Hamburger button para sa mobile. Kapag pinindot, ipinapakita o itinatago ang menu na may id na "navMenu". --}}
            <span class="navbar-toggler-icon"></span>
            {{-- Ang icon na tatlong guhit ng hamburger. --}}
        </button>
        {{-- Dulo ng hamburger button. --}}
        <div class="collapse navbar-collapse" id="navMenu">
            {{-- Ang menu na itinatago o ipinapakita ng hamburger. Ang id ay tumutugma sa data-bs-target sa itaas. --}}
            <ul class="navbar-nav me-auto">
                {{-- Listahan ng mga link. me-auto = itinutulak ang natitirang laman (username at logout) sa kanan. --}}
                <li class="nav-item">
                    {{-- Isang item sa menu: Dashboard. --}}
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    {{-- Kapag nasa dashboard ang user (routeIs), nilalagyan ng class na "active" para naka-highlight. Kung hindi, walang idadagdag. --}}
                </li>
                <li class="nav-item">
                    {{-- Susunod na item: Products. --}}
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Products</a>
                    {{-- Ang "products.*" ay tumutugma sa lahat ng products page (index, create, edit, show), kaya highlighted ang Products sa lahat ng iyon. --}}
                </li>
                <li class="nav-item">
                    {{-- Susunod na item: Reports. --}}
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">Reports</a>
                    {{-- Naka-highlight kapag nasa reports page. Papunta sa /reports. --}}
                </li>
            </ul>
            {{-- Dulo ng listahan ng mga link. --}}
            <span class="text-white me-3">{{ Auth::user()->name }}</span>
            {{-- Ipinapakita ang pangalan ng naka-login na user. Auth::user() = ang kasalukuyang user. --}}
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                {{-- Logout form. Dapat POST ang logout (hindi link) para ligtas. d-inline = hindi bababa sa susunod na linya. --}}
                @csrf
                {{-- Naglalagay ng nakatagong CSRF token. Kung wala ito, tatanggihan ni Laravel ang form (error 419). --}}
                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                {{-- Logout button. Kapag pinindot, isusumite ang form at mag-lo-logout ang user. --}}
            </form>
            {{-- Dulo ng logout form. --}}
        </div>
        {{-- Dulo ng collapsible na menu. --}}
    </div>
    {{-- Dulo ng container-fluid. --}}
</nav>
{{-- Dulo ng navbar. --}}

<main class="container py-4">
    {{-- Pangunahing laman ng page. container = may margin sa gilid at nasa gitna. py-4 = espasyo sa itaas at ibaba. --}}
    @if (session('success'))
        {{-- Kung may "success" na flash message sa session (halimbawa pagkatapos mag-add o mag-update ng product)... --}}
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{-- ...ipakita ang berdeng alert box na pwedeng isara (dismissible). --}}
            {{ session('success') }}
            {{-- Ang mismong mensahe, hal. "Product added successfully!". --}}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{-- X button na nagsasara ng alert. --}}
        </div>
        {{-- Dulo ng alert. --}}
    @endif
    {{-- Dulo ng @if. --}}

    @yield('content')
    {{-- Dito ilalagay ang laman ng bawat page (products, dashboard, reports) na may @section('content'). Ito ang "butas" ng layout. --}}
</main>
{{-- Dulo ng main. --}}

<footer class="text-center text-muted small pb-4">&copy; {{ date('Y') }} Hardware Inventory Management System</footer>
{{-- Footer: nasa gitna, abuhin, maliit ang text. &copy; = ©. Ang date('Y') ay awtomatikong kasalukuyang taon. --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- Bootstrap JavaScript: kailangan para gumana ang hamburger menu at ang pagsara ng alert. Nasa dulo ng body para nakaload na ang page bago tumakbo. --}}
@stack('scripts')
{{-- Dito ilalagay ang mga script na galing sa ibang page gamit ang @push('scripts') (halimbawa ang Chart.js sa dashboard). --}}
</body>
{{-- Dulo ng body. --}}
</html>
{{-- Dulo ng buong HTML. --}}