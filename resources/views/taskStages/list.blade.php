<x-app-layout>
    <!-- All task-stages Content -->
    <div id="all-task-stages" class="my-3 split">
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
                            <h6>All Task Stages <a href="{{ route('task-stages.create') }}"
                                    class="btn-link btn btn-dark float-end"><i class="fa fa-plus"></i> Add New</a></h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="task-stagesTable" width="100%" cellspacing="0">
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

    <!-- View Task Stage Modal -->
    <div class="modal fade" id="viewTaskStageModal" tabindex="-1" aria-labelledby="viewTaskStageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTaskStageModalLabel">Task Stage Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body task-stage-details-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Content -->
                    <div id="taskStageDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="task_stage_title" class="form-label">Title</label>
                                <p id="task_stage_title"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="task_stage_description" class="form-label">Description</label>
                                <p id="task_stage_description"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="task_stage_order_by" class="form-label">Order By</label>
                                <p id="task_stage_order_by"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="task_stage_created_at" class="form-label">Created At</label>
                                <p id="task_stage_created_at"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $('#task-stagesTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                stateSave: true,
                ajax: {
                    url: "{{ route('task-stages.index') }}",
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

            $('#task-stagesTable').on('click', '[data-bs-toggle="modal"]', function() {
                const taskStageId = $(this).data('id');
                const modal = $('#viewTaskStageModal');
                toggleLoading(modal, true);
                modal.modal('show');

                $.ajax({
                    url: `./task-stages/${taskStageId}`,
                    type: 'GET',
                    success: function(data) {
                        modal.find('#task_stage_title').text(data.title || 'N/A');
                        modal.find('#task_stage_description').text(data.description || 'N/A');
                        modal.find('#task_stage_order_by').text(data.order_by || 'N/A');
                        modal.find('#task_stage_created_at').text(data.created_at ? moment(data.created_at)
                            .format('D MMM, YYYY') : 'N/A');
                        toggleLoading(modal, false);
                    },
                    error: function(xhr) {
                        console.error('Error fetching task stage details:', xhr);
                        alert('Failed to fetch task stage details. Please try again.');
                        toggleLoading(modal, false);
                    }
                });
            });

            function toggleLoading(modal, isLoading) {
                modal.find('#loadingSpinner').toggle(isLoading);
                modal.find('#taskStageDetails').toggle(!isLoading);
            }

            // AJAX delete functionality for Task Stages
            $('#task-stagesTable').on('click', '.delete-task-stage-btn', function(e) {
                e.preventDefault();

                if (confirm('Are you sure you want to delete this task stage?')) {
                    const taskStageId = $(this).data('id');
                    const deleteUrl = `./task-stages/${taskStageId}`;
                    const table = $('#task-stagesTable').DataTable();
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
                            console.error('Error deleting task stage:', xhr.responseText);
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
                $('#all-task-stages').prepend(alertBox);
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }
        </script>
    @endsection
</x-app-layout>
