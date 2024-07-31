<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Google Login Button -->
        <div class="mt-5">
            <a href="{{ route('google.redirect') }}" class="flex items-center justify-center px-4 py-2 bg-white border border-green-500 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-100">
                <img src="{{ asset('logos/google.png') }}" class="w-20 h-6 mr-2" alt="Google Logo">
                <span>{{ __('Continue with Google') }}</span>
            </a>
        </div>


        <div class="float-end mt-4">
            <button type="submit" class="flex items-end justify-center px-4 py-2 bg-white border border-blue-500 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-blue-300">
                <span class="w-20 h-15 mr-2">{{ __('Log in') }}</span>
            </button>
        </div>
    </form>
</x-guest-layout>
