<x-app-layout>
    <!-- All Clients Content -->
    <div id="reports" class="split" style="margin-top:100px;">
        <div class="container-fluid">
            <div class="card shadow mb-4 mt-4 rounded-0">
                <ul class="nav nav-tabs d-flex gap-2 border-0" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link1 active" id="daily-report-tab" data-bs-toggle="tab"
                            data-bs-target="#daily-report" type="button" role="tab" aria-controls="daily-report"
                            aria-selected="true">Daily Report</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link1" id="monthly-report-tab" data-bs-toggle="tab"
                            data-bs-target="#monthly-report" type="button" role="tab"
                            aria-controls="monthly-report" aria-selected="false">Monthly Report</button>
                    </li>
                </ul>
                <div class="card-body table-body p-0">
                    <div class="tab-content " id="myTabContent">
                        <div id="daily-report" class="tab-pane fade show active" role="tabpanel"
                            aria-labelledby="daily-report-tab">

                            {{-- For Daily Reports --}}
                            <div class="row p-3">

                                @include('reports.filter')

                            </div>

                            <!-- DataTable for daily report -->
                            <div class="table-responsive">
                                <table class="table table-hover daily-reports" id="daily-reports" width="100%"
                                    cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>Project ID</th>
                                            <th>Task ID</th>
                                            <th>Task Name</th>
                                            <!--<th>Task Description</th>-->
                                            <th>Task Type</th>
                                            <th>Task Value</th>
                                            <th>Active Time</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Team</th>
                                            <th>Assigned Members</th>
                                            <th>Status</th>
                                            <th>Task Stage</th>
                                            <th>Priority</th>
                                            <th>Author</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div id="monthly-report" class="tab-pane fade" role="tabpanel"
                            aria-labelledby="monthly-report-tab">

                            {{-- For Monthly Reports --}}


                            {{-- <div class="col-md-4 d-flex justify-content-end">
                                <a href="#" class="btn-link btn btn-primary" role="button"
                                    data-bs-toggle="tab" aria-haspopup="true">
                                    Export To Excel
                                </a>
                            </div> --}}

                            <!-- DataTable for monthly report -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>#</th>
                                            <th>Project Id</th>
                                            <th>Task Name</th>
                                            <th>Type</th>
                                            <th>Value</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Stage</th>
                                            <th>Assigned 1</th>
                                            <th>Assigned 2</th>
                                            <th>Finalized</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>01</td>
                                            <td>34957736</td>
                                            <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                            <td>logo Design</td>
                                            <td>80</td>
                                            <td>2023-09-27</td>
                                            <td>2023-09-27</td>
                                            <td>Final</td>
                                            <td><a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002795.png" alt="">
                                                </a></td>
                                            <td>
                                                <a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002798.png" alt="">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002811.png" alt="">
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>01</td>
                                            <td>34957736</td>
                                            <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                            <td>logo Design</td>
                                            <td>80</td>
                                            <td>2023-09-27</td>
                                            <td>2023-09-27</td>
                                            <td>Final</td>
                                            <td><a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002795.png" alt="">
                                                </a></td>
                                            <td>
                                                <a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002798.png" alt="">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002811.png" alt="">
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>01</td>
                                            <td>34957736</td>
                                            <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                            <td>logo Design</td>
                                            <td>80</td>
                                            <td>2023-09-27</td>
                                            <td>2023-09-27</td>
                                            <td>Final</td>
                                            <td><a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002795.png" alt="">
                                                </a></td>
                                            <td>
                                                <a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002798.png" alt="">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002811.png" alt="">
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>01</td>
                                            <td>34957736</td>
                                            <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                            <td>logo Design</td>
                                            <td>80</td>
                                            <td>2023-09-27</td>
                                            <td>2023-09-27</td>
                                            <td>Final</td>
                                            <td><a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002795.png" alt="">
                                                </a></td>
                                            <td>
                                                <a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002798.png" alt="">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002811.png" alt="">
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>01</td>
                                            <td>34957736</td>
                                            <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                            <td>logo Design</td>
                                            <td>80</td>
                                            <td>2023-09-27</td>
                                            <td>2023-09-27</td>
                                            <td>Final</td>
                                            <td><a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002795.png" alt="">
                                                </a></td>
                                            <td>
                                                <a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002798.png" alt="">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="#" id="team-profile">
                                                    <img src="./Images/Group 1000002811.png" alt="">
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    @section('scripts')
        <script>
            var table;
            $(document).ready(function() {
                function setFormValuesFromURL() {
                    let urlParams = new URLSearchParams(window.location.search);

                    $("#department").val(urlParams.get("department_id"));
                    $("#team").val(urlParams.get("team_id"));
                    $("#member").val(urlParams.get("member_id"));
                    $("#client").val(urlParams.get("client_id"));
                    $("#project").val(urlParams.get("project_id"));
                    $("#source").val(urlParams.get("source_id"));
                    $("#from_date").val(urlParams.get("from_date"));
                    $("#to_date").val(urlParams.get("to_date"));
                }

                setFormValuesFromURL();

                table = $('#daily-reports').DataTable({
                    drawCallback: function(settings) {
                        if (this.api().data().length === 0) {
                            $('#daily-reports tbody').html(
                                '<tr><td colspan="10">No records found</td></tr>');
                        }
                    },
                    processing: true,
                    serverSide: true,
                    paging: true,
                    stateSave: true,
                    ajax: {
                        url: "{{ route('reports.daily-reports') }}",
                        type: 'GET',
                        cache: false,
                        data: function(d) {

                            let urlParams = new URLSearchParams(window.location.search);

                            d.search = $('input[type="search"]').val();
                            d.department_id = urlParams.get("department_id");
                            d.team_id = urlParams.get("team_id");
                            d.member_id = urlParams.get("member_id");
                            d.client_id = urlParams.get("client_id");
                            d.project_id = urlParams.get("project_id");
                            d.source_id = urlParams.get("source_id");
                            d.from_date = urlParams.get("from_date");
                            d.to_date = urlParams.get("to_date");
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
                            data: 'project_id',
                            name: 'project_id',
                            render: function(data, type, row) {
                                if (data != null) {
                                    return `<a href="./projects/${data}/details">${data}</a>`;
                                } else {
                                    return '';
                                }
                            }
                        },
                        {
                            data: 'id',
                            name: 'id',
                            render: function(data, type, row) {
                                return `<a href="./projects/${row.project_id}/tasks/${data}/details">${data}</a>`;
                            }
                        },
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
                        {
                            data: 'task_value',
                            name: 'task_value'
                        },
                        {
                            data: 'total_time',
                            name: 'task_time'
                        },
                        {
                            data: 'start_date',
                            name: 'start_date',
                            render: function(data) {
                                return moment(data).format('D MMM, YYYY');
                            }
                        },
                        {
                            data: 'end_date',
                            name: 'end_date',
                            render: function(data) {
                                return moment(data).format('D MMM, YYYY');
                            }
                        },
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
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data) {
                                return moment(data).format('D MMM, YYYY');
                            }
                        },

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
                });

            });



            function getTeam(department_id, element) {
                var container = $(element).closest('.tab-pane'); // Get current tab
                $.ajax({
                    url: '{{ route('reports.get.team') }}',
                    type: 'GET',
                    data: {
                        department_id: department_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        container.find('#team').empty().append(
                            '<option value="" disabled selected>Select Team</option>');
                        response.forEach(function(team) {
                            container.find('#team').append('<option value="' + team.id + '">' + team.name +
                                '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Error fetching teams.');
                    }
                });
            }

            function getMember(team_id, element) {
                var container = $(element).closest('.tab-pane'); // Get current tab
                $.ajax({
                    url: '{{ route('reports.get.member') }}',
                    type: 'GET',
                    data: {
                        team_id: team_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        container.find('#member').empty().append(
                            '<option value="" disabled selected>Select Member</option>');
                        response.forEach(function(member) {
                            container.find('#member').append('<option value="' + member.id + '">' + member
                                .name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Error fetching members.');
                    }
                });
            }

            function getProject(client_id, element) {
                var container = $(element).closest('.tab-pane'); // Get current tab
                $.ajax({
                    url: '{{ route('reports.get.project') }}',
                    type: 'GET',
                    data: {
                        client_id: client_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        container.find('#project').empty().append(
                            '<option value="" disabled selected>Select Project</option>');
                        response.forEach(function(project) {
                            container.find('#project').append('<option value="' + project.id + '">' +
                                project.project_name + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Error fetching projects.');
                    }
                });
            }
        </script>
    @endsection
</x-app-layout>
