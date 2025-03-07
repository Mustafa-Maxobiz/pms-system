<x-app-layout>
    <div id="project-details" class="mb-4 mt-4 pt-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 mb-4">
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
                </div>
            </div>
            <div class="card shadow mb-4 mt-4 rounded-0">
                <ul class="nav nav-tabs d-flex gap-2 border-0" id="myTab" role="tablist">
                    @can('View Projects')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link1 active" id="project-details-form-tab" data-bs-toggle="tab"
                                data-bs-target="#project-details-form" type="button" role="tab"
                                aria-controls="project-details-form" aria-selected="true">Project Detail</button>
                        </li>
                    @endcan
                    @can('View Tasks')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link1" id="related-task-tab" data-bs-toggle="tab"
                                data-bs-target="#related-task" type="button" role="tab" aria-controls="related-task"
                                aria-selected="false">Related Tasks</button>
                        </li>
                    @endcan
                    @can('View Payments')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link1" id="related-payments-tab" data-bs-toggle="tab"
                                data-bs-target="#related-payments" type="button" role="tab"
                                aria-controls="related-payments" aria-selected="false">Related Payments</button>
                        </li>
                    @endcan
                </ul>
                <div class="card-body table-body p-0">
                    <div class="tab-content" id="myTabContent">
                        @can('View Projects')
                            <div id="project-details-form" class="tab-pane fade show active" role="tabpanel"
                                aria-labelledby="project-details-form-tab">
                                <div class="row p-4">
                                    <!-- Project ID -->
                                    <div class="col-md-6 mb-3">
                                        <label for="project_id" class="form-label">PROJECT ID:</label>
                                        <p>{{ $project->id }}</p>
                                    </div>

                                    <!-- Project Name -->
                                    <div class="col-md-6 mb-3">
                                        <label for="project_name" class="form-label">PROJECT NAME:</label>
                                        <p>{{ $project->project_name }}</p>
                                    </div>

                                    <!-- Source -->
                                    <div class="col-md-6 mb-3">
                                        <label for="source_id" class="form-label">SOURCE:</label>
                                        <p>{{ $project->source->source_name ?? 'N/A' }}</p>
                                    </div>

                                    <!-- Client Name -->
                                    <div class="col-md-6 mb-3">
                                        <label for="client_id" class="form-label">CLIENT NAME:</label>
                                        <p>{{ $project->client->client_name ?? 'N/A' }}</p>
                                    </div>

                                    <!-- URL -->
                                    <div class="col-md-6 mb-3">
                                        <label for="url" class="form-label">URL:</label>
                                        <p>{{ $project->url }}</p>
                                    </div>

                                    <!-- External Status -->
                                    <div class="col-md-6 mb-3">
                                        <label for="external_status" class="form-label">EXTERNAL STATUS:</label>
                                        <p>{{ $project->external_status }}</p>
                                    </div>

                                    <!-- Total Amount -->
                                    <div class="col-md-6 mb-3">
                                        <label for="total_amount" class="form-label">TOTAL AMOUNT:</label>
                                        <p>{{ $project->total_amount }}</p>
                                    </div>

                                    <!-- Start Date -->
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label">START DATE:</label>
                                        <p>{{ $project->start_date }}</p>
                                    </div>

                                    <!-- Target Date -->
                                    <div class="col-md-6 mb-3">
                                        <label for="target_date" class="form-label">TARGET DATE:</label>
                                        <p>{{ $project->target_date }}</p>
                                    </div>

                                    <!-- Completion Date -->
                                    <div class="col-md-6 mb-3">
                                        <label for="completion_date" class="form-label">COMPLETION DATE:</label>
                                        <p>{{ $project->completion_date }}</p>
                                    </div>

                                    <!-- Project Alerts -->
                                    <div class="col-md-6 mb-3">
                                        <label for="project_alerts" class="form-label">PROJECT ALERTS:</label>
                                        <p>{{ $project->project_alerts }}</p>
                                    </div>

                                    <!-- Final Feedback -->
                                    <div class="col-md-6 mb-3">
                                        <label for="final_feedback" class="form-label">FINAL FEEDBACK:</label>
                                        <p>{{ $project->final_feedback }}</p>
                                    </div>

                                    <!-- Back Button -->
                                    <div class="col-md-12 text-start">
                                        @can('Edit Project')
                                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary"><i
                                                    class="fa fa-edit"></i> Edit</a>
                                        @endcan
                                        <a href="{{ route('projects.index') }}" class="btn btn-warning"><i
                                                class="fa fa-arrow-rotate-left"></i> Back</a>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('View Tasks')
                            <div id="related-task" class="tab-pane fade" role="tabpanel" aria-labelledby="related-task-tab">
                                @include('projects.partials.tasks.list')
                            </div>
                        @endcan
                        @can('View Payments')
                            <div id="related-payments" class="tab-pane fade" role="tabpanel"
                                aria-labelledby="related-payments-tab">
                                @include('projects.partials.payments.list')
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Get the current URL hash (e.g., #related-task)
                const hash = window.location.hash;

                if (hash) {
                    // Remove the 'active' class from all tabs and tab content
                    document.querySelectorAll('.nav-link1').forEach(tab => tab.classList.remove('active'));
                    document.querySelectorAll('.tab-pane').forEach(content => {
                        content.classList.remove('show', 'active');
                    });

                    // Find the tab and tab content corresponding to the hash
                    const tabButton = document.querySelector(`button[data-bs-target="${hash}"]`);
                    const tabContent = document.querySelector(hash);

                    if (tabButton && tabContent) {
                        // Activate the tab and its content
                        tabButton.classList.add('active');
                        tabContent.classList.add('show', 'active');
                    }
                }
            });
            @can('View Tasks')
                $('#tasksTable').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    stateSave: true,
                    ajax: {
                        url: "{{ route('projects.tasks.index', ['project' => $project->id]) }}",
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
                            data: 'task_name',
                            name: 'task_name'
                        },
                        {
                            data: 'task_type',
                            name: 'task_type',
                            render: function(data) {
                                return data ? data.title : '';
                            }
                        },
                        {
                            data: 'task_value',
                            name: 'task_value'
                        },
                        {
                            data: 'total_time',
                            name: 'task_time'
                        },
                        {
                            data: 'team',
                            name: 'team',
                            render: function(data) {
                                return data ? data.name : '';
                            }
                        },
                        {
                            data: 'csr',
                            name: 'csr',
                            render: function(data) {
                                return data ? data.name : '';
                            }
                        },
                        {
                            data: 'client_name',
                            name: 'client_name',
                            render: function(data, type, row) {
                                return row.project.client ? row.project.client.client_name : '-';
                            }
                        },
                        {
                            data: 'task_assignments',
                            name: 'assign_id',
                            render: function(data) {
                                if (Array.isArray(data)) {
                                    return data.map(assignment =>
                                        `<a class="team-member" href="#">${assignment.user?.name || 'Unknown User'}</a>`
                                    ).join(' ');
                                }
                                return 'No Assignees';
                            }
                        },
                        {
                            data: 'finalized_user', // Ensure it matches the key from the controller
                            name: 'finalized',
                            render: function(data) {
                                return data || 'No Finalized User';
                            }
                        },
                        {
                            data: 'task_stage',
                            name: 'task_stage',
                            render: function(data) {
                                return data ? data.title : '';
                            }
                        },
                        {
                            data: 'author',
                            name: 'author',
                            render: function(data) {
                                return data.name;
                            }
                        },
                        {
                            data: 'start_date',
                            name: 'start_date',
                            render: function(data) {
                                return data ? moment(data).format('D MMM, YYYY') : '-';
                            }
                        },
                        {
                            data: 'end_date',
                            name: 'end_date',
                            render: function(data) {
                                return data ? moment(data).format('D MMM, YYYY') : '-';
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
            @endcan
            $(document).ready(function() {
                $('#tasksTable').on('click', '.delete-task-btn', function(e) {
                    e.preventDefault();
                    const taskId = $(this).data('id');

                    if (confirm("Are you sure you want to delete this task?")) {
                        $.ajax({
                            url: `{{ route('projects.tasks.destroy', ['project' => $project->id, 'task' => '__TASK_ID__']) }}`
                                .replace('__TASK_ID__', taskId),
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    showMessage('success', response.message);
                                    $('#tasksTable').DataTable().ajax.reload(null, false);
                                } else {
                                    showMessage('danger', 'Error: ' + response.message);
                                }
                            },
                            error: function(xhr) {
                                console.error('Error deleting task:', xhr.responseText);
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
        </div>`;
                    $('#project-details').prepend(alertBox);
                    setTimeout(() => {
                        $('.alert').alert('close');
                    }, 5000);
                }
            });


            $(document).ready(function() {
                $('#paymentsTable').on('click', '.delete-payment-btn', function(e) {
                    e.preventDefault();
                    const paymentId = $(this).data('id');

                    if (confirm("Are you sure you want to delete this payment?")) {
                        $.ajax({
                            url: `{{ route('projects.payments.destroy', ['project' => $project->id, 'payment' => '__PAYMENT_ID__']) }}`
                                .replace('__PAYMENT_ID__', paymentId),
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    showMessage('success', response.message);
                                    $('#paymentsTable').DataTable().ajax.reload(null, false);
                                } else {
                                    showMessage('danger', 'Error: ' + response.message);
                                }
                            },
                            error: function(xhr) {
                                console.error('Error deleting payment:', xhr.responseText);
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
                    $('#project-details').prepend(alertBox);
                    setTimeout(() => {
                        $('.alert').alert('close');
                    }, 5000);
                }
            });

            @can('View Payments')
                $('#paymentsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    stateSave: true,
                    ajax: {
                        url: "{{ route('projects.payments.index', ['project' => $project->id]) }}",
                        type: 'GET',
                        data: function(d) {
                            d.search = d.search.value || $('input[type="search"]').val(); 
                            d.start = d.start;
                            d.length = d.length;
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

                function toggleLoading(modal, isLoading) {
                    modal.find('#loadingSpinner').toggle(isLoading);
                    modal.find('#paymentDetails').toggle(!isLoading);
                }
                $('#paymentsTable').on('click', '[data-bs-toggle="modal"]', function() {
                    const paymentId = $(this).data('id');
                    const projectId = '{{ $project->id }}'
                    const modal = $('#viewPaymentModal');

                    toggleLoading(modal, true);
                    modal.modal('show');
                    const url = `{{ route('projects.payments.index', $project->id) }}/${paymentId}`;
                    modal.find('#paymentTitle').text('');
                    modal.find('#paymentDescription').text('');
                    modal.find('#paymentAuthor').text('');
                    modal.find('#paymentCreated').text('');
                    modal.find('#paymentSubTask').text('');
                    modal.find('#paymentTotalTaskValue').text('');
                    modal.find('#paymentDiscount').text('');
                    modal.find('#paymentGST').text('');
                    modal.find('#paymentPaidAmount').text('');
                    modal.find('#paymentRemainingAmount').text('');

                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            modal.find('#paymentTitle').text(data.title || 'N/A');
                            modal.find('#paymentDescription').text(data.description || 'N/A');
                            modal.find('#paymentAuthor').text(data.author ? data.author.name : 'N/A');
                            modal.find('#paymentCreated').text(
                                data.created_at ? moment(data.created_at).format('D MMM, YYYY') : 'N/A'
                            );
                            modal.find('#paymentSubTask').text(data.task_ids || 'N/A');
                            modal.find('#paymentTotalTaskValue').text(data.selected_task_value || 'N/A');
                            modal.find('#paymentDiscount').text(data.discount || 'N/A');
                            modal.find('#paymentGST').text(data.gst || 'N/A');
                            modal.find('#paymentPaidAmount').text(data.payed_amount || 'N/A');
                            modal.find('#paymentRemainingAmount').text(data.remaining_payment || 'N/A');

                            toggleLoading(modal, false);
                        },
                        error: function(xhr) {
                            console.error('Error fetching payment details:', xhr);
                            alert('Failed to fetch payment details. Please try again.');
                            toggleLoading(modal, false);
                        }
                    });
                });
            @endcan
        </script>
    @endsection
</x-app-layout>
