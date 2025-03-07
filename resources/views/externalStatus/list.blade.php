<x-app-layout>
    <!-- All External Status Content -->
    <div id="all-external-status" class="my-3 split">
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
                            <h6>All External Status
                                <a href="{{ route('external-status.create') }}" class="btn-link btn btn-dark float-end">
                                    <i class="fa fa-plus"></i> Add New
                                </a>
                            </h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="external-statusTable" width="100%"
                                    cellspacing="0">
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

    <!-- View External Status Details Modal -->
    <div class="modal fade" id="viewExternalStatusModal" tabindex="-1" aria-labelledby="viewExternalStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewExternalStatusModalLabel">External Status Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body external-status-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Content -->
                    <div id="externalStatusDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="title" class="form-label">Title</label>
                                <p id="external_title"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <p id="external_description"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="order_by" class="form-label">Order By</label>
                                <p id="external_order_by"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="created" class="form-label">Created</label>
                                <p id="external_created"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $('#external-statusTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                stateSave: true,
                ajax: {
                    url: "{{ route('external-status.index') }}",
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
            // AJAX delete functionality for External Status
            $('#external-statusTable').on('click', '.delete-external-status-btn', function(e) {
                e.preventDefault();

                if (confirm('Are you sure you want to delete this external status?')) {
                    const externalStatusId = $(this).data('id');
                    const deleteUrl = `./external-status/${externalStatusId}`;
                    const table = $('#external-statusTable').DataTable();
                    const row = $(this).closest('tr');

                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function(response) {
                            if (response.success) {
                                showExternalStatusMessage('success', response.message);
                                table.row(row).remove().draw(false);
                            } else {
                                showExternalStatusMessage('danger', 'Error: ' + response.message);
                            }
                        },
                        error: function(xhr) {
                            console.error('Error deleting external status:', xhr.responseText);
                            showExternalStatusMessage('danger', 'An error occurred. Please try again.');
                        }
                    });
                }
            });

            // Function to display messages
            function showExternalStatusMessage(type, message) {
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
                $('#all-external-status').prepend(alertBox);
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }

            // Function to toggle the loading spinner and content in modal
            function toggleLoading(modal, isLoading) {
                modal.find('#loadingSpinner').toggle(isLoading);
                modal.find('#externalStatusDetails').toggle(!isLoading);
            }

            $('#external-statusTable').on('click', '[data-bs-toggle="modal"]', function() {
                const externalStatusId = $(this).data('id');
                const modal = $('#viewExternalStatusModal');
                toggleLoading(modal, true);
                modal.modal('show');
                $.ajax({
                    url: `./external-status/${externalStatusId}`,
                    type: 'GET',
                    success: function(data) {
                        modal.find('#external_title').text(data.title || 'N/A');
                        modal.find('#external_description').text(data.description || 'N/A');
                        modal.find('#external_order_by').text(data.order_by || 'N/A');
                        modal.find('#external_created').text(data.created_at ? moment(data.created_at)
                            .format('D MMM, YYYY') : 'N/A');
                        toggleLoading(modal, false);
                    },
                    error: function(xhr) {
                        console.error('Error fetching external status details:', xhr);
                        alert('Failed to fetch external status details. Please try again.');
                        toggleLoading(modal, false);
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
