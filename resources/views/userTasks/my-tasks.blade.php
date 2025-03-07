<x-app-layout>
    <!-- All task Content -->
    <div id="all-task" class="my-3 split">
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
                            <h6>My Tasks</h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="all-task-table" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>Task ID</th>
                                            {{-- <th>Project ID</th> --}}
                                            <th>Task Name</th>
                                            <!--<th>Task Description</th>-->
                                            <th>Task Type</th>
                                            <th>Team</th>
                                            {{-- <th>Task Value</th> --}}
                                            {{-- <th>Start Date</th> --}}
                                            {{-- <th>End Date</th> --}}
                                            <th>Assign Members</th>
                                            <th>Status</th>
                                            <th>Task Stage</th>
                                            <th>Priority</th>
                                            <th>Author</th>
                                            {{-- <th>Created</th> --}}
                                            <th>Action</th> <!-- Add Action Column -->
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
            <div class="load-task-details"></div>
        </div>
    </div>

    @section('scripts')
        <link rel="stylesheet" href="{{ asset('public/richtexteditor/richtexteditor/rte_theme_default.css') }}" />
        <script type="text/javascript" src="{{ asset('public/richtexteditor/richtexteditor/rte.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/richtexteditor/richtexteditor/plugins/all_plugins.js') }}">
        </script>
        <script>
            var table;

            $(document).ready(function() {

                table = $('#all-task-table').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    stateSave: true,
                    ajax: {
                        url: "{{ route('myTasks') }}",
                        type: 'GET',
                        data: function(d) {
                            // Pass search term and pagination details to server
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
                        // { data: 'project_id', name: 'project_id' },
                        {
                            data: 'task_name',
                            name: 'task_name'
                        },
                        //{ data: 'task_description', name: 'task_description' },
                        {
                            data: 'task_type',
                            name: 'task_type',
                            render: function(data) {
                                if (data != null) {
                                    return data.title;
                                } else {
                                    return '';
                                }
                            }
                        },
                        // { data: 'task_value', name: 'task_value' },
                        // { data: 'start_date', name: 'start_date', render: function(data) {
                        //     return moment(data).format('D MMM, YYYY');
                        // }},
                        // { data: 'end_date', name: 'end_date', render: function(data) {
                        //     return moment(data).format('D MMM, YYYY');
                        // }},
                        {
                            data: 'team',
                            name: 'team',
                            render: function(data) {
                                if (data != null) {
                                    return data.name;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: 'task_assignments',
                            name: 'taskAssignments',
                            render: function(data) {
                                if (Array.isArray(data)) {
                                    // Extract and join user names
                                    return data.map(item => item.user?.name || 'N/A').join(', ');
                                }
                                return 'N/A'; // Return a default value if data is not an array
                            }
                        },
                        {
                            data: 'task_status_logs',
                            name: 'task_status',
                            render: function(data) {
                                if (data.length != 0) {
                                    return data[0].task_status.title;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: 'task_stage',
                            name: 'task_stage',
                            render: function(data) {
                                if (data != null) {
                                    return data.title;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: 'task_priority',
                            name: 'priority',
                            render: function(data) {
                                if (data != null) {
                                    return data.title;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: 'author',
                            name: 'author',
                            render: function(data) {
                                if (data != null) {
                                    return data.name;
                                } else {
                                    return '';
                                }
                            }
                        },
                        // { data: 'created_at', name: 'created_at', render: function(data) {
                        //     return moment(data).format('D MMM, YYYY');
                        // }},
                        // Action Column to link to task details
                        {
                            data: 'id',
                            name: 'id',
                            render: function(data) {
                                return '<button class="btn btn-info text-white bg-info btn-sm view-task-details" data-id="' +
                                    data + '">View Details</button>';
                            },
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
                    ],
                    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        if (aData.task_status_logs.length !== 0) {
                            var status = aData.task_status_logs[0].task_status;
                            if (status.title === 'Completed') {
                                $('td', nRow)
                                    .addClass('task-status-color')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                            if (status.title === 'Delayed') {
                                $('td', nRow)
                                    .addClass('task-status-color')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                            if (status.title === 'Verify TL') {
                                $('td', nRow)
                                    .addClass('task-status-color')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                            if (status.title === 'Verify CSRs') {
                                $('td', nRow)
                                    .addClass('task-status-color')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                            if (status.title === 'Open') {
                                $('td', nRow)
                                    .addClass('task-status-color')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                            if (status.title === 'Active') {
                                $('td', nRow)
                                    .addClass('active-rows')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                            if (status.title === 'RTC') {
                                $('td', nRow)
                                    .addClass('task-status-color')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                        }
                    }
                });

                $(document).on('click', '.view-task-details', function() {
                    var taskId = $(this).data('id'); // Get the task ID from the button
                    // Perform an AJAX request to fetch task details
                    $.ajax({
                        url: './my-tasks/' + taskId + '/details', // Match the route you defined
                        method: 'GET',
                        success: function(response) {
                            $('html, body').animate({
                                scrollTop: $('.load-task-details').offset().top -
                                    20 // Adjust offset for smooth scrolling
                            }, 100);
                            // Focus on the task details container
                            $('.load-task-details').focus();
                            // Assuming the response contains task details data
                            $('.load-task-details').html(response);
                            // Scroll to the task details section
                        },
                        error: function(xhr, status, error) {
                            alert('Error loading task details');
                        }
                    });
                });
            });
        </script>


        <script>
            Pusher.logToConsole = true;

            var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
                cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
                forceTLS: true
            });

            // Current Logged-in User ID
            var userId = "{{ auth()->id() }}"; // Laravel Blade se user ka ID pass karna

            // Public Channel Subscribe
            var channel = pusher.subscribe("user." + userId);

            channel.bind("task-notification", function(data) {
                toastr.success(data.message, "New Task Assigned!", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 5000,
                });

                // Reload DataTable
                table.ajax.reload(null, false); // false ensures pagination doesn't reset

            });
        </script>
    @endsection
</x-app-layout>
