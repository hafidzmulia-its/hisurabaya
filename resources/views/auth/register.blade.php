<x-guest-layout>
    <div class="flex bg-white shadow-2xl overflow-hidden max-w-7xl w-full">
        <!-- Left Side - Hotel Image -->
        <div class="hidden lg:block lg:w-1/2 xl:w-[55%]">
            <img src="{{ asset('storage/login.png') }}" alt="HiSurabaya Hotel" class="w-full h-full object-cover">
        </div>

        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 xl:w-[45%] p-12">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('storage/logo.png') }}" alt="HiSurabaya" class="h-16">
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Sign up</h2>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Register as</label>
                    <select id="role" name="role" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User (Guest)</option>
                        <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Hotel Owner</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-gray-900 text-white font-semibold py-3 rounded-lg hover:bg-gray-800 transition duration-150 mt-6">
                    Sign up
                </button>
            </form>
            
            <!-- Divider -->
            <div class="relative flex items-center justify-center w-full my-6">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="flex-shrink mx-4 text-sm text-gray-500">OR</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>
            
            <!-- Google OAuth Button -->
            <a href="{{ route('auth.google') }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Sign up with Google
            </a>

            <!-- Sign In Link -->
            <p class="text-center text-sm text-gray-600 mt-6">
                Already have an account? <a href="{{ route('login') }}" class="font-semibold text-gray-900 hover:underline">Sign in</a>
            </p>
        </div>
    </div>
</x-guest-layout>
