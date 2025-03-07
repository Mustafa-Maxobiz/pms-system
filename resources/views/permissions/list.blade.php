<x-app-layout>
    <!-- All permissions Content -->
    <div id="all-permissions" class="my-3 split">
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
                            <h6>All Permissions <a href="{{ route('permissions.create') }}"
                                    class="btn-link btn btn-dark float-end"><i class="fa fa-plus"></i> Add New</a></h6>
                        </div>


                        <div class="container-fluid mt-2">
                            <div class="row mb-3 mt-3">
                                <div class="col-md-12">
                                    <input type="text" id="searchPermissions" class="form-control"
                                        placeholder="Search permissions...">
                                </div>
                            </div>
                            <div class="row" id="permissionsContainer">
                                <!-- Show permission -->
                            </div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <nav>
                                        <ul class="pagination" id="paginationLinks">
                                            <!-- Pagination Links Will Be Loaded Here -->
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>



                        {{-- <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="permissionsTable" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div> --}}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- View Permission Details Modal -->
    <div class="modal fade" id="viewPermissionModal" tabindex="-1" aria-labelledby="viewPermissionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPermissionModalLabel">Permission Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body permission-details-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Content -->
                    <div id="permissionDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="permission_id" class="form-label">ID</label>
                                <p id="permission_id"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="permission_name" class="form-label">Name</label>
                                <p id="permission_name"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="permission_created" class="form-label">Created</label>
                                <p id="permission_created"></p>
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

                let currentPage = 1;

                function fetchPermissions(page = 1, searchQuery = '') {
                    $.ajax({
                        url: "{{ route('permissions.index') }}",
                        type: 'GET',
                        data: {
                            search: searchQuery,
                            page: page
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#permissionsContainer').empty();
                            $('#paginationLinks').empty();

                            response.data.forEach(permission => {
                                let card = `
                            <div class="col-md-3 mb-4 permission-card" data-name="${permission.name.toLowerCase()}">
                                <div class="card shadow-sm" style="min-height: 0px;"    >
                                    <div class="card-body" style="min-height: 0px;">
                                        <h6 class="card-title">${permission.name}</h6>
                                        <p class="card-text"><strong>ID:</strong> ${permission.id}</p>
                                        <p class="card-text"><strong>Created:</strong> ${moment(permission.created_at).format('D MMM, YYYY')}</p>
                                        <div class="btn-group">${permission.action}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                                $('#permissionsContainer').append(card);
                            });

                            // Add Pagination Buttons
                            if (response.pagination.prev_page_url) {
                                $('#paginationLinks').append(
                                    `<li class="page-item"><a class="page-link pagination-btn" href="javascript:void(0)" data-page="${response.pagination.current_page - 1}">Previous</a></li>`
                                );
                            }

                            for (let i = 1; i <= response.pagination.last_page; i++) {
                                let activeClass = i === response.pagination.current_page ? 'active' : '';
                                $('#paginationLinks').append(
                                    `<li class="page-item ${activeClass}"><a class="page-link pagination-btn" href="javascript:void(0)" data-page="${i}">${i}</a></li>`
                                );
                            }

                            if (response.pagination.next_page_url) {
                                $('#paginationLinks').append(
                                    `<li class="page-item"><a class="page-link pagination-btn" href="#" data-page="${response.pagination.current_page + 1}">Next</a></li>`
                                );
                            }
                        },
                        error: function(xhr) {
                            console.error('Error fetching permissions:', xhr.responseText);
                        }
                    });
                }

                // Initial Load
                fetchPermissions();

                // Pagination Click
                $(document).on('click', '.pagination-btn', function(e) {
                    e.preventDefault();
                    let page = $(this).data('page');
                    fetchPermissions(page, $('#searchPermissions').val());
                });

                // Search Functionality
                $('#searchPermissions').on('keyup', function() {
                    fetchPermissions(1, $(this).val());
                });

                $('#permissionsContainer').on('click', '.delete-permission-btn', function(e) {
                    e.preventDefault();

                    if (confirm('Are you sure you want to delete this permission?')) {
                        const permissionId = $(this).data('id'); // ID of the permission
                        const deleteUrl = `./permissions/${permissionId}`; // Your delete route
                        const card = $(this).closest('.col-md-3'); // Get the closest card container

                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(response) {
                                if (response.success) {
                                    showMessage('success', response.message);
                                    card.fadeOut(300, function() {
                                        $(this).remove();
                                    }); // Smoothly remove the card
                                } else {
                                    showMessage('danger', 'Error: ' + response.message);
                                }
                            },
                            error: function(xhr) {
                                console.error('Error deleting permission:', xhr.responseText);
                                showMessage('danger', 'An error occurred. Please try again.');
                            }
                        });
                    }
                });
            });
        </script>

        <script>

            // $('#permissionsTable').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     paging: true,
            //     stateSave: true,
            //     ajax: {
            //         url: "{{ route('permissions.index') }}",
            //         type: 'GET',
            //         data: function(d) {
            //             d.search = $('input[type="search"]').val();
            //             d.start = d.start;
            //             d.length = d.length;
            //         },
            //         error: function(xhr) {
            //             if (xhr.status === 401) {
            //                 alert('Your session has expired. Redirecting to the login page...');
            //                 window.location.href = "{{ route('login') }}";
            //             } else {
            //                 console.error('DataTables AJAX error:', xhr.responseText);
            //                 alert('An error occurred while loading the data. Please try again.');
            //             }
            //         }
            //     },
            //     columns: [{
            //             data: 'id',
            //             name: 'id'
            //         },
            //         {
            //             data: 'name',
            //             name: 'name'
            //         },
            //         {
            //             data: 'created_at',
            //             name: 'created_at',
            //             render: function(data) {
            //                 return moment(data).format('D MMM, YYYY');
            //             }
            //         },
            //         {
            //             data: 'action',
            //             name: 'action',
            //             orderable: false,
            //             searchable: false
            //         }
            //     ],
            //     order: [
            //         [0, 'desc']
            //     ],
            //     lengthMenu: [10, 25, 50, 100],
            //     pageLength: 10,
            //     dom: 'lBfrtip',
            //     buttons: [{
            //             extend: 'copy',
            //             className: 'btn btn-primary'
            //         },
            //         {
            //             extend: 'csv',
            //             className: 'btn btn-success'
            //         },
            //         {
            //             extend: 'excel',
            //             className: 'btn btn-success'
            //         },
            //         {
            //             extend: 'pdf',
            //             className: 'btn btn-danger'
            //         },
            //         {
            //             extend: 'print',
            //             className: 'btn btn-info'
            //         }
            //     ]
            // });

            // // AJAX delete functionality
            // $('#permissionsTable').on('click', '.delete-permission-btn', function(e) {
            //     e.preventDefault();

            //     if (confirm('Are you sure you want to delete this permission?')) {
            //         const permissionId = $(this).data('id'); // ID of the permission
            //         const deleteUrl = `./permissions/${permissionId}`; // Your delete route
            //         const table = $('#permissionsTable').DataTable();
            //         const row = $(this).closest('tr'); // Get the closest row for deletion

            //         $.ajax({
            //             url: deleteUrl,
            //             type: 'DELETE',
            //             data: {
            //                 _token: $('meta[name="csrf-token"]').attr('content'),
            //             },
            //             success: function(response) {
            //                 if (response.success) {
            //                     showMessage('success', response.message);
            //                     table.row(row).remove().draw(false); // Remove the row without a full reload
            //                 } else {
            //                     showMessage('danger', 'Error: ' + response.message);
            //                 }
            //             },
            //             error: function(xhr) {
            //                 console.error('Error deleting permission:', xhr.responseText);
            //                 showMessage('danger', 'An error occurred. Please try again.');
            //             }
            //         });
            //     }
            // });


            // Show message function
            function showMessage(type, message) {
                $('html, body').animate({
                    scrollTop: $('.container-fluid').offset().top - 20 // Adjust offset for smooth scrolling
                        }, 100);
                        const alertBox = `
                    <div class="container-fluid">
                        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                            ${message}
                        </div>
                    </div>
                `;
                $('#all-permissions').prepend(alertBox);
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }

        </script>
    @endsection
</x-app-layout>
