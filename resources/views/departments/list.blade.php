<x-app-layout>
    <!-- All departments Content -->
    <div id="all-departments" class="my-3 split">
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
                            <h6>
                                All Departments
                                @can('Add New Department')
                                    <a href="{{ route('departments.create') }}" class="btn-link btn btn-dark float-end">
                                        <i class="fa fa-plus"></i> Add New
                                    </a>
                                @endcan
                                @can('View Subdepartments')
                                    <a href="{{ route('subdepartments.list') }}"
                                        class="btn-link btn btn-primary float-end me-2">
                                        <i class="fa fa-list"></i> View Subdepartments
                                    </a>
                                @endcan
                            </h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="departmentsTable" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Author</th>
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
    <!-- View Department Modal -->
    <div class="modal fade" id="viewDepartmentModal" tabindex="-1" aria-labelledby="viewDepartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDepartmentModalLabel">Department Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body department-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Department Details -->
                    <div id="departmentDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="departmentName" class="form-label">Department Name:</label>
                                <p id="departmentName"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="departmentId" class="form-label">Department ID:</label>
                                <p id="departmentId"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Description:</label>
                                <p id="description"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="authorName" class="form-label">Author:</label>
                                <p id="authorName"></p>
                            </div>
                            <div class="col-md-12 col-12 mb-3">
                                <label for="createdAt" class="form-label">Created At:</label>
                                <p id="createdAt"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $('#departmentsTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                stateSave: true,
                ajax: {
                    url: "{{ route('departments.index') }}",
                    type: 'GET',
                    data: function(d) {
                        d.search = $('input[type="search"]').val();
                        d.start = d.start;
                        d.length = d.length;
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            // Redirect to login page if unauthorized
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'author',
                        name: 'author',
                        render: function(data) {
                            return data.name; // Fallback if permissions is not an array
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
                ], // Default sorting by the first column (ID)
                "lengthMenu": [10, 25, 50, 100], // Page length options for the dropdown
                "pageLength": 10, // Default page length
                dom: 'lBfrtip', // Add 'l' to show the page length dropdown
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

            $(document).ready(function() {
                $('#departmentsTable').on('click', '.delete-department-btn', function(e) {
                    e.preventDefault();
                    const departmentId = $(this).data('id');

                    if (confirm("Are you sure you want to delete this department?")) {
                        const deleteUrl = `./departments/${departmentId}`;
                        const table = $('#departmentsTable').DataTable();
                        const row = $(this).closest('tr');

                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(response) {
                                if (response.success) {
                                    showMessage('success', response.message);
                                    table.row(row).remove().draw(false);
                                } else {
                                    showMessage('danger', 'Error: ' + response.message);
                                }
                            },
                            error: function(xhr) {
                                console.error('Error deleting department:', xhr.responseText);
                                showMessage('danger', 'An error occurred. Please try again.');
                            }
                        });
                    }
                });

                // Show message function
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
                    $('#all-departments').prepend(alertBox);
                    setTimeout(() => {
                        $('.alert').alert('close');
                    }, 5000);
                }
            });

            $('#departmentsTable').on('click', '.view-department-btn', function() {
                const departmentId = $(this).data('id');
                const modal = $('#viewDepartmentModal');

                modal.find('#loadingSpinner').show();
                modal.find('#departmentDetails').hide();
                modal.modal('show');

                $.ajax({
                    url: `./departments/${departmentId}`,
                    type: 'GET',
                    success: function(data) {
                        modal.find('#departmentName').text(data.name || 'N/A');
                        modal.find('#departmentId').text(data.id || 'N/A');
                        modal.find('#description').text(data.description || 'N/A');
                        modal.find('#authorName').text(data.author.name || 'N/A');
                        modal.find('#createdAt').text(moment(data.created_at).format('D MMM, YYYY') ||
                            'N/A');
                        modal.find('#loadingSpinner').hide();
                        modal.find('#departmentDetails').show();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error Fetching Data:", status, error);
                        alert('Failed to fetch department details. Please try again.');
                        modal.modal('hide');
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
