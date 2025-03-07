<x-app-layout>
    <!-- All teams Content -->
    <div id="all-teams" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">

                    @if(Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('success') }}
                    </div>
                    @endif

                    @if(Session::has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session::get('error') }}
                    </div>
                    @endif

                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header p-3 table-heading">
                            <h6>
                                All teams
                                @can('Add New Team')
                                <a href="{{ route('teams.create') }}" class="btn-link btn btn-dark float-end">
                                    <i class="fa fa-plus"></i> Add New
                                </a>
                                @endcan
                            </h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="teamsTable" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
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

    @section('scripts')
    <script>
        $('#teamsTable').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            stateSave: true,
            ajax: {
                url: "{{ route('teams.index') }}",
                type: 'GET',
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.start = d.start; 
                    d.length = d.length;
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'author', name: 'author',
                    render: function(data) {
                        return data.name;  // Fallback if permissions is not an array
                    }
                },
                { data: 'created_at', name: 'created_at', render: function(data) {
                    return moment(data).format('D MMM, YYYY');
                }},
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']], // Default sorting by the first column (ID)
            "lengthMenu": [10, 25, 50, 100], // Page length options for the dropdown
            "pageLength": 10, // Default page length
            dom: 'lBfrtip', // Add 'l' to show the page length dropdown
            buttons: [
                {
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

        function confirmDelete(clientId) {
            if (confirm("Are you sure you want to delete this Team?")) {
                document.getElementById('delete-form-' + clientId).submit();
            }
        }
    </script>
    @endsection
</x-app-layout>