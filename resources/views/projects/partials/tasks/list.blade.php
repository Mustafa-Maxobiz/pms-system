    <!-- All tasks Content -->
    <div id="all-tasks" class="my-0 split">
        <div class="row">
            <div class="col-sm-12">

                <div class="card-header p-3 table-heading">
                    <h6>
                        All Tasks
                        @can('Add New Task')
                            <a href="{{ route('projects.tasks.create', ['project' => $project->id]) }}"
                                class="btn-link btn btn-dark py-2 float-end">
                                <i class="fa fa-plus"></i> Add New
                            </a>
                        @endcan
                    </h6>
                </div>
                <div class="p-0">
                    <div class="table-responsive">
                        <table class="table table-hover" id="tasksTable" width="100%" cellspacing="0">
                            <thead class="table-head">
                                <tr class="table-light">
                                    <th>Task ID</th>
                                    <th>Task Name</th>
                                    <th>Task Type</th>
                                    <th>Task Value</th>
                                    <th>Task Time</th>
                                    <th>Team</th>
                                    <th>CSR Verify</th>
                                    <th>Client Verify </th>
                                    <th>Assign Members</th>
                                    <th>Finalized By</th>
                                    <th>Task Stage</th>
                                    <th>Author</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
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
    <!-- View Department Modal -->
    <div class="modal fade" id="viewDepartmentModal" tabindex="-1" aria-labelledby="viewDepartmentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDepartmentModalLabel">Department Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body department-modal">
                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <!-- Department Details -->
                    <div id="departmentDetails" style="display: none;">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="departmentName" class="form-label">Department Name:</label>
                                <p id="departmentName"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="departmentId" class="form-label">Department ID:</label>
                                <p id="departmentId"></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="task_description" class="form-label">task_description:</label>
                                <p id="task_description"></p>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="author_name" class="form-label">Author:</label>
                                <p id="author_name"></p>
                            </div>
                            <div class="col-md-12 col-12 mb-3">
                                <label for="createdAt" class="form-label">Created At:</label>
                                <p id="createdAt"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
