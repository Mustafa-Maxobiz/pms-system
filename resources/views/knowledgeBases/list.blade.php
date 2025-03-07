<x-app-layout>
    <!-- All Knowledge Bases Content -->
    <div id="all-knowledge-bases" class="my-3 split">
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
                                All Knowledge Bases
                                @can('Add New KnowledgeBase')
                                    <a href="{{ route('knowledge-base.create') }}" class="btn-link btn btn-dark float-end">
                                        <i class="fa fa-plus"></i> Add New
                                    </a>
                                @endcan
                            </h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="knowledgeBasesTable" width="100%"
                                    cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>Attachments</th>
                                            <th>Department</th>
                                            <th>Tags</th>
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

    <!-- Modal for Viewing Knowledge Base Details -->
    <div class="modal fade" id="viewKnowledgeBaseModal" tabindex="-1" aria-labelledby="viewKnowledgeBaseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewKnowledgeBaseModalLabel">Knowledge Base Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body knowledge-base-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Content -->
                    <div id="knowledgeBaseDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="knowledgeBaseTitle" class="form-label">Title:</label>
                                <p id="knowledgeBaseTitle"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="knowledgeBaseDepartment" class="form-label">Department:</label>
                                <p id="knowledgeBaseDepartment"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="knowledgeBaseAuthor" class="form-label">Author:</label>
                                <p id="knowledgeBaseAuthor"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="knowledgeBaseCreated" class="form-label">Created:</label>
                                <p id="knowledgeBaseCreated"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="knowledgeBaseTags" class="form-label">Tags:</label>
                                <p id="knowledgeBaseTags"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="knowledgeBaseAttachments" class="form-label">Attachments:</label>
                                <div id="knowledgeBaseAttachments"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $('#knowledgeBasesTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                stateSave: true,
                ajax: {
                    url: "{{ route('knowledge-base.index') }}",
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
                        data: 'attachments',
                        name: 'attachments',
                        render: function(data) {
                            if (data) {
                                try {
                                    let attachmentsArray = JSON.parse(data);
                                    if (Array.isArray(attachmentsArray) && attachmentsArray.length > 0) {
                                        return attachmentsArray.map(attachment =>
                                            '<a href="/' + attachment.path +
                                            '" target="_blank" class="text-decoration-none" title="' +
                                            attachment.original_name + '" alt="' + attachment
                                            .original_name + '"><i class="fa fa-eye fa-2x"></i></a>'
                                        ).join(' ');
                                    }
                                } catch (e) {
                                    console.error('Error parsing attachments:', e);
                                }
                            }
                            return '';
                        }
                    },
                    {
                        data: 'department',
                        name: 'department',
                        render: function(data) {
                            return data ? data.name : '';
                        }
                    },
                    {
                        data: 'tags',
                        name: 'tags',
                        render: function(data) {
                            if (data) {
                                try {
                                    let parsedTags = JSON.parse(data);
                                    return parsedTags.map(tag => '<h5 class="btn btn-success">' + tag.value +
                                        '</h5>').join(' ');
                                } catch (e) {
                                    console.error("Error parsing tags JSON:", e);
                                    return 'Invalid tags format';
                                }
                            } else {
                                return '';
                            }
                        }
                    },
                    {
                        data: 'author',
                        name: 'author',
                        render: function(data) {
                            return data.name || 'Unknown';
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
                ]
            });

            $('#knowledgeBasesTable').on('click', '.view-knowledge-base-btn', function() {
                const knowledgeBaseId = $(this).data('id');
                const modal = $('#viewKnowledgeBaseModal');

                modal.find('#loadingSpinner').show();
                modal.find('#knowledgeBaseDetails').hide();
                modal.modal('show');

                $.ajax({
                    url: `./knowledge-base/${knowledgeBaseId}`,
                    type: 'GET',
                    success: function(data) {
                        modal.find('#knowledgeBaseTitle').text(data.title || 'N/A');
                        modal.find('#knowledgeBaseDepartment').text(data.department ? data.department.name :
                            'N/A');
                        modal.find('#knowledgeBaseAuthor').text(data.author ? data.author.name : 'N/A');
                        modal.find('#knowledgeBaseCreated').text(moment(data.created_at).format(
                            'D MMM, YYYY') || 'N/A');
                        modal.find('#knowledgeBaseTags').html(data.tags ? JSON.parse(data.tags).map(tag =>
                                '<span class="badge bg-success">' + tag.value + '</span>').join(' ') :
                            'N/A');
                        modal.find('#knowledgeBaseAttachments').html(data.attachments ? JSON.parse(data
                                .attachments).map(attachment => '<a href="/' + attachment.path +
                                '" target="_blank">' + attachment.original_name + '</a>').join(', ') :
                            'N/A');

                        modal.find('#loadingSpinner').hide();
                        modal.find('#knowledgeBaseDetails').show();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error Fetching Data:", status, error);
                        alert('Failed to fetch knowledge base details. Please try again.');
                        modal.modal('hide');
                    }
                });
            });
            $(document).ready(function() {
                $('#knowledgeBasesTable').on('click', '.delete-knowledge-base-btn', function(e) {
                    e.preventDefault();
                    const knowledgeBaseId = $(this).data('id');

                    if (confirm("Are you sure you want to delete this knowledge base?")) {
                        $.ajax({
                            url: `./knowledge-base/${knowledgeBaseId}`,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    showMessage('success', response.message);
                                    $('#knowledgeBasesTable').DataTable().ajax.reload(null, false);
                                } else {
                                    showMessage('danger', 'Error: ' + response.message);
                                }
                            },
                            error: function(xhr) {
                                console.error('Error deleting knowledge base:', xhr.responseText);
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
                    $('#all-knowledge-bases').prepend(alertBox);
                    setTimeout(() => {
                        $('.alert').alert('close');
                    }, 5000);
                }
            });
        </script>
    @endsection
</x-app-layout>
