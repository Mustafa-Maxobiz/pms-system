<x-app-layout>
    <div id="edit-user" class="mb-4 mt-4 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Edit User</h6>
                        </div>
                        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data"
                            style="padding: 30px;">
                            @csrf
                            @method('PUT')
                            <!-- Row -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="name" class="form-label">First Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}"
                                        autocomplete="on" placeholder="First Name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="last_name" class="form-label">Last Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                        autocomplete="on" placeholder="Last Name" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="father_name" class="form-label">Father Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('father_name') is-invalid @enderror" id="father_name"
                                        name="father_name" value="{{ old('father_name', $user->father_name) }}"
                                        autocomplete="on" placeholder="Father Name" required>
                                    @error('father_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">Gender<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender"
                                        name="gender" required>
                                        <option value="">Gender</option>
                                        <option value="Male"
                                            {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female"
                                            {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female
                                        </option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="dob" class="form-label">D.O.B<span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('dob') is-invalid @enderror"
                                        id="dob" name="dob" value="{{ old('dob', $user->dob) }}" required>
                                    @error('dob')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nic" class="form-label">NIC<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nic') is-invalid @enderror"
                                        id="nic" name="nic" value="{{ old('nic', $user->nic) }}"
                                        autocomplete="off" placeholder="00000-0000000-0" maxlength="15"
                                        oninput="formatNIC(this)" required>
                                    @error('nic')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label for="address" class="form-label">Address<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                        id="address" name="address" value="{{ old('address', $user->address) }}"
                                        placeholder="Address" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">City<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city', $user->city) }}"
                                        placeholder="City" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="country" class="form-label">Country<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror"
                                        id="country" name="country" value="{{ old('country', $user->country) }}"
                                        placeholder="Country" required>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="form-label">Mobile<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                        id="mobile" name="mobile" value="{{ old('mobile', $user->mobile) }}"
                                        placeholder="Mobile" required>
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                        placeholder="Phone">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="department_id" class="form-label">Department<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('department_id') is-invalid @enderror"
                                        id="department_id" name="department_id" required>
                                        <option value="" selected>Department</option>
                                        @foreach ($departments as $department_id)
                                            <option value="{{ $department_id->id }}"
                                                {{ old('department_id', $user->department_id) == $department_id->id ? 'selected' : '' }}>
                                                {{ $department_id->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="team_id" class="form-label">Team<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('team_id') is-invalid @enderror"
                                        id="team_id" name="team_id" required>
                                        <option value="" disabled selected>Team</option>
                                    </select>
                                    @error('team_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="email" class="form-label">Personal Email<span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}"
                                        placeholder="Personal Email" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="work_email" class="form-label">Work Email (for Login)<span
                                            class="text-danger">*</span></label>
                                    <input type="email"
                                        class="form-control @error('work_email') is-invalid @enderror"
                                        id="work_email" name="work_email"
                                        value="{{ old('work_email', $user->work_email) }}" placeholder="Work Email"
                                        required>
                                    @error('work_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="username" class="form-label">Username (for Login)<span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('username') is-invalid @enderror"
                                        id="username" name="username"
                                        value="{{ old('username', $user->username) }}" placeholder="Username"
                                        required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="password" class="form-label">Password (min 8 characters)</label>
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror" id="password"
                                        name="password" placeholder="Leave blank if not changing password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="confirmed" class="form-label">Confirm Password (min 8
                                        characters)</label>
                                    <input type="password"
                                        class="form-control @error('confirmed') is-invalid @enderror" id="confirmed"
                                        name="confirmed" placeholder="Confirm Password">
                                    @error('confirmed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Row for Picture -->
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="profile_picture" class="form-label">Profile Picture</label>
                                    <input type="file"
                                        class="form-control @error('profile_picture') is-invalid @enderror"
                                        id="profile_picture" name="profile_picture"
                                        accept="image/png, image/jpeg, image/webp">
                                    @error('profile_picture')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Allowed formats: JPG, PNG, WEBP. Max size: 1MB</small>
                                </div>
                                <div class="col-md-3 mb-3">
                                    @if ($user->profile_picture)
                                        <label for="profile_picture" class="form-label">Your Profile
                                            Picture</label><br>
                                        <img src="{{ asset('storage/app/public/' . $user->profile_picture) }}"
                                            alt="Profile Picture" class="img-thumbnail" style="max-height: 150px;">
                                        <a href="javascript:void(0);" onclick="event.preventDefault();"
                                            class="btn btn-dark btn-sm p-1"><i class="fa fa-trash"></i></a>
                                    @endif
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="status" class="form-label">Status</label> 
                                    <select class="form-control select2 @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label for="is_visible" class="form-label">Is Visible</label>
                                    <select class="form-control select2 @error('is_visible') is-invalid @enderror" id="is_visible" name="is_visible">
                                        <option value="1" {{ old('is_visible', $user->is_visible) == 1 ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ old('is_visible', $user->is_visible) == 0 ? 'selected' : '' }}>No</option>
                                    </select>
                                    @error('is_visible')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>                                
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Access Level</label><br>
                                @if ($roles->isNotEmpty())
                                    @foreach ($roles as $role)
                                        <div class="form-check-inline">
                                            <input type="checkbox" id="role-{{ $role->id }}" name="role[]"
                                                value="{{ $role->name }}"
                                                @if (in_array($role->name, old('role', $user->roles->pluck('name')->toArray()))) checked @endif>
                                            <label class="form-check-label"
                                                for="role-{{ $role->id }}">{{ $role->name }}</label>
                                        </div>
                                    @endforeach
                                @endif

                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Row -->
                            <div class="row mt-4">
                                <div class="col-md-12 py-3 table-heading" style="background: #fff !important;">
                                    <h6>Next of Kin</h6>
                                </div>
                            </div>
                            <!-- Row  -->
                            <div class="col-md-12 mb-3">
                                <div id="next-of-kin-container">
                                    @foreach ($user->nextOfKins as $index => $nextKins)
                                        <div class="next-of-kin-entry" data-index="{{ $index }}">
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label for="" class="form-label">Name</label>
                                                    <input type="text"
                                                        name="next_of_kin[{{ $index }}][name]"
                                                        class="form-control" placeholder="Name"
                                                        value="{{ old('next_of_kin.' . $index . '.name', $nextKins->name) }}">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="" class="form-label">Relation</label>
                                                    <input type="text"
                                                        name="next_of_kin[{{ $index }}][relation]"
                                                        class="form-control" placeholder="Relation"
                                                        value="{{ old('next_of_kin.' . $index . '.relation', $nextKins->relation) }}">
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label for="" class="form-label">Phone</label>
                                                    <input type="text"
                                                        name="next_of_kin[{{ $index }}][contact]"
                                                        class="form-control" placeholder="Contact"
                                                        value="{{ old('next_of_kin.' . $index . '.contact', $nextKins->contact) }}">
                                                </div>
                                                <div class="col-md-1 text-end">
                                                    <label class="form-label">&nbsp;</label><br>
                                                    <button type="button" class="btn btn-dark delete-next-of-kin"><i
                                                            class="fa fa-trash"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" id="load-more-next-of-kin"
                                    class="btn btn-primary float-end"><i class="fa fa-plus"></i> More Next of
                                    Kin</button>
                            </div>
                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>
                                        Update</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-warning"><i
                                            class="fa fa-arrow-rotate-left"></i> Back</a>
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
            $(document).ready(function() {
                var selectedTeamId = '{{ $user->team_id }}'; // Pre-selected value for Team
                var selectedDepartmentId = '{{ $user->department_id }}'; // Pre-selected value for Department

                // Fetch teams for the pre-selected department
                if (selectedDepartmentId) {
                    fetchTeams(selectedDepartmentId, selectedTeamId);
                    //fetchSubDepartments(selectedDepartmentId); // Fetch sub-departments on load
                }

                $('#department_id').on('select2:select', function(e) {
                    var selectedDepartmentId = $(this).val();

                    // Reset and clear the team dropdown
                    $('#team_id').prop('disabled', false).val('').trigger('change');

                    // Fetch teams based on the selected department
                    fetchTeams(selectedDepartmentId);
                });

                function fetchTeams(departmentId, preSelectedTeamId = null) {
                    $.ajax({
                        url: '{{ route('teams.ajaxTeams') }}', // Replace with the correct endpoint for fetching teams
                        data: {
                            department_id: departmentId
                        },
                        dataType: 'json',
                        success: function(data) {
                            // Create the first empty option
                            var firstOption = new Option('Select Team', '', true, false);

                            // Map team data to Option elements
                            var teamOptions = data.teams.map(function(team) {
                                return new Option(team.name, team.id, false, false);
                            });

                            // Empty the team dropdown and populate with new options
                            $('#team_id').empty().append(firstOption).append(teamOptions).trigger('change');

                            // If a pre-selected value exists, set it
                            if (preSelectedTeamId) {
                                $('#team_id').val(preSelectedTeamId).trigger('change');
                            }
                        },
                        error: function() {
                            alert('Error loading teams.');
                        }
                    });
                }

                // function fetchSubDepartments(departmentId) {
                //     $.ajax({
                //         url: '{{ route('subdepartments.ajaxSubdepartments') }}', // This is the new route
                //         data: {
                //             department_id: departmentId
                //         },
                //         dataType: 'json',
                //         success: function(data) {
                //             // Create the first empty option
                //             var firstOption = new Option('Select Subdepartment', '', true, false);

                //             // Map sub-department data to Option elements
                //             var subDepartmentOptions = data.subdepartments.map(function(subdepartment) {
                //                 return new Option(subdepartment.name, subdepartment.id, false,
                //                     false);
                //             });

                //             // Empty the sub-department dropdown, append the empty option first, then append the sub-department options
                //             $('#subdepartment_id').empty().append(firstOption).append(subDepartmentOptions).prop('disabled', false).trigger('change');
                //             if (selectedSubDepartmentId) {
                //                 $('#subdepartment_id').val(selectedSubDepartmentId).trigger('change');
                //             }
                //         },
                //         error: function() {
                //             alert('Error loading sub-departments.');
                //         }
                //     });
                // }
            });

            document.addEventListener("DOMContentLoaded", function() {
                let nextOfKinIndex = {{ count($user->nextOfKins) }}; // Start from the last index

                // Add new Next of Kin entry
                document.getElementById("load-more-next-of-kin").addEventListener("click", function() {
                    const container = document.getElementById("next-of-kin-container");
                    const newEntry = document.createElement("div");
                    newEntry.className = "next-of-kin-entry";
                    newEntry.dataset.index = nextOfKinIndex;

                    newEntry.innerHTML = `
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="next_of_kin[${nextOfKinIndex}][name]" class="form-label">Name</label>
                            <input type="text" name="next_of_kin[${nextOfKinIndex}][name]" class="form-control" placeholder="Name">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="next_of_kin[${nextOfKinIndex}][relation]" class="form-label">Relation</label>
                            <input type="text" name="next_of_kin[${nextOfKinIndex}][relation]" class="form-control" placeholder="Relation">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="next_of_kin[${nextOfKinIndex}][contact]" class="form-label">Phone</label>
                            <input type="text" name="next_of_kin[${nextOfKinIndex}][contact]" class="form-control" placeholder="Contact">
                        </div>
                        <div class="col-md-1 text-end">
                            <label class="form-label">&nbsp;</label><br>
                            <button type="button" class="btn btn-dark delete-next-of-kin"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                `;

                    container.appendChild(newEntry);
                    nextOfKinIndex++;
                });

                document.getElementById("next-of-kin-container").addEventListener("click", function(event) {
                    if (event.target.classList.contains("delete-next-of-kin")) {
                        const entry = event.target.closest(".next-of-kin-entry");
                        entry.remove();
                    }
                });
            });

            function formatNIC(input) {
                let value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
                if (value.length <= 5) {
                    input.value = value;
                } else if (value.length <= 12) {
                    input.value = value.slice(0, 5) + '-' + value.slice(5);
                } else {
                    input.value = value.slice(0, 5) + '-' + value.slice(5, 12) + '-' + value.slice(12, 13);
                }
            }
        </script>
    @endsection
</x-app-layout>
