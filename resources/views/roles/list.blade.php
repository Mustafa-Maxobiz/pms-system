<x-app-layout>
    <!-- All Roles Content -->
    <div id="all-roles" class="my-3 split">
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
                            <h6>All Roles
                                @can('Add New Role')
                                <a href="{{ route('roles.create') }}" class="btn-link btn btn-dark float-end"><i
                                        class="fa fa-plus"></i> Add New</a>
                                @endcan
                            </h6>
                        </div>
                        <div class="card-body table-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="rolesTable" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Permissions</th>
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

    <!-- View Role Details Modal -->
    <div class="modal fade" id="viewRoleModal" tabindex="-1" aria-labelledby="viewRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRoleModalLabel">Role Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body role-details-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Content -->
                    <div id="roleDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="role_name" class="form-label">Role Name</label>
                                <p id="role_name"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="created_at" class="form-label">Created At</label>
                                <p id="created_at"></p>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="permissions" class="form-label">Permissions</label>
                                <p id="permissions"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
    $('#rolesTable').DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        stateSave: true,
        ajax: {
            url: "{{ route('roles.index') }}",
            type: 'GET',
            data: function(d) {
                d.search = $('input[type="search"]').val();
                d.start = d.start;
                d.length = d.length;
            }
        },
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'permissions',
                name: 'permissions',
                render: function(data) {
                    if (Array.isArray(data)) {
                        return data.map(permission => permission.name).join(', ');
                    }
                    return data;
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
        "lengthMenu": [10, 25, 50, 100],
        "pageLength": 10,
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

    $('#rolesTable').on('click', '[data-bs-toggle="modal"]', function() {
        const roleId = $(this).data('id'); // Get the role ID
        const modal = $('#viewRoleModal');

        // Show the modal and the loading spinner
        toggleLoading(modal, true);
        modal.modal('show');

        // Make the AJAX request
        $.ajax({
            url: `./roles/${roleId}`, // Replace `./roles` with the correct URL if needed
            type: 'GET',
            success: function(data) {
                // Log the data to the console for debugging
                console.log('Fetched Role Data:', data);

                // Populate modal fields with the fetched data
                modal.find('#role_name').text(data.name || 'N/A');
                modal.find('#created_at').text(data.created_at ? moment(data.created_at).format(
                    'D MMM, YYYY') : 'N/A');
                modal.find('#permissions').text(data.permissions ? data.permissions.map(p => p.name)
                    .join(', ') : 'N/A');

                // Hide the spinner and show the modal content
                toggleLoading(modal, false);
            },
            error: function(xhr) {
                // Log the error to the console
                console.error('Error fetching role details:', xhr);
                alert('Failed to fetch role details. Please try again.');

                // Hide the spinner
                toggleLoading(modal, false);
            }
        });
    });

    function toggleLoading(modal, isLoading) {
        modal.find('#loadingSpinner').toggle(isLoading);
        modal.find('#roleDetails').toggle(!isLoading);
    }

    // AJAX delete functionality for roles
    $('#rolesTable').on('click', '.delete-role-btn', function(e) {
        e.preventDefault();

        if (confirm('Are you sure you want to delete this role?')) {
            const roleId = $(this).data('id'); // ID of the role
            const deleteUrl = `./roles/${roleId}`; // Your delete route
            const table = $('#rolesTable').DataTable();
            const row = $(this).closest('tr'); // Get the closest row for deletion

            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response.success) {
                        showMessage('success', response.message);
                        table.row(row).remove().draw(false); // Remove the row without a full reload
                    } else {
                        showMessage('danger', 'Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    console.error('Error deleting role:', xhr.responseText);
                    showMessage('danger', 'An error occurred. Please try again.');
                }
            });
        }
    });


    // Show message function
    function showMessage(type, message) {
        $('html, body').animate({
            scrollTop: $('.container-fluid').offset().top - 20 // Adjust offset for smooth scrolling
        }, 100);
        const alertBox = `
            <div class="container-fluid">
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                </div>
            </div>
        `;
        $('#all-roles').prepend(alertBox);
        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
    }
    </script>

    @endsection
</x-app-layout>
