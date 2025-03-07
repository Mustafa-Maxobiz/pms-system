<section class="mb-5">
    <header>
        <h2 class="fs-4 fw-semibold text-dark">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-2 fs-6 text-secondary">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Picture Field -->
        <div class="mb-3">
            <label for="profilePicture" class="form-label">{{ __('Profile Picture') }}</label>
            <div class="mb-2">
                @if (auth()->user()->profile_picture)
                    <img src="{{ asset('storage/app/public/' . $user->profile_picture) }}" alt="Profile Picture"
                        class="img-thumbnail" style="max-height: 150px;">
                @else
                    <img src="{{ asset('default-profile.png') }}" alt="Profile Picture" class="img-thumbnail"
                        style="max-height: 150px;">
                @endif
            </div>
            <input type="file" id="profile_picture" name="profile_picture"
                class="form-control @error('profile_picture') is-invalid @enderror"
                accept="image/png, image/jpeg, image/webp">
            {{-- @error('profile_picture')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror --}}
        </div>


        <!-- Name Field -->
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" id="name" name="name" class="form-control"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" id="email" name="email" class="form-control"
                value="{{ old('email', $user->email) }}" required autocomplete="username" readonly>

            @error('email')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="fs-6 mt-2 text-dark">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="btn btn-link text-dark p-0 m-0">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-success fs-6">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Save Button -->
        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p class="fs-6 text-success">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>

</section>
