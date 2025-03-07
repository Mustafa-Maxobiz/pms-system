<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="register-container p-4 shadow-lg rounded bg-white">
            <div class="logo d-flex justify-content-center mb-4">
            <img src="./public/template/Images/Group 149.png" alt="{{ config('app.name', 'Laravel') }}" class="mb-4" />
            </div>
            <h1 class="text-center mb-3">Join {{ config('app.name', 'Laravel') }}!</h1>
            <p class="subtitle text-center mb-4">
                Create an account and start your journey with us.
            </p>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <!-- Name -->
                <div class="mb-3">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <!-- Email Address -->
                <div class="mb-3">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <x-input-label for="password" :value="__('Password')" />
                    <div class="input-group">
                        <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                        <span class="input-group-text" id="togglePassword">
                            <i class="fa fa-eye" id="eyeIcon"></i>
                        </span>
                    </div>
                    @error('password')
                    <x-input-error messages="{{ $message }}" class="text-danger mt-2" />
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <!-- Actions -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        {{ __('Already registered?') }}
                    </a>
                    <x-primary-button class="btn btn-primary">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>