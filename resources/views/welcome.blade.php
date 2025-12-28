<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HiSurabaya! - Hotel in Surabaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <!-- Navbar -->
    <nav class="bg-black text-white fixed w-full z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 lg:px-12">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <img src="{{ asset('storage/logoputih.png') }}" alt="HiSurabaya" class="h-12">
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="hover:text-golden transition">Home</a>
                    <a href="#about" class="hover:text-golden transition">About Us</a>
                    <a href="{{ route('map') }}" class="hover:text-golden transition">View Map</a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 hover:text-golden transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center justify-center w-10 h-10 bg-white rounded-full hover:bg-golden transition">
                            <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    @endauth
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
                <a href="#home" class="block px-3 py-2 hover:bg-gray-800 rounded">Home</a>
                <a href="#about" class="block px-3 py-2 hover:bg-gray-800 rounded">About Us</a>
                <a href="{{ route('map') }}" class="block px-3 py-2 hover:bg-gray-800 rounded">View Map</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 hover:bg-gray-800 rounded">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 hover:bg-gray-800 rounded">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section with Jumbotron -->
    <section id="home" class="relative h-screen flex items-center" style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('{{ asset('storage/jumbotron.png') }}'); background-size: cover; background-position: center;">
        <div class="text-white pl-8 md:pl-16 lg:pl-24 max-w-4xl">
            <h1 class="text-4xl md:text-6xl font-light mb-6">
                We help you find hotels that feel just right
            </h1>
            <p class="text-xl md:text-2xl font-light mb-8">
                curated for quality, comfort, and memorable experiences
            </p>
            @auth
                <a href="{{ route('map') }}" class="inline-block bg-golden text-dark-gray font-semibold px-8 py-3 rounded hover:bg-opacity-90 transition">
                    VIEW MAP
                </a>
            @else
                <a href="{{ route('login') }}" class="inline-block bg-golden text-dark-gray font-semibold px-8 py-3 rounded hover:bg-opacity-90 transition">
                    VIEW MAP
                </a>
            @endauth
        </div>

        <!-- Area Preferences Overlay -->
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 w-full max-w-4xl px-4 z-10">
            <div class="bg-dark-gray bg-opacity-95 rounded-lg shadow-2xl p-8">
                <h2 class="text-2xl font-bold text-white text-center mb-6">
                    FIND HOTELS BASED ON YOUR AREA PREFERENCES
                </h2>
                
                <div class="flex flex-col items-center gap-4">
                    <!-- First Row: 3 buttons -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 w-full">
                        @foreach(['West Surabaya', 'Central Surabaya', 'East Surabaya'] as $area)
                            @auth
                                <a href="{{ route('map', ['area' => strtolower(str_replace(' ', '-', $area))]) }}" 
                                   class="bg-golden text-dark-gray font-semibold py-3 px-6 rounded text-center hover:bg-opacity-90 transition">
                                    {{ $area }}
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="bg-golden text-dark-gray font-semibold py-3 px-6 rounded text-center hover:bg-opacity-90 transition">
                                    {{ $area }}
                                </a>
                            @endauth
                        @endforeach
                    </div>

                    <!-- Second Row: 2 buttons centered -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full md:w-2/3">
                        @foreach(['South Surabaya', 'North Surabaya'] as $area)
                            @auth
                                <a href="{{ route('map', ['area' => strtolower(str_replace(' ', '-', $area))]) }}" 
                                   class="bg-golden text-dark-gray font-semibold py-3 px-6 rounded text-center hover:bg-opacity-90 transition">
                                    {{ $area }}
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="bg-golden text-dark-gray font-semibold py-3 px-6 rounded text-center hover:bg-opacity-90 transition">
                                    {{ $area }}
                                </a>
                            @endauth
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="about" class="py-16 bg-gray-200 pt-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-dark-gray mt-8 mb-6">
                We provide features that are easy to access
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Quality Assurance -->
                <div class="bg-white p-8 rounded">
                    <h3 class="text-xl font-bold text-dark-gray mb-3">Quality Assurance</h3>
                    <p class="text-gray-600 text-sm">Carefully curated for quality and comfort</p>
                </div>

                <!-- Experience Guarantee -->
                <div class="bg-white p-8 rounded">
                    <h3 class="text-xl font-bold text-dark-gray mb-3">Experience Guarantee</h3>
                    <p class="text-gray-600 text-sm">More than a stay, we provide an experience</p>
                </div>

                <!-- Exceptional Service -->
                <div class="bg-white p-8 rounded">
                    <h3 class="text-xl font-bold text-dark-gray mb-3">Exceptional Service</h3>
                    <p class="text-gray-600 text-sm">Seamless support, every step of the way</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Recommendations Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-dark-gray mb-12">Recommendation</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($topHotels->take(3) as $hotel)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                        <!-- Hotel Image -->
                        <div class="h-48 bg-gray-300 overflow-hidden">
                            @if($hotel->images->isNotEmpty())
                                @php
                                    $imageUrl = $hotel->images->first()->ImageURL;
                                    $imageSrc = str_starts_with($imageUrl, 'http') ? $imageUrl : asset('storage/' . $imageUrl);
                                @endphp
                                <img src="{{ $imageSrc }}" 
                                     alt="{{ $hotel->NamaObjek }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.src='{{ asset('storage/jumbotron.png') }}'">
                            @else
                                <img src="{{ asset('storage/jumbotron.png') }}" 
                                     alt="{{ $hotel->NamaObjek }}" 
                                     class="w-full h-full object-cover">
                            @endif
                        </div>

                        <!-- Hotel Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-dark-gray mb-2">{{ $hotel->NamaObjek }}</h3>
                            
                            <div class="flex items-center text-gray-600 mb-3">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm">{{ $hotel->kecamatan->NamaKecamatan ?? 'Surabaya' }}</span>
                            </div>

                            <!-- Star Rating -->
                            <div class="flex items-center mb-3">
                                @for($i = 0; $i < $hotel->StarClass; $i++)
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>

                            <!-- Price Range -->
                            <p class="text-dark-gray font-semibold">
                                Rp{{ number_format($hotel->HargaMin, 0, ',', '.') }} - Rp{{ number_format($hotel->HargaMax, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8 text-gray-500">
                        <p>No hotels available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark-gray text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <!-- Logo -->
                
                <!-- Credits -->
                <div class="text-center md:text-right text-sm text-gray-400">
                    <p>Hafidz Mulia - Rajni Yafi' Amelia Rahmah - Denis Tiara Luthfia</p>
                    <p>Aghnia Tias Salsabila - Reinasya Diar Phalosa</p>
                </div>
                <div class="mb-4 md:mb-0">
                    <img src="{{ asset('storage/logokuning.png') }}" alt="HiSurabaya" class="h-12">
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>

        