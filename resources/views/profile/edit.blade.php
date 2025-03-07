<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold fs-3 text-dark">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-5 py-md-6">
        <div class="container px-3 px-sm-4 px-md-5">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 g-4">
                <div class="col">
                    <div class="card p-4 shadow-sm rounded-3">
                        <div class="card-body">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card p-4 shadow-sm rounded-3">
                        <div class="card-body">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
                <!--
                <div class="col">
                    <div class="card p-4 shadow-sm rounded-3">
                        <div class="card-body">
                        {{-- @include('profile.partials.delete-user-form') --}}
                        </div>
                    </div>
                </div>
                -->
            </div>
        </div>
    </div>
</x-app-layout>
