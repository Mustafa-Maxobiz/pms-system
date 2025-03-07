<x-guest-layout>
    @php
        $title = \App\Models\Setting::first();
    @endphp
    <div class="container d-flex justify-content-center w-100">
        <div class="login-container">
            <div class="logo d-flex justify-content-center">
                <img src="./public/template/Images/Group 149.png" alt="{{ isset($title) ? $title->name : config('app.name', 'Maxobiz') }}   " class="mb-4" />
            </div>
            <h1 class="text-center">Welcome to {{ isset($title) ? $title->name : config('app.name', 'Maxobiz') }}!</h1>
            <p class="subtitle">
                Please sign-in to your account and start the adventure
            </p>
            @error('email')
            <x-input-error messages="{{ $message }}" class="alert alert-danger" />
            @enderror
            
            @if (session('error'))
                <x-input-error messages="{{ session('error') }}" class="alert alert-danger" />
            @endif

            <form method="POST" action="{{ route('login') }}">
            @csrf
                <div class="mb-3">
                    <x-input-label for="login" :value="__('Email Or Username')" />
                    <x-text-input id="login" class="form-control" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" />
                </div>
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
                <x-primary-button class="btn btn-primary w-100 login">
                    {{ __('Log in') }}
                </x-primary-button>
            </form>
        </div>
    </div>
</x-guest-layout>