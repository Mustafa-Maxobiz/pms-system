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
                            <h6>CSR Tasks Completed But Verification Pending</h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="all-task-table" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>Task ID</th>
                                            <th>Task Name</th>
                                            <th>Task Type</th>
                                            <th>Team</th>
                                            <th>Assign Members</th>
                                            <th>Status</th>
                                            <th>Task Stage</th>
                                            <th>Priority</th>
                                            <th>Author</th>
                                            <th>Action</th> <!-- Add Action Column -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header p-3 table-heading">
                            <h6>CSR Tasks</h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="all-task-assigned-table" width="100%"
                                    cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>Task ID</th>
                                            <th>Task Name</th>
                                            <th>Task Type</th>
                                            <th>Team</th>
                                            <th>Assign Members</th>
                                            <th>Status</th>
                                            <th>Task Stage</th>
                                            <th>Priority</th>
                                            <th>Author</th>
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
                // DataTable for tasks completed but verification pending
                table = $('#all-task-table').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    stateSave: true,
                    ajax: {
                        url: "{{ route('myTasks') }}",
                        type: 'GET',
                        data: function(d) {
                            d.search = d.search.value || $('input[type="search"]').val();
                            d.start = d.start;
                            d.length = d.length;
                        },
                        dataSrc: function(json) {
                            console.log("This is the response data:", json.data);
                            return json.data;
                        },
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
                            data: 'team',
                            name: 'team',
                            render: function(data) {
                                return data ? data.name : '';
                            }
                        },
                        {
                            data: 'task_assignments',
                            name: 'task_assignments',
                            render: function(data) {
                                return Array.isArray(data) ? data.map(item => item.user?.name || 'N/A')
                                    .join(', ') : 'N/A';
                            }
                        },
                        {
                            data: 'task_status_logs',
                            name: 'task_status',
                            render: function(data) {
                                return data.length ? data[0].task_status.title : '';
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
                            data: 'task_priority',
                            name: 'priority',
                            render: function(data) {
                                return data ? data.title : '';
                            }
                        },
                        {
                            data: 'author',
                            name: 'author',
                            render: function(data) {
                                return data ? data.name : '';
                            }
                        },
                        {
                            data: 'id',
                            name: 'id',
                            render: function(data, type, row) {
                                var btnAction = '';
                                @can('Edit Task')
                                    // Generate the correct edit URL dynamically
                                    var editUrl = './projects/' + row.project_id + '/tasks/' + row.id +
                                        '/edit';

                                    btnAction += '<a href="' + editUrl +
                                        '" class="btn btn-success bg-success text-white btn-sm py-2" title="Edit">' +
                                        '<i class="fa fa-edit"></i></a> ';
                                @endcan

                                @can('View Projects')
                                    // Generate the correct edit URL dynamically
                                    var viewUrl = './projects/' + row.project_id + '/details';

                                    btnAction += '<a href="' + viewUrl +
                                        '" class="btn btn-primary bg-primary text-white btn-sm py-2" title="Edit">' +
                                        '<i class="fa fa-list"></i></a> ';
                                @endcan

                                btnAction += '<button data-id="' + row.id +
                                    '" class="btn btn-info bg-info btn-sm py-2 text-white view-task-details" title="View">' +
                                    '<i class="fa fa-eye" aria-hidden="true"></i></button>';

                                return '<div class="btn-group" role="group" aria-label="Btn Group">' +
                                    btnAction + '</div>';
                            },
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
                    ],
                    "fnRowCallback": function(nRow, aData) {
                        if (aData.task_status_logs.length) {
                            var status = aData.task_status_logs[0].task_status;
                            
                            if (status.title === 'Completed') {
                                $('td', nRow)
                                    .addClass('completed-row')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                            if (status.title === 'Delayed') {
                                $('td', nRow)
                                    .addClass('delayed-row')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                            if (status.title === 'Verify TL') {
                                $('td', nRow)
                                    .addClass('verify-tl-row')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                            if (status.title === 'Verify CSRs') {
                                $('td', nRow)
                                    .addClass('active-verify-csrs-row')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                            if (status.title === 'Open') {
                                $('td', nRow)
                                    .addClass('open-row')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                            if (status.title === 'Active') {
                                $('td', nRow)
                                    .addClass('active-row')
                                    .css({
                                        'background-color': status.bg_color,
                                        'color': '#fff'
                                    });
                            }
                        }
                    }
                });

                // DataTable for tasks completed
                $('#all-task-assigned-table').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    stateSave: true,
                    ajax: {
                        url: "{{ route('csrTasks') }}",
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
                            data: 'team',
                            name: 'team',
                            render: function(data) {
                                return data ? data.name : '';
                            }
                        },
                        {
                            data: 'task_assignments',
                            name: 'task_assignments',
                            render: function(data) {
                                return Array.isArray(data) ? data.map(item => item.user?.name || 'N/A')
                                    .join(', ') : 'N/A';
                            }
                        },
                        {
                            data: 'task_status_logs',
                            name: 'task_status',
                            render: function(data) {
                                return data.length ? data[0].task_status.title : '';
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
                            data: 'task_priority',
                            name: 'priority',
                            render: function(data) {
                                return data ? data.title : '';
                            }
                        },
                        {
                            data: 'author',
                            name: 'author',
                            render: function(data) {
                                return data ? data.name : '';
                            }
                        },
                        {
                            data: 'id',
                            name: 'id',
                            render: function(data, type, row) {
                                var btnAction = '';
                                @can('Edit Task')
                                    // Generate the correct edit URL dynamically
                                    var editUrl = './projects/' + row.project_id + '/tasks/' + row.id +
                                        '/edit';

                                    btnAction += '<a href="' + editUrl +
                                        '" class="btn btn-success bg-success text-white btn-sm py-2" title="Edit">' +
                                        '<i class="fa fa-edit"></i></a> ';
                                @endcan

                                @can('View Projects')
                                    // Generate the correct edit URL dynamically
                                    var viewUrl = './projects/' + row.project_id + '/details';

                                    btnAction += '<a href="' + viewUrl +
                                        '" class="btn btn-primary bg-primary text-white btn-sm py-2" title="Edit">' +
                                        '<i class="fa fa-list"></i></a> ';
                                @endcan

                                btnAction += '<button data-id="' + row.id +
                                    '" class="btn btn-info bg-info btn-sm py-2 text-white view-task-details" title="View">' +
                                    '<i class="fa fa-eye" aria-hidden="true"></i></button>';

                                return '<div class="btn-group" role="group" aria-label="Btn Group">' +
                                    btnAction + '</div>';
                            },
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
                    ],
                    "fnRowCallback": function(nRow, aData) {
                        if (aData.task_status_logs.length) {
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
                    timeOut: 10000,
                });

                // Reload DataTable
                table.ajax.reload(null, false); // false ensures pagination doesn't reset
            });
        </script>
    @endsection
</x-app-layout>
