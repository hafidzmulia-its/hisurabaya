<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Additional Styles -->
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <!-- Navbar -->
        <nav class="bg-black text-white fixed w-full z-50 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 lg:px-12">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('storage/logoputih.png') }}" alt="HiSurabaya" class="h-12">
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="hover:text-golden transition">Home</a>
                        <a href="{{ route('home') }}#about" class="hover:text-golden transition">About Us</a>
                        <a href="{{ route('map') }}" class="hover:text-golden transition">View Map</a>
                        
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 hover:text-golden transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ auth()->user()->name }}</span>
                        </a>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-6 py-2.5 bg-red-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-wider hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 shadow-lg hover:shadow-xl transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                </svg>
                                Log Out
                            </button>
                        </form>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button id="mobile-menu-button" class="text-white hover:text-golden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-black border-t border-gray-700">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ route('home') }}" class="block px-3 py-2 hover:bg-gray-800 rounded">Home</a>
                    <a href="{{ route('home') }}#about" class="block px-3 py-2 hover:bg-gray-800 rounded">About Us</a>
                    <a href="{{ route('map') }}" class="block px-3 py-2 hover:bg-gray-800 rounded">View Map</a>
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 hover:bg-gray-800 rounded">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 hover:bg-gray-800 rounded">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="pt-20">
            {{ $slot }}
        </main>
        
        <!-- Additional Scripts -->
        @stack('scripts')
        
        <script>
            // Mobile menu toggle
            document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
                document.getElementById('mobile-menu').classList.toggle('hidden');
            });
        </script>
    </body>
</html>
