<x-app-layout>
    <!-- All sources Content -->
    <div id="all-sources" class="my-3 split">
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
                                All Sources
                                @can('Add New Source')
                                    <a href="{{ route('sources.create') }}" class="btn-link btn btn-dark float-end">
                                        <i class="fa fa-plus"></i> Add New
                                    </a>
                                @endcan
                            </h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="sourcesTable" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>ID</th>
                                            <th>Source Name</th>
                                            <th>Source Url</th>
                                            <th>Source Type</th>
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

    <!-- View Source Modal -->
    <div class="modal fade" id="viewSourceModal" tabindex="-1" aria-labelledby="viewSourceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewSourceModalLabel">View Source</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body sources-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Content -->
                    <div id="sourceDetails" style="display: none;">
                        <div class="mb-3">
                            <label for="source_name" class="form-label">Source Name</label>
                            <p id="source_name"></p>
                        </div>
                        <div class="mb-3">
                            <label for="source_url" class="form-label">Source URL</label>
                            <a href="#" id="source_url" target="_blank">e</a>
                        </div>
                        <div class="mb-3">
                            <label for="source_type" class="form-label">Source Type</label>
                            <p id="source_type"></p>
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <p id="author"></p>
                        </div>
                        <div class="mb-3">
                            <label for="created" class="form-label">Created</label>
                            <p id="created"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            $('#sourcesTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                stateSave: true,
                ajax: {
                    url: "{{ route('sources.index') }}",
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
                        data: 'source_name',
                        name: 'source_name'
                    },
                    {
                        data: 'source_url',
                        name: 'source_url',
                        render: function(data) {
                            return data ?
                                `<a href="${data}" target="_blank" class="text-decoration-none"><i class="fa fa-eye fa-2x"></i></a>` :
                                '';
                        }
                    },
                    {
                        data: 'source_type',
                        name: 'source_type'
                    },
                    {
                        data: 'author',
                        name: 'author',
                        render: function(data) {
                            return data && data.name ? data.name : 'N/A';
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

            // AJAX delete functionality for sources
            $('#sourcesTable').on('click', '.delete-source-btn', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this source?')) {
                    const sourceId = $(this).data('id');
                    const deleteUrl = `./sources/${sourceId}`;
                    const table = $('#sourcesTable').DataTable();
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
                            console.error('Error deleting source:', xhr.responseText);
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
                $('#all-sources').prepend(alertBox);
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }

            // Event listener for fetching and displaying source details in the modal
            $('#sourcesTable').on('click', '[data-bs-toggle="modal"]', function() {
                const sourceId = $(this).data('id');
                const modal = $('#viewSourceModal');
                toggleLoading(modal, true);
                modal.modal('show');

                $.ajax({
                    url: `./sources/${sourceId}`,
                    type: 'GET',
                    success: function(data) {
                        modal.find('#source_name').text(data.source_name || 'N/A');
                        modal.find('#source_type').text(data.source_type || 'N/A');
                        modal.find('#author').text(data.author ? data.author.name : 'N/A');
                        modal.find('#created').text(data.created_at ? moment(data.created_at).format(
                            'D MMM, YYYY') : 'N/A');
                        modal.find('#source_url').attr('href', data.source_url || '#').text(data
                            .source_url || 'N/A');
                        toggleLoading(modal, false);
                    },
                    error: function(xhr) {
                        console.error('Error fetching source details:', xhr);
                        alert('Failed to fetch source details. Please try again.');
                        toggleLoading(modal, false);
                    }
                });
            });

            function toggleLoading(modal, isLoading) {
                modal.find('#loadingSpinner').toggle(isLoading);
                modal.find('#sourceDetails').toggle(!isLoading);
            }
        </script>
    @endsection

</x-app-layout>
