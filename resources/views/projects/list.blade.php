<x-app-layout>
    <!-- All projects Content -->
    <div id="all-projects" class="my-3 split">
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
                            <h6>All Projects
                                @can('Add New Project')
                                    <a href="{{ route('projects.create') }}" class="btn-link btn btn-dark float-end"><i
                                            class="fa fa-plus"></i> Add New</a>
                                @endcan
                            </h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="projectsTable" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>Id</th>
                                            <th>Project MainID</th>
                                            <th>Project Name</th>
                                            <th>Client Name</th>
                                            <th>URL</th>
                                            <th>Source</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>External Status</th>
                                            <th>Total Amount</th>
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
    <div class="modal fade" id="viewProjectModal" tabindex="-1" aria-labelledby="viewProjectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewProjectModalLabel">Project Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body project-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Content -->
                    <div id="projectDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="project_name" class="form-label">Project Name:</label>
                                <p id="project_name"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="project_id" class="form-label">Project ID:</label>
                                <p id="project_id"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="clientName" class="form-label">Client Name:</label>
                                <p id="clientName"></p>
                            </div>
                            <!-- Project URL row -->
                            <div class="col-md-6 col-12 mb-3">
                                <label for="url" class="form-label">Project URL:</label>
                                <p id="url"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="sourceName" class="form-label">Source:</label>
                                <p id="sourceName"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="start_date" class="form-label">Start Date:</label>
                                <p id="start_date"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="completion_date" class="form-label">Completion Date:</label>
                                <p id="completion_date"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="total_amount" class="form-label">Total Amount:</label>
                                <p id="total_amount"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="external_status" class="form-label">External Status:</label>
                                <p id="external_status"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="authorName" class="form-label">Author:</label>
                                <p id="authorName"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            $('#projectsTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                stateSave: true,
                ajax: {
                    url: "{{ route('projects.index') }}",
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
                        data: 'mainid',
                        name: 'mainid'
                    },
                    {
                        data: 'project_name',
                        name: 'project_name'
                    },
                    {
                        data: 'client',
                        name: 'client',
                        render: function(data) {
                            if (data != null) {
                                return data.client_name;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'url',
                        name: 'url',
                        render: function(data) {
                            if (data != null) {
                                return '<a href="' + data +
                                    '" target="_blank" class="text-decoration-none"><i class="fa fa-eye fa-2x"></i></a>';
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'source',
                        name: 'source',
                        render: function(data) {
                            if (data != null) {
                                return data.source_name;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        render: function(data) {
                            return moment(data).format('D MMM, YYYY');
                        }
                    },
                    {
                        data: 'completion_date',
                        name: 'completion_date',
                        render: function(data) {
                            return moment(data).format('D MMM, YYYY');
                        }
                    },
                    {
                        data: 'external_status',
                        name: 'external_status',
                        render: function(data) {
                            if (data != null) {
                                return data.title;
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
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
                $('#projectsTable').on('click', '.delete-project-btn', function(e) {
                    e.preventDefault();
                    const projectId = $(this).data('id');

                    if (confirm("Are you sure you want to delete this project?")) {
                        const deleteUrl = `./projects/${projectId}`;
                        const table = $('#projectsTable').DataTable();
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
                                console.error('Error deleting project:', xhr.responseText);
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
                    $('#all-projects').prepend(alertBox);
                    setTimeout(() => {
                        $('.alert').alert('close');
                    }, 5000);
                }
            });

            $('#projectsTable').on('click', '.view-project-btn', function() {
                const project_id = $(this).data('id'); // Retrieve project ID
                console.log("Clicked Project ID:", project_id); // Debugging

                const modal = $('#viewProjectModal');

                modal.find('#loadingSpinner').show();
                modal.find('#projectDetails').hide();
                modal.modal('show');

                // AJAX request
                $.ajax({
                    url: `./projects/${project_id}`, // Endpoint
                    type: 'GET',
                    success: function(data) {
                        console.log("Fetched Data:", data); // Debugging response
                        modal.find('#project_id').text(data.id || 'N/A');
                        modal.find('#project_name').text(data.project_name || 'N/A');
                        modal.find('#clientName').text(data.client ? data.client.client_name : 'N/A');
                        modal.find('#url').html(data.url ?
                            `<a href="${data.url}" target="_blank">${data.url}</a>` : 'N/A');
                        modal.find('#sourceName').text(data.source ? data.source.source_name : 'N/A');
                        modal.find('#start_date').text(data.start_date ? moment(data.start_date).format(
                            'D MMM, YYYY') : 'N/A');
                        modal.find('#completion_date').text(data.completion_date ? moment(data
                                .completion_date)
                            .format('D MMM, YYYY') : 'N/A');
                        modal.find('#total_amount').text(data.total_amount || 'N/A');
                        modal.find('#external_status').text(data.external_status || 'N/A');
                        modal.find('#authorName').text(data.author ? data.author.name : 'N/A');

                        // Update UI
                        modal.find('#loadingSpinner').hide();
                        modal.find('#projectDetails').show();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error Fetching Data:", status, error);
                        alert('Failed to fetch project details. Please try again.');
                        modal.modal('hide');
                    }
                });
            });
        </script>
    @endsection
</x-app-layout>
