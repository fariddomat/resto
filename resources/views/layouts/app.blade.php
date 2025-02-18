<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"
    livewire:navigate>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dashboard') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('noty/noty.css') }}">
    <script src="{{ asset('noty/noty.min.js') }}" defer></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
         html[dir="rtl"] th{
            text-align: right;
         }
        @media (max-width: 768px) {
            html[dir="rtl"] .main-b {
                margin-left: 0 !important;
                margin-right: -16rem !important;
            }

            html[dir="rtl"] .-translate-x-64 {
                --tw-translate-x: +16rem;
            }

        }

        @media (min-width: 768px) {
            html[dir="rtl"] aside {
                left: unset !important;
            }

            html[dir="ltr"] .main-b {
                margin-left: 16rem !important;
                margin-right: 0 !important;
            }

            html[dir="rtl"] .main-b {
                margin-left: 0 !important;
                margin-right: 16rem !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100">

    <div class="flex h-screen  overflow-x-hidden" x-data="{ open: false }">
        <!-- Sidebar Overlay (Mobile) -->
        <div class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden" x-show="open" @click="open = false"></div>

        <!-- Sidebar -->
        <aside
            class="bg-gray-900 text-white p-5 w-64 md:w-64 md:fixed md:top-0 md:left-0 md:h-screen transition-all duration-300 ease-in-out z-50 overflow-y-auto"
            :class="open ? 'translate-x-0' : '-translate-x-64 md:translate-x-0'">
            <h2 class="text-xl font-bold mb-4">@lang('site.dashboard')</h2>

            <nav class="mt-5 space-y-3 ">
                <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    @lang('site.home') <i class="fas fa-home"></i>
                </x-responsive-nav-link>
                <x-responsive-nav-link href="{{ route('dashboard.users.index') }}" :active="Str::startsWith(request()->route()->getName(), 'dashboard.users.')">
                   @lang('site.users') <i class="fas fa-users"></i>
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('dashboard.blogcategories.index') }}" :active="Str::startsWith(request()->route()->getName(), 'dashboard.blogcategories.')">
                    @lang('site.categories') <i class="fas fa-list-alt"></i>
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('dashboard.blogs.index') }}" :active="Str::startsWith(request()->route()->getName(), 'dashboard.blogs.')">
                     @lang('site.blogs') <i class="fas fa-newspaper"></i>
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('profile') }}" :active="request()->routeIs('profile')">
                    @lang('site.profile') <i class="fas fa-user-cog"></i>
                </x-responsive-nav-link>
            </nav>
        </aside>

        <div class="container main-b flex-1 flex flex-col -ml-64 md:ml-64">

            <!-- Navbar -->
            <header class="bg-blue-800 text-white  shadow p-4 flex justify-between items-center">
                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="md:hidden text-gray-700 text-2xl">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- User Actions -->
                <div class="flex space-x-3 items-center text-lg">
                    <span class="cursor-pointer hover:text-gray-500pr-2">
                        <i class="fas fa-bell"></i>
                    </span>

                    <span class="cursor-pointer hover:text-gray-500 pr-2">
                        <i class="fas fa-user"></i> User
                    </span>

                    <!-- Logout Button -->
                    {{-- <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-red-500 hover:text-red-600 transition-all duration-200">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form> --}}
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts

    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include ajaxForm library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

    @extends('layouts._noty')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('redirect', (url) => {
                window.location.href = url;
            });
        });
    </script>


</body>

</html>
