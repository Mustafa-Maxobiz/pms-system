<x-app-layout>
    <!-- All Clients Content -->
    <div id="all-clients" class="my-3 split">
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
                                Client Management
                                @can('Add New Client')
                                    <a href="{{ route('clients.create') }}" class="btn-link btn btn-dark float-end">
                                        <i class="fa fa-plus"></i> Add New
                                    </a>
                                @endcan
                            </h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="clientsTable" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>ID</th>
                                            <th>Client Name</th>
                                            <th>Username</th>
                                            <th>Source</th>
                                            <th>Phone</th>
                                            <th>Mobile</th>
                                            <th>Email</th>
                                            <th>Country</th>
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
    <!-- View Client Details Modal -->
    <div class="modal fade" id="viewClientModal" tabindex="-1" aria-labelledby="viewClientModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewClientModalLabel">Client Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body client-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Content -->
                    <div id="clientDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="client_name" class="form-label">Client Name</label>
                                <p id="client_name"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="client_username" class="form-label">Username</label>
                                <p id="client_username"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="source" class="form-label">Source</label>
                                <p id="source"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="client_phone" class="form-label">Phone</label>
                                <p id="client_phone"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="client_mobile" class="form-label">Mobile</label>
                                <p id="client_mobile"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="client_email" class="form-label">Email</label>
                                <p id="client_email"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="client_country" class="form-label">Country</label>
                                <p id="client_country"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="author" class="form-label">Author</label>
                                <p id="author"></p>
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
            $('#clientsTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                stateSave: true,
                ajax: {
                    url: "{{ route('clients.index') }}",
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
                        data: 'client_name',
                        name: 'client_name'
                    },
                    {
                        data: 'client_username',
                        name: 'client_username'
                    },
                    {
                        data: 'source',
                        name: 'source',
                        render: function(data) {
                            return data != null ? data.source_name : "";
                        }
                    },
                    {
                        data: 'client_phone',
                        name: 'client_phone'
                    },
                    {
                        data: 'client_mobile',
                        name: 'client_mobile'
                    },
                    {
                        data: 'client_email',
                        name: 'client_email'
                    },
                    {
                        data: 'client_country',
                        name: 'client_country'
                    },
                    {
                        data: 'author',
                        name: 'author',
                        render: function(data) {
                            return data ? data.name : '';
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
                "lengthMenu": [10, 25, 50, 100], // Page length options
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
                $('#clientsTable').on('click', '.delete-client-btn', function(e) {
                    e.preventDefault();
                    const clientId = $(this).data('id');

                    if (confirm("Are you sure you want to delete this client?")) {
                        const deleteUrl = `./clients/${clientId}`;
                        const table = $('#clientsTable').DataTable();
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
                                console.error('Error deleting client:', xhr.responseText);
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
                    $('#all-clients').prepend(alertBox);
                    setTimeout(() => {
                        $('.alert').alert('close');
                    }, 5000);
                }
            });


            // Function to toggle spinner and details for client modal
            function toggleLoading(modal, isLoading) {
                modal.find('#loadingSpinner').toggle(isLoading);
                modal.find('#clientDetails').toggle(!isLoading);
            }

            // Event listener for fetching and displaying client details in the modal
            $('#clientsTable').on('click', '[data-bs-toggle="modal"]', function() {
                const clientId = $(this).data('id');
                const modal = $('#viewClientModal');
                toggleLoading(modal, true);
                modal.modal('show');
                $.ajax({
                    url: `./clients/${clientId}`,
                    type: 'GET',
                    success: function(data) {
                        modal.find('#client_name').text(data.client_name || 'N/A');
                        modal.find('#client_username').text(data.client_username || 'N/A');
                        modal.find('#source').text(data.source ? data.source.source_name : 'N/A');
                        modal.find('#client_phone').text(data.client_phone || 'N/A');
                        modal.find('#client_mobile').text(data.client_mobile || 'N/A');
                        modal.find('#client_email').text(data.client_email || 'N/A');
                        modal.find('#client_country').text(data.client_country || 'N/A');
                        modal.find('#author').text(data.author ? data.author.name : 'N/A');
                        modal.find('#created').text(data.created_at ? moment(data.created_at).format(
                            'D MMM, YYYY') : 'N/A');
                        toggleLoading(modal, false);
                    },
                    error: function(xhr) {
                        console.error('Error fetching client details:', xhr);
                        alert('Failed to fetch client details. Please try again.');
                        toggleLoading(modal, false);
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
