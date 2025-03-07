<x-app-layout>
    <!-- All users Content -->
    <div id="all-users" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">

                    @if (Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    @if (Session::has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::get('error') }}
                        </div>
                    @endif

                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header p-3 table-heading">
                            <h6>All Users
                                @can('Add New user')
                                    <a href="{{ route('users.create') }}" class="btn-link btn btn-dark float-end"><i
                                            class="fa fa-plus"></i> Add New</a>
                                @endcan
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="departmentFilter" class="form-label">Department:</label>
                                    <select class="form-select select2" id="departmentFilter">
                                        <option value="">All</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="teamFilter" class="form-label">Teams:</label>
                                    <select class="form-select select2" id="teamFilter">
                                        <option value="">All</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="roleFilter" class="form-label">Roles:</label>
                                    <select class="form-select select2" id="roleFilter">
                                        <option value="">All</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="usersTable" width="100%" cellspacing="0">
                                        <thead class="table-head">
                                            <tr class="table-light">
                                                <th>Id</th>
                                                <th>Profile Picture</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>UserName</th>
                                                <th>Roles</th>
                                                <th>Created</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View User Details Modal -->
        <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body user-details-modal">
                        <!-- Loading Spinner -->
                        <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <!-- Content -->
                        <div id="userDetails" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <p id="first_name"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <p id="last_name"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="father_name" class="form-label">Father's Name</label>
                                    <p id="father_name"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <p id="gender"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <p id="dob"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="nic" class="form-label">NIC</label>
                                    <p id="nic"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <p id="address"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <p id="city"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <p id="country"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="mobile" class="form-label">Mobile</label>
                                    <p id="mobile"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <p id="phone"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="department" class="form-label">Department</label>
                                    <p id="department"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="team" class="form-label">Team</label>
                                    <p id="team"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="personal_email" class="form-label">Personal Email</label>
                                    <p id="personal_email"></p>
                                </div>
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="work_email" class="form-label">Work Email</label>
                                    <p id="work_email"></p>
                                </div>
                                <!-- Access Level -->
                                <div class="col-md-6 col-12 mb-3">
                                    <label for="access_level" class="form-label">Role</label>
                                    <p id="access_level"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Department User Details Modal -->
        <div class="modal fade" id="viewDepartmentUserModal" tabindex="-1" aria-labelledby="viewDepartmentUserModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewDepartmentUserModalLabel">CSR User - Department Access</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body user-details-modal">
                        <!-- Loading Spinner -->
                        <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <!-- Content -->
                        <div id="userDetails" style="display: none;">
                            <div class="row">
                                <div class="col-md-12 col-12 mb-3">
                                    <label for="first_name" class="form-label">Name</label>
                                    <p id="first_name"></p>
                                </div>
                                <div class="col-md-12 col-12 mb-3">
                                    <label for="departments_access" class="form-label">Department Access</label><br>
                                    @foreach ($departments as $department)
                                        <div class="form-check-inline">
                                            <input type="checkbox" id="department-{{ $department->id }}" 
                                                name="departments_access[]" value="{{ $department->id }}">
                                            <label class="form-check-label" for="department-{{ $department->id }}">{{ $department->name }}</label>
                                        </div>
                                    @endforeach

                                </div>
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @section('scripts')
            <script>
                $(document).ready(function() {
                    const usersTable = $('#usersTable').DataTable({
                        processing: true,
                        serverSide: true,
                        paging: true,
                        stateSave: true,
                        ajax: {
                            url: "{{ route('users.index') }}",
                            type: 'GET',
                            data: function(d) {
                                d.department = $('#departmentFilter').val();
                                d.team = $('#teamFilter').val();
                                d.role = $('#roleFilter').val();
                                d.search = $('input[type="search"]').val(); // Include search term
                            },
                            error: function(xhr) {
                                if (xhr.status === 401) {
                                    alert('Your session has expired. Redirecting to the login page...');
                                    window.location.href = "{{ route('login') }}";
                                } else {
                                    console.error('DataTables AJAX error:', xhr.responseText);
                                    alert('An error occurred while loading the data. Please try again.');
                                }
                            }
                        },
                        columns: [{
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'profile_picture',
                                name: 'profile_picture',
                                render: function(data) {
                                   if(data != null){
                                    return `<img
                                                src="${'storage/app/public/' + data }"
                                                alt="Profile"
                                                onerror="this.src='./public/no-image.png'"
                                                class="rounded-circle me-3"
                                                width="40"
                                                height="40"
                                            />`;
                                    }else{
                                    return `<img
                                                src="public/no-image.png?1"
                                                alt="Profile"
                                                onerror="this.src='./public/no-image.png'"
                                                class="rounded-circle me-3"
                                                width="40"
                                                height="40"
                                            />`;
                                    }
                                }
                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'email',
                                name: 'email'
                            },
                            {
                                data: 'username',
                                name: 'username'
                            },
                            {
                                data: 'roles',
                                name: 'roles',
                                render: function(data) {
                                    return data.map(role => role.name).join(', ');
                                }
                            },
                            {
                                data: 'created_at',
                                name: 'created_at',
                                render: function(data) {
                                    return moment(data).format('D MMM, YYYY');
                                }
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ],
                        order: [
                            [0, 'desc']
                        ],
                        lengthMenu: [10, 25, 50, 100],
                        pageLength: 10,
                        dom: 'lBfrtip',
                        buttons: [{
                                extend: 'copy',
                                className: 'btn btn-primary'
                            },
                            {
                                extend: 'csv',
                                className: 'btn btn-success'
                            },
                            {
                                extend: 'excel',
                                className: 'btn btn-success'
                            },
                            {
                                extend: 'pdf',
                                className: 'btn btn-danger'
                            },
                            {
                                extend: 'print',
                                className: 'btn btn-info'
                            }
                        ]
                    });

                    // Filter event listeners
                    $('#departmentFilter, #teamFilter, #roleFilter').on('change', function() {
                        usersTable.draw();
                    });
                });
                // Delete User Button
                $(document).on('click', '.delete-user-btn', function(e) {
                    if (confirm('Are you sure you want to delete this user?')) {
                        e.preventDefault();
                        const userId = $(this).data('id');
                        const deleteUrl = `./users/${userId}`;
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(response) {
                                if (response.success) {
                                    showMessage('success', response.message);
                                    const table = $('#usersTable').DataTable();
                                    const row = $(`#user-row-${userId}`);
                                    table.row(row).remove().draw(false);
                                } else {
                                    showMessage('danger', 'Error: ' + response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                showMessage('danger', 'Error: ' + (xhr.responseJSON?.message ||
                                    'Failed to delete the user'));
                            }
                        });
                    }
                });

                function showMessage(type, message) {
                    $('html, body').animate({
                        scrollTop: $('.container-fluid').offset().top - 20
                    }, 100);
                    const alertBox = `
                            <div class="container-fluid">
                                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                    ${message}
                                </div>
                            </div>
                            `;

                    $('#all-users').prepend(alertBox);
                    setTimeout(() => {
                        $('.alert').alert('close');
                    }, 5000);
                }

                // View User Details (modal)
                $('#usersTable').on('click', '[data-bs-toggle="modal-user"]', function() {
                    const userId = $(this).data('id');
                    const modal = $('#viewUserModal');
                    toggleLoading(modal, true);
                    modal.modal('show');
                    $.ajax({
                        url: `./users/${userId}`,
                        type: 'GET',
                        success: function(data) {
                            modal.find('#first_name').text(data.name || 'N/A');
                            modal.find('#last_name').text(data.last_name || 'N/A');
                            modal.find('#father_name').text(data.father_name || 'N/A');
                            modal.find('#gender').text(data.gender || 'N/A');
                            modal.find('#dob').text(data.dob ? moment(data.dob).format('D MMM, YYYY') : 'N/A');
                            modal.find('#nic').text(data.nic || 'N/A');
                            modal.find('#address').text(data.address || 'N/A');
                            modal.find('#city').text(data.city || 'N/A');
                            modal.find('#country').text(data.country || 'N/A');
                            modal.find('#mobile').text(data.mobile || 'N/A');
                            modal.find('#phone').text(data.phone || 'N/A');
                            modal.find('#department').text(data.department ? data.department.name : 'N/A');
                            modal.find('#team').text(data.team ? data.team.name : 'N/A');
                            modal.find('#personal_email').text(data.email || 'N/A');
                            modal.find('#work_email').text(data.work_email || 'N/A');
                            modal.find('#access_level').text(data.roles ? data.roles.map(role => role.name)
                                .join(', ') : 'N/A');
                            toggleLoading(modal, false);
                        },
                        error: function(xhr) {
                            console.error('Error fetching user details:', xhr);
                            alert('Failed to fetch user details. Please try again.');
                            toggleLoading(modal, false);
                        }
                    });
                });
                
                // View Department User Details (modal)
                $('#usersTable').on('click', '[data-bs-toggle="modal-department"]', function () {
                    const userId = $(this).data('id');
                    const modal = $('#viewDepartmentUserModal');
                    toggleLoading(modal, true);
                    modal.modal('show');
                    // Fetch user details via AJAX
                    $.ajax({
                        url: `./users/${userId}`,
                        type: 'GET',
                        success: function (data) {
                            modal.find('#first_name').text(data.name || 'N/A');
                            // Uncheck all checkboxes first
                            modal.find('input[name="departments_access[]"]').prop('checked', false);

                            if (data.user_departments) {
                                const userDepartmentIds = data.user_departments.split(','); // Convert "7,8,6" to ['7', '8', '6']
                                // Check checkboxes that match the user's departments
                                userDepartmentIds.forEach(id => {
                                    modal.find(`#department-${id}`).prop('checked', true);
                                });
                            }

                            modal.find('#userDetails').show();
                            toggleLoading(modal, false);
                        },
                        error: function (xhr) {
                            console.error('Error fetching user details:', xhr);
                            alert('Failed to fetch user details. Please try again.');
                            toggleLoading(modal, false);
                        }
                    });

                    // Handle Save button click
                    modal.find('.btn-success').off('click').on('click', function () {
                        const selectedDepartments = modal.find('input[name="departments_access[]"]:checked')
                            .map(function () { return $(this).val(); })
                            .get();

                        $.ajax({
                            url: `./users/${userId}/update-departments`,
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                departments: selectedDepartments
                            },
                            success: function (response) {
                                alert('Department access updated successfully.');
                                modal.modal('hide');
                            },
                            error: function (xhr) {
                                console.error('Error updating department access:', xhr);
                                alert('Failed to update department access. Please try again.');
                            }
                        });
                    });
                });



                function toggleLoading(modal, isLoading) {
                    modal.find('#loadingSpinner').toggle(isLoading);
                    modal.find('#userDetails').toggle(!isLoading);
                }
            </script>
        @endsection
</x-app-layout>
