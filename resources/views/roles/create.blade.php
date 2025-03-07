<x-app-layout>
    <div id="add-roles" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Add Role</h6>
                        </div>
                        <form method="POST" action="{{ route('roles.store') }}" style="padding: 30px;">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <input 
                                        type="text" 
                                        class="form-control @error('name') is-invalid @enderror" 
                                        id="name" 
                                        name="name"
                                        placeholder="Role Name" 
                                        value="{{ old('name') }}"
                                    >
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="d-flex flex-wrap">
                                        <div class="form-check-inline">
                                            <input 
                                                type="checkbox" 
                                                id="permission-all"
                                            >
                                            <label class="form-check-label" for="permission-all">Check All</label>
                                        </div>
                                    </div>
                                </div>
                                @if($permissions->isNotEmpty())
                                    @foreach($permissions as $key => $permission)
                                    <div class="col-md-3 mb-3">
                                        <div class="d-flex flex-wrap">
                                            <div class="form-check-inline">
                                                <input 
                                                    type="checkbox" 
                                                    id="permission-{{ $permission->id }}" 
                                                    name="permission[]" 
                                                    class="permission-checkbox"
                                                    value="{{ $permission->name }}"
                                                >
                                                <label class="form-check-label" for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif

                            </div>

                            
                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                    <a href="{{ route('roles.index') }}" class="btn btn-warning"><i class="fa fa-arrow-rotate-left"></i> Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
    <script>
        // Select the "Check All" checkbox
        const checkAllBox = document.getElementById('permission-all');
        // Select all individual permission checkboxes
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');

        // Add an event listener to "Check All" checkbox
        checkAllBox.addEventListener('change', function () {
            const isChecked = checkAllBox.checked;

            // Loop through all permission checkboxes and update their state
            permissionCheckboxes.forEach(function (checkbox) {
                checkbox.checked = isChecked;
            });
        });

        // Optional: Add logic to update "Check All" checkbox state based on individual checkboxes
        permissionCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(permissionCheckboxes).some(cb => cb.checked);

                // Update "Check All" checkbox state
                checkAllBox.checked = allChecked;
                checkAllBox.indeterminate = !allChecked && someChecked;
            });
        });
    </script>
    @endsection
</x-app-layout>
