<x-app-layout>
    <div id="all-user-logs" class="my-3 split">
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

                    <!-- Filters Section Above Tables -->
                    <div class="card shadow mb-4">
                        <div class="card-header p-3  table-heading">
                            <h6>User Logs</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <select id="filter-user" class="form-control select2">
                                        <option value="">All Users</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select id="filter-status" class="form-control select2">
                                        <option value="">All Statuses</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select select2" id="departmentFilter">
                                        <option value="">All Departments</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select select2" id="teamFilter">
                                        <option value="">All Teams</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <!-- Single Table -->
                                <div class="col-md-12">
                                    <div class="card shadow mb-4">
                                        <div class="card-header p-3">
                                            <h6>User Logs</h6>
                                        </div>
                                        <div class="p-3">
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="user-logsTable" width="100%"
                                                    cellspacing="0">
                                                    <thead class="table-head">
                                                        <tr class="table-light">
                                                            <th>Id</th>
                                                            <th>User</th>
                                                            <th>Status</th>
                                                            <th>Activity</th>
                                                            <th>Action</th>
                                                            <th>Id</th>
                                                            <th>User</th>
                                                            <th>Status</th>
                                                            <th>Activity</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- End row -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <script>
            $(document).ready(function() {
                var table;

                function initializeDataTable(tableId) {
                    return $('#' + tableId).DataTable({
                        columns: [{
                                data: 'id1',
                                name: 'id1'
                            },
                            {
                                data: 'user1.name',
                                name: 'user1.name',
                                render: function(data) {
                                    return data ? data : 'N/A';
                                }
                            },
                            {
                                data: 'status1',
                                name: 'status1'
                            },
                            {
                                data: 'created_at1',
                                name: 'created_at1',
                                render: function(data) {
                                    return moment(data).format('D MMM, YYYY h:mm A');
                                }
                            },
                            {
                                data: 'action1',
                                name: 'action1',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'id2',
                                name: 'id2',
                                className: 'table-right'
                            },
                            {
                                data: 'user2.name',
                                name: 'user2.name',
                                render: function(data) {
                                    return data ? data : 'N/A';
                                }
                            },
                            {
                                data: 'status2',
                                name: 'status2',
                            },
                            {
                                data: 'created_at2',
                                name: 'created_at2',
                                render: function(data) {
                                    return moment(data).format('D MMM, YYYY h:mm A');
                                }
                            },
                            {
                                data: 'action2',
                                name: 'action2',
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
                }

                table = initializeDataTable('user-logsTable');

                function loadData() {
                    $.ajax({
                        url: "{{ route('user-logs.index') }}",
                        type: 'GET',
                        data: {
                            user_id: $('#filter-user').val() || '',
                            status_id: $('#filter-status').val() || '',
                            department_id: $('#departmentFilter').val() || '',
                            team_id: $('#teamFilter').val() || '',
                            search: $('input[type="search"]').val() || ''
                        },
                        success: function(response) {
                            // Clear existing data
                            table.clear();

                            // Combine data1 and data2 into a single array
                            const combinedData = [];
                            const maxLength = Math.max(response.data1.length, response.data2.length);

                            for (let i = 0; i < maxLength; i++) {
                                const row = {
                                    id1: response.data1[i]?.id || '',
                                    user1: response.data1[i]?.user || {
                                        name: 'N/A'
                                    },
                                    status1: response.data1[i]?.status || '',
                                    created_at1: response.data1[i]?.created_at || '',
                                    action1: response.data1[i]?.action || '',
                                    id2: response.data2[i]?.id || '',
                                    user2: response.data2[i]?.user || {
                                        name: 'N/A'
                                    },
                                    status2: response.data2[i]?.status || '',
                                    created_at2: response.data2[i]?.created_at || '',
                                    action2: response.data2[i]?.action || ''
                                };
                                combinedData.push(row);
                            }

                            // Add combined data to the table
                            table.rows.add(combinedData).draw();
                        }
                    });
                }

                loadData();

                // Reload the table when filters change
                $('#filter-user, #filter-status, #departmentFilter, #teamFilter').change(function() {
                    loadData();
                });

                // AJAX Delete Functionality
                $('.table').on('click', '.delete-user-log-btn', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to delete this log?')) {
                        const logId = $(this).data('id');
                        const deleteUrl = `./user-logs/${logId}`;
                        const row = $(this).closest('tr');

                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
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
                                console.error('Error deleting user log:', xhr.responseText);
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
                    const alertBox =
                        `<div class="container-fluid"><div class="alert alert-${type} alert-dismissible fade show" role="alert">${message}</div></div>`;
                    $('#all-user-logs').prepend(alertBox);
                    setTimeout(() => {
                        $('.alert').alert('close');
                    }, 5000);
                }
            });
        </script>
    @endsection
</x-app-layout>
