{{-- NAVIGATION BAR ng Breeze (Tailwind). x-data="{ open: false }" = Alpine.js: may variable na "open" na False; ito ang nagkokontrol kung nakabukas ang hamburger menu sa mobile. bg-white border-b = puting background na may manipis na guhit sa ibaba. --}}
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    {{-- PANGUNAHING NAVIGATION: max-w-7xl mx-auto = may limitasyon sa lapad at nasa gitna. px-4 sm:px-6 lg:px-8 = espasyo sa gilid na lumalaki sa mas malaking screen. --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- flex justify-between h-16 = magkabilang dulo ang mga laman (kaliwa at kanan), 4rem ang taas. --}}
        <div class="flex justify-between h-16">
            {{-- KALIWANG BAHAGI: logo at mga link. --}}
            <div class="flex">
                {{-- LOGO: shrink-0 = hindi liliit ang logo. flex items-center = nasa gitna patayo. --}}
                <div class="shrink-0 flex items-center">
                    {{-- Kapag pinindot ang logo, pupunta sa dashboard. --}}
                    <a href="{{ route('dashboard') }}">
                        {{-- Component na application-logo (ang SVG logo ng Laravel). Ang mga class ay nagtatakda ng laki (h-9) at kulay. --}}
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                {{-- MGA NAVIGATION LINK (desktop lang). hidden = nakatago sa maliit na screen; sm:flex = lalabas mula sa katamtamang laki pataas. space-x-8 = espasyo sa pagitan ng mga link. --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- Dashboard link. :href = URL papunta sa dashboard. :active = naka-highlight kapag ito ang kasalukuyang page. --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    {{-- Products link. Ang "products.*" ay tugma sa lahat ng products page (index, create, edit, show). --}}
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                        {{ __('Products') }}
                    </x-nav-link>
                    {{-- Reports link. Naka-highlight kapag nasa reports page. --}}
                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                        {{ __('Reports') }}
                    </x-nav-link>
                </div>
            </div>

            {{-- SETTINGS DROPDOWN (desktop lang): nasa kanan, ipinapakita ang pangalan ng user. --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                {{-- Dropdown component. align="right" = nakahanay sa kanan. width="48" = lapad ng menu. --}}
                <x-dropdown align="right" width="48">
                    {{-- TRIGGER: ang pinipindot para bumukas ang dropdown. --}}
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            {{-- Ang pangalan ng naka-login na user. --}}
                            <div>{{ Auth::user()->name }}</div>

                            {{-- Maliit na arrow (pababang tatsulok) sa tabi ng pangalan. --}}
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    {{-- LAMAN NG DROPDOWN: lalabas kapag pinindot ang trigger. --}}
                    <x-slot name="content">
                        {{-- Link papunta sa Profile page. --}}
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        {{-- LOGOUT: dapat POST (form) at hindi simpleng link para ligtas. --}}
                        <form method="POST" action="{{ route('logout') }}">
                            {{-- CSRF token; kung wala ito, magkakaroon ng error 419. --}}
                            @csrf

                            {{-- Ang link na ito ay pinipigilan (preventDefault) na mag-navigate, at isinusumite na lang ang form sa itaas. --}}
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- HAMBURGER BUTTON (mobile lang): -me-2 flex items-center sm:hidden = lalabas lang sa maliit na screen. --}}
            <div class="-me-2 flex items-center sm:hidden">
                {{-- @click="open = ! open" = Alpine.js: tuwing pinindot, binabaligtad ang "open" (True/False), kaya bumubukas o sumasara ang menu. --}}
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        {{-- Icon na tatlong guhit (hamburger). Nakatago kapag nakabukas ang menu (open = true). --}}
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        {{-- Icon na X. Lalabas lang kapag nakabukas ang menu. --}}
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MENU PARA SA MOBILE: nakatago (hidden) bilang default. Ang :class ay nagpapakita (block) kapag open = true. sm:hidden = laging nakatago sa mas malaking screen. --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        {{-- Mga link ng mobile menu, nakasalansan nang pababa (space-y-1). --}}
        <div class="pt-2 pb-3 space-y-1">
            {{-- Dashboard link (mobile). --}}
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            {{-- Products link (mobile). --}}
            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                {{ __('Products') }}
            </x-responsive-nav-link>
            {{-- Reports link (mobile). --}}
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                {{ __('Reports') }}
            </x-responsive-nav-link>
        </div>

        {{-- SETTINGS NG USER (mobile): may guhit sa itaas (border-t). --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
            {{-- Pangalan at email ng naka-login na user. --}}
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                {{-- Link papunta sa Profile page (mobile). --}}
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                {{-- LOGOUT (mobile): parehong paraan, isinusumite ang POST form. --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>