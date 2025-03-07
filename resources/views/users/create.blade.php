<x-app-layout>
    <div id="add-users" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Add User</h6>
                        </div>
                        <form method="POST" action="{{ route('users.store') }}" style="padding: 30px;"
                            enctype="multipart/form-data">
                            @csrf
                            <!-- Row  -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="name" class="form-label">First Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" autocomplete="on"
                                        placeholder="First Name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="last_name" class="form-label">Last Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                        id="last_name" name="last_name" value="{{ old('last_name') }}" autocomplete="on"
                                        placeholder="Last Name" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="father_name" class="form-label">Father Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('father_name') is-invalid @enderror" id="father_name"
                                        name="father_name" value="{{ old('father_name') }}" autocomplete="on"
                                        placeholder="Father Name" required>
                                    @error('father_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">Gender <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('gender') is-invalid @enderror" id="gender"
                                        name="gender" required>
                                        <option value="">Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female
                                        </option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="dob" class="form-label">D.O.B <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('dob') is-invalid @enderror"
                                        id="dob" name="dob" value="{{ old('dob') }}" required>
                                    @error('dob')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="nic" class="form-label">NIC <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nic') is-invalid @enderror"
                                        id="nic" name="nic" value="{{ old('nic') }}" autocomplete="off"
                                        placeholder="00000-0000000-0" maxlength="15" oninput="formatNIC(this)" required>
                                    @error('nic')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="address" class="form-label">Address <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                        id="address" name="address" value="{{ old('address') }}"
                                        placeholder="Address" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">City <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city') }}"
                                        placeholder="City" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="country" class="form-label">Country <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('country') is-invalid @enderror"
                                        id="country" name="country" value="{{ old('country') }}"
                                        placeholder="Country" required>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="mobile" class="form-label">Mobile <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror"
                                        id="mobile" name="mobile" value="{{ old('mobile') }}"
                                        placeholder="Mobile" pattern="^03\d{9}$" maxlength="11"
                                        title="Mobile number must start with 03 and be 11 digits long" required>
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone') }}"
                                        placeholder="Phone" pattern="^03\d{9}$" maxlength="11"
                                        title="Phone number must start with 03 and be 11 digits long">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="department_id" class="form-label">Department <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('department_id') is-invalid @enderror"
                                        id="department_id" name="department_id" required>
                                        <option value="" selected>Department</option>
                                        @foreach ($departments as $department_id)
                                            <option value="{{ $department_id->id }}"
                                                {{ old('department_id') == $department_id->id ? 'selected' : '' }}>
                                                {{ $department_id->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="team" class="form-label">Team <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('team_id') is-invalid @enderror"
                                        id="team_id" name="team_id" required="">
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
                                        id="personal_email" name="email" value="{{ old('email') }}"
                                        placeholder="Personal Email" required>
                                    @error('personal_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="work_email" class="form-label">Work Email (for Login) <span
                                            class="text-danger">*</span></label>
                                    <input type="email"
                                        class="form-control @error('work_email') is-invalid @enderror"
                                        id="work_email" name="work_email" value="{{ old('work_email') }}"
                                        placeholder="Work Email" required>
                                    @error('work_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="username" class="form-label">Username (for Login) <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('username') is-invalid @enderror"
                                        id="username" name="username" value="{{ old('username') }}"
                                        placeholder="Username" required>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="password" class="form-label">Password (min 8 characters) <span
                                            class="text-danger">*</span></label>
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror" id="password"
                                        name="password" placeholder="Password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="confirmed" class="form-label">Confirm Password (min 8 characters)
                                        <span class="text-danger">*</span></label>
                                    <input type="password"
                                        class="form-control @error('confirmed') is-invalid @enderror" id="confirmed"
                                        name="confirmed" placeholder="Confirm Password" required>
                                    @error('confirmed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Profile Picture -->
                                <div class="row p-0 m-0">
                                    <div class="col-md-6 mb-3">
                                        <label for="profile_picture" class="form-label">Profile Picture</label>
                                        <input type="file"
                                            class="form-control @error('profile_picture') is-invalid @enderror"
                                            id="profile_picture" name="profile_picture"
                                            accept="image/png, image/jpeg, image/webp">
                                        <small class="text-muted">Allowed formats: JPG, PNG, WEBP. Max size:
                                            1MB</small>
                                        @error('profile_picture')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="status" class="form-label">Status</label> 
                                        <select class="form-control @error('status') is-invalid @enderror" id="status"
                                            name="status">
                                            <option value="1" {{ old('active') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('inactive') == '0' ? 'selected' : '' }}>Inactive</option>
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="is_visible" class="form-label">Is Visible</label>
                                            <select class="form-control @error('is_visible') is-invalid @enderror" id="is_visible"
                                                name="is_visible">
                                                <option value="1" {{ old('is_visible') == '1' ? 'selected' : '' }}>Yes</option>
                                                <option value="0" {{ old('is_visible') == '0' ? 'selected' : '' }}>No</option>
                                                </option>
                                            </select>
                                        @error('is_visible')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Access Level <span
                                        class="text-danger">*</span></label><br>
                                @if ($roles->isNotEmpty())
                                    @foreach ($roles as $key => $role)
                                        <div class="form-check-inline">
                                            <input type="checkbox" id="role-{{ $role->id }}" name="role[]"
                                                value="{{ $role->name }}">
                                            <label class="form-check-label"
                                                for="role-{{ $role->id }}">{{ $role->name }}</label>
                                        </div>
                                    @endforeach
                                @endif
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Row  -->
                            <div class="row mt-4">
                                <div class="col-md-12 py-3 table-heading" style="background: #fff !important;">
                                    <h6>Next of Kin</h6>
                                </div>
                            </div>

                            <!-- Row  -->
                            <div class="col-md-12 mb-3">
                                <div id="next-of-kin-container">
                                    <div class="next-of-kin-entry" data-index="0">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="" class="form-label">Name </label>
                                                <input type="text" name="next_of_kin[0][name]"
                                                    class="form-control" placeholder="Name">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="" class="form-label">Relation </label>
                                                <input type="text" name="next_of_kin[0][relation]"
                                                    class="form-control" placeholder="Relation">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="" class="form-label">Phone </label>
                                                <input type="text" name="next_of_kin[0][contact]"
                                                    class="form-control" placeholder="Contact">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="load-more-next-of-kin"
                                    class="btn btn-primary float-end"><i class="fa fa-plus"></i> More Next of
                                    Kin</button>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>
                                        Save</button>
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
                $('#team_id').prop('disabled', true);

                $('#department_id').on('select2:select', function(e) {
                    var selectedDepartmentId = $(this).val();

                    // Reset and clear the team dropdown
                    $('#team_id').prop('disabled', false).val('').trigger('change'); // Reset team

                    // Fetch teams based on the selected department
                    $.ajax({
                        url: '{{ route('teams.ajaxTeams') }}', // Replace with the correct endpoint for fetching teams
                        data: {
                            department_id: selectedDepartmentId
                        },
                        dataType: 'json',
                        success: function(data) {
                            // Create the first empty option
                            var firstOption = new Option('Select Team', '', true, false);

                            // Map team data to Option elements
                            var teamOptions = data.teams.map(function(team) {
                                return new Option(team.name, team.id, false, false);
                            });

                            // Empty the team dropdown, append the empty option first, then append the team options
                            $('#team_id').empty().append(firstOption).append(teamOptions).trigger(
                                'change');
                        },
                        error: function() {
                            alert('Error loading teams.');
                        }
                    });
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                let nextOfKinIndex = 1; // Start from 1 since 0 is already used

                // Add new Next of Kin entry
                document.getElementById("load-more-next-of-kin").addEventListener("click", function() {
                    const container = document.getElementById("next-of-kin-container");

                    // Create a new Next of Kin entry
                    const newEntry = document.createElement("div");
                    newEntry.className = "next-of-kin-entry";
                    newEntry.dataset.index = nextOfKinIndex;
                    newEntry.innerHTML = `
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <input type="text" name="next_of_kin[${nextOfKinIndex}][name]" class="form-control" placeholder="Name">
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="text" name="next_of_kin[${nextOfKinIndex}][relation]" class="form-control" placeholder="Relation">
                    </div>
                    <div class="col-md-3     mb-3">
                        <input type="text" name="next_of_kin[${nextOfKinIndex}][contact]" class="form-control" placeholder="Contact">
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="button" class="btn btn-dark delete-next-of-kin"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            `;

                    // Append the new entry to the container
                    container.appendChild(newEntry);

                    // Increment index for next entry
                    nextOfKinIndex++;
                });

                // Delete a Next of Kin entry
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
