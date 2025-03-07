<x-app-layout>
    <div id="all-projects" class="my-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    @if (Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header p-3 table-heading">
                            <h6>Project Tracker</h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="projectTrackerTable" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Task ID</th>
                                            <th>Name</th>
                                            <th>Stage</th>
                                            <th>Status</th>
                                            <th>Priority</th>
                                            <th>Assigned Member</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Project Alpha</td>
                                            <td>Designing</td>
                                            <td>Completed</td>
                                            <td>High</td>
                                            <td>Ahmad</td>
                                            <td>10 Feb, 2025</td>
                                            <td>15 Feb, 2025</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Project Alpha</td>
                                            <td>Development</td>
                                            <td>Ongoing</td>
                                            <td>Medium</td>
                                            <td>Zohaib</td>
                                            <td>16 Feb, 2025</td>
                                            <td>Pending</td>
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
            $(document).ready(function() {
                $('#projectTrackerTable').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true,
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
                    ],
                    
                });
            });
        </script>
    @endsection
</x-app-layout>
