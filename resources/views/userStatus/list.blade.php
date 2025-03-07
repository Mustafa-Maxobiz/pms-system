<x-app-layout>
    <!-- All User Status Content -->
    <div id="all-user-status" class="my-3 split">
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
                            <h6>All User Status
                                <a href="{{ route('user-status.create') }}" class="btn-link btn btn-dark float-end">
                                    <i class="fa fa-plus"></i> Add New
                                </a>
                            </h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="user-statusTable" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>Id</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Order By</th>
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

    <!-- View User Status Details Modal -->
    <div class="modal fade" id="viewUserStatusModal" tabindex="-1" aria-labelledby="viewUserStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewUserStatusModalLabel">User Status Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body user-status-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Content -->
                    <div id="userStatusDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="title" class="form-label">Title</label>
                                <p id="title"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <p id="description"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="order_by" class="form-label">Order By</label>
                                <p id="order_by"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="created" class="form-label">Created</label>
                                <p id="created"></p>
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
                $('#user-statusTable').on('click', '.delete-user-status-btn', function(e) {
                    e.preventDefault();

                    if (confirm('Are you sure you want to delete this User Status?')) {
                        const userStatusId = $(this).data('id');
                        const deleteUrl = `./user-status/${userStatusId}`;
                        const table = $('#user-statusTable').DataTable();
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
                                console.error('Error deleting user status:', xhr.responseText);
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
                    $('#all-user-status').prepend(alertBox);
                    setTimeout(() => {
                        $('.alert').alert('close');
                    }, 5000);
                }
            });

            $('#user-statusTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                stateSave: true,
                ajax: {
                    url: "{{ route('user-status.index') }}",
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
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'order_by',
                        name: 'order_by'
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

            function toggleLoading(modal, isLoading) {
                modal.find('#loadingSpinner').toggle(isLoading);
                modal.find('#userStatusDetails').toggle(!isLoading);
            }
            $('#user-statusTable').on('click', '[data-bs-toggle="modal"]', function() {
                const userStatusId = $(this).data('id');
                const modal = $('#viewUserStatusModal');
                toggleLoading(modal, true);
                modal.modal('show');
                $.ajax({
                    url: `./user-status/${userStatusId}`,
                    type: 'GET',
                    success: function(data) {
                        modal.find('#title').text(data.title || 'N/A');
                        modal.find('#description').text(data.description || 'N/A');
                        modal.find('#order_by').text(data.order_by || 'N/A');
                        modal.find('#created').text(data.created_at ? moment(data.created_at).format(
                            'D MMM, YYYY') : 'N/A');
                        toggleLoading(modal, false);
                    },
                    error: function(xhr) {
                        console.error('Error fetching user status details:', xhr);
                        alert('Failed to fetch user status details. Please try again.');
                        toggleLoading(modal, false);
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
