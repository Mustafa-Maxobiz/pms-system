<section class="mb-4">
    <header>
        <h2 class="fs-4 fw-semibold text-dark">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-2 text-muted small">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <!-- Button to open the modal -->
    <button 
        class="btn btn-primary mt-3"
        id="delete-account-button"
    >
        {{ __('Delete Account') }}
    </button>

    <!-- Modal -->
    <div id="confirm-user-deletion" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center hidden">
        <div class="bg-white p-4 rounded-lg w-96">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <h2 class="fs-5 fw-semibold text-dark">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="mt-2 text-muted small">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="mt-4">
                    <x-input-label for="password" value="{{ __('Password') }}" class="visually-hidden" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="form-control mt-2"
                        placeholder="{{ __('Password') }}"
                    />

                    {{-- Error Handling for Password Field --}}
                    @error('password')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-secondary me-2" id="cancel-button">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="btn btn-primary">
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    // JavaScript to handle modal visibility
    document.getElementById('delete-account-button').addEventListener('click', function() {
        document.getElementById('confirm-user-deletion').classList.remove('hidden');
    });

    // Close the modal when clicking the cancel button
    document.getElementById('cancel-button').addEventListener('click', function() {
        document.getElementById('confirm-user-deletion').classList.add('hidden');
    });

    // Optionally close the modal if clicking outside the modal
    document.getElementById('confirm-user-deletion').addEventListener('click', function(event) {
        if (event.target === this) {
            document.getElementById('confirm-user-deletion').classList.add('hidden');
        }
    });
</script>
