<x-app-layout>
    <!-- All task-priorities Content -->
    <div id="all-task-priorities" class="my-3 split">
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
                            <h6>All Task Priorities
                                <a href="{{ route('task-priorities.create') }}" class="btn-link btn btn-dark float-end">
                                    <i class="fa fa-plus"></i> Add New
                                </a>
                            </h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="task-prioritiesTable" width="100%"
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

    <!-- View Task Priority Modal -->
    <div class="modal fade" id="viewTaskPriorityModal" tabindex="-1" aria-labelledby="viewTaskPriorityModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTaskPriorityModalLabel">Task Priority Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body task-priority-details-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Content -->
                    <div id="taskPriorityDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="task_priority_title" class="form-label">Title</label>
                                <p id="task_priority_title"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="task_priority_description" class="form-label">Description</label>
                                <p id="task_priority_description"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="task_priority_order_by" class="form-label">Order By</label>
                                <p id="task_priority_order_by"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="task_priority_created_at" class="form-label">Created At</label>
                                <p id="task_priority_created_at"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $('#task-prioritiesTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                stateSave: true,
                ajax: {
                    url: "{{ route('task-priorities.index') }}",
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

            $('#task-prioritiesTable').on('click', '[data-bs-toggle="modal"]', function() {
                const taskPriorityId = $(this).data('id');
                const modal = $('#viewTaskPriorityModal');
                toggleLoading(modal, true);
                modal.modal('show');

                $.ajax({
                    url: `./task-priorities/${taskPriorityId}`,
                    type: 'GET',
                    success: function(data) {
                        modal.find('#task_priority_title').text(data.title || 'N/A');
                        modal.find('#task_priority_description').text(data.description || 'N/A');
                        modal.find('#task_priority_order_by').text(data.order_by || 'N/A');
                        modal.find('#task_priority_created_at').text(data.created_at ? moment(data
                            .created_at).format('D MMM, YYYY') : 'N/A');
                        toggleLoading(modal, false);
                    },
                    error: function(xhr) {
                        console.error('Error fetching task priority details:', xhr);
                        alert('Failed to fetch task priority details. Please try again.');
                        toggleLoading(modal, false);
                    }
                });
            });

            function toggleLoading(modal, isLoading) {
                modal.find('#loadingSpinner').toggle(isLoading);
                modal.find('#taskPriorityDetails').toggle(!isLoading);
            }

            // AJAX delete functionality for Task Priorities
            $('#task-prioritiesTable').on('click', '.delete-task-priority-btn', function(e) {
                e.preventDefault();

                if (confirm('Are you sure you want to delete this task priority?')) {
                    const taskPriorityId = $(this).data('id');
                    const deleteUrl = `./task-priorities/${taskPriorityId}`;
                    const table = $('#task-prioritiesTable').DataTable();
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
                            console.error('Error deleting task priority:', xhr.responseText);
                            showMessage('danger', 'An error occurred. Please try again.');
                        }
                    });
                }
            });

            // Function to display messages
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
                $('#all-task-priorities').prepend(alertBox);
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }
        </script>
    @endsection
</x-app-layout>
