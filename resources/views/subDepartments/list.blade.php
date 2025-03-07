<x-app-layout>
    <!-- All Sub-Departments Content -->
    <div id="all-sub-departments" class="my-3 split">
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
                                All Sub-Departments
                                <!-- Button to Add New Sub-Department -->
                                @can('Add New Sub-Department')
                                    <a href="{{ route('subdepartments.create') }}" class="btn-link btn btn-dark float-end">
                                        <i class="fa fa-plus"></i> Add New
                                    </a>
                                @endcan

                            </h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="subDepartmentsTable" width="100%"
                                    cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>ID</th>
                                            <th>Parent Department</th>
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
    <!-- View Sub-Department Modal -->
    <div class="modal fade" id="viewSubDepartmentModal" tabindex="-1" aria-labelledby="viewSubDepartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewSubDepartmentModalLabel">Sub-Department Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body sub-department-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Sub-Department Details -->
                    <div id="subDepartmentDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="subDepartmentName" class="form-label">Sub-Department Name:</label>
                                <p id="subDepartmentName"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="subDepartmentId" class="form-label">Sub-Department ID:</label>
                                <p id="subDepartmentId"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="parentDepartment" class="form-label">Parent Department:</label>
                                <p id="parentDepartment"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="authorName" class="form-label">Author:</label>
                                <p id="authorName"></p>
                            </div>
                            <div class="col-md-12 col-12 mb-3">
                                <label for="description" class="form-label">Description:</label>
                                <p id="description"></p>
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
            $('#subDepartmentsTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                stateSave: true,
                ajax: {
                    url: "{{ route('subdepartments.list') }}", // Updated to use the correct route for listing sub-departments
                    type: 'GET',
                    data: function(d) {
                        d.search = $('input[type="search"]').val();
                        d.start = d.start;
                        d.length = d.length;
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
                        data: 'department.name',
                        name: 'department.name',
                        defaultContent: 'N/A'
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
                        name: 'author'
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

            $(document).ready(function() {
                $('#subDepartmentsTable').on('click', '.delete-sub-department-btn', function(e) {
                    e.preventDefault();
                    const subDepartmentId = $(this).data('id');

                    if (confirm("Are you sure you want to delete this sub-department?")) {
                        const deleteUrl = `./sub-departments/${subDepartmentId}`;
                        const table = $('#subDepartmentsTable').DataTable();
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
                                console.error('Error deleting sub-department:', xhr.responseText);
                                showMessage('danger', 'An error occurred. Please try again.');
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
                    $('#all-sub-departments').prepend(alertBox);
                    setTimeout(() => {
                        $('.alert').alert('close');
                    }, 5000);
                }
            });


            $('#subDepartmentsTable').on('click', '.view-sub-department-btn', function() {
                const subDepartmentId = $(this).data('id');
                const modal = $('#viewSubDepartmentModal');

                modal.find('#loadingSpinner').show();
                modal.find('#subDepartmentDetails').hide();
                modal.modal('show');

                $.ajax({
                    url: `./sub-departments/${subDepartmentId}`, // Updated the URL for viewing sub-department
                    type: 'GET',
                    success: function(data) {
                        modal.find('#subDepartmentName').text(data.name || 'N/A');
                        modal.find('#subDepartmentId').text(data.id || 'N/A');
                        modal.find('#parentDepartment').text(data.department.name || 'N/A');
                        modal.find('#authorName').text(data.author.name || 'N/A');
                        modal.find('#description').text(data.description || 'N/A');
                        modal.find('#createdAt').text(moment(data.created_at).format('D MMM, YYYY') ||
                            'N/A');
                        modal.find('#loadingSpinner').hide();
                        modal.find('#subDepartmentDetails').show();
                    },
                    error: function(xhr) {
                        console.error("Error Fetching Data:", xhr);
                        alert('Failed to fetch sub-department details. Please try again.');
                        modal.modal('hide');
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
