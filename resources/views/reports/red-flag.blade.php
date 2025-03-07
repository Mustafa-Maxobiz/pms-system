<x-app-layout>
    <!-- All Clients Content -->
    <div id="all-clients" class="my-3 split">
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
                            Red Flags
                            </h6>
                        </div>
                        <div class="p-0">
                           <div class="table-responsive">
                              <table class="table table-hover daily-reports" id="daily-reports" width="100%" cellspacing="0">
                                 <thead class="table-head">
                                    <tr class="table-light">
                                       <th>Project ID</th>
                                       <th>Task ID</th>
                                       <th>Task Name</th>
                                       <th>Task Type</th>
                                       <th>Task Value</th>
                                       <th>Task AVG Time</th>
                                       <th>Task Time</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
  


    @section('scripts')
        <script>
            var table;
            $(document).ready(function() {
               table = $('#daily-reports').DataTable({
                  processing: true,
                  serverSide: true,
                  paging: true,
                  stateSave: true,
                  ajax: {
                     url: "{{ route('reports.daily-reports') }}",
                     type: 'GET',
                     cache: false,
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
                           name: 'id'
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
                     { data: 'task_value', name: 'task_value' },
                     { data: 'task_value', name: 'task_value' },
                     { data: 'task_value', name: 'task_value' },
                     { data: 'start_date', name: 'start_date', render: function(data) {
                          return moment(data).format('D MMM, YYYY');
                     }},
                     { data: 'end_date', name: 'end_date', render: function(data) {
                         return moment(data).format('D MMM, YYYY');
                     }},
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
                     { data: 'created_at', name: 'created_at', render: function(data) {
                          return moment(data).format('D MMM, YYYY');
                     }},
                     
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
        </script>
    @endsection
</x-app-layout>
