<div class="container-fluid p-0">
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid p-0">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse d-lg-flex justify-content-center" id="navbarSupportedContent">
                <div class="row nav-links text-center w-100 d-lg-flex justify-content-center">
                    @can('View Projects')
                        <div class="col-6 col-lg-auto">
                            <a class="nav-link-2" href="{{ route('projects.index') }}">
                                <div class="status_2 d-flex align-items-center">
                                    <img class="mx-2" src="{{ asset('public/template/Images/Icon.svg') }}"
                                        alt="Icon" />
                                    <span> All Projects </span>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('Add New Project')
                        <div class="col-6 col-lg-auto">
                            <a class="nav-link-2" href="{{ route('projects.create') }}">
                                <div class="status_2 d-flex align-items-center">
                                    <img class="mx-2" src="{{ asset('public/template/Images/additem.svg') }}"
                                        alt="Add Item" />
                                    <span> New Project </span>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('View Projects')
                        <div class="col-6 col-lg-auto">
                            <a class="nav-link-2" href="{{ route('projects.index') }}">
                                <div class="status_2 d-flex align-items-center">
                                    <img class="mx-2" src="{{ asset('public/template/Images/brush.svg') }}"
                                        alt="In Working" />
                                    <span> In Working Projects </span>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('View Departments')
                        <div class="col-6 col-lg-auto">
                            <a class="nav-link-2" href="{{ route('departments.index') }}">
                                <div class="status_2 d-flex align-items-center">
                                    <img class="mx-2" src="{{ asset('public/template/Images/brush.svg') }}"
                                        alt="Department Management" />
                                    <span> Department Management </span>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('View Teams')
                        <div class="col-6 col-lg-auto">
                            <a class="nav-link-2" href="{{ route('teams.index') }}">
                                <div class="status_2 d-flex align-items-center">
                                    <img class="mx-2" src="{{ asset('public/template/Images/setting-2.svg') }}"
                                        alt="Team Management" />
                                    <span> Team Management </span>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('View Clients')
                        <div class="col-6 col-lg-auto">
                            <a class="nav-link-2" href="{{ route('clients.index') }}">
                                <div class="status_2 d-flex align-items-center">
                                    <img class="mx-2" src="{{ asset('public/template/Images/setting.svg') }}"
                                        alt="Client Management" />
                                    <span> Client Management </span>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('View Sources')
                        <div class="col-6 col-lg-auto">
                            <a class="nav-link-2" href="{{ route('sources.index') }}">
                                <div class="status_2 d-flex align-items-center">
                                    <img class="mx-2" src="{{ asset('public/template/Images/message-edit.svg') }}"
                                        alt="Source" />
                                    <span> Source </span>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('View Daily Reports')
                        <div class="col-6 col-lg-auto">
                            <a class="nav-link-2" href="{{ route('reports.index') }}">
                                <div class="status_2 d-flex align-items-center">
                                    <img class="mx-2" src="{{ asset('public/template/Images/message-edit-1.svg') }}"
                                        alt="Reports" />
                                    <span> Reports </span>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('More Options')
                        <!-- New Dropdown Menu -->
                        <div class="col-6 col-lg-auto">
                            <div class="dropdown">
                                <a class="nav-link-2 dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="status_2 d-flex align-items-center">
                                        <img class="mx-2" src="{{ asset('public/template/Images/setting-2.svg') }}"
                                            alt="Dropdown Icon" />
                                        <span> More Options </span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu more-options p-0" aria-labelledby="dropdownMenuLink">
                                    @can('View Users')
                                        <li><a class="dropdown-item" href="{{ route('users.index') }}">Users</a></li>
                                    @endcan
                                    @can('View Permissions')
                                        <li><a class="dropdown-item" href="{{ route('permissions.index') }}">Permissions</a>
                                        </li>
                                    @endcan
                                    @can('View Roles')
                                        <li><a class="dropdown-item" href="{{ route('roles.index') }}">Roles</a></li>
                                    @endcan
                                    <li><a class="dropdown-item" href="{{ route('settings.index') }}">App Settings</a></li>
                                    @can('View TaskTypes' || 'View TaskStatus' || 'View TaskStages' || 'View TaskPriorities'
                                        || 'View ExternalStatus')
                                        <hr>
                                    @endcan
                                    @can('View TaskTypes')
                                        <li><a class="dropdown-item" href="{{ route('task-types.index') }}">Task Types</a></li>
                                    @endcan
                                    @can('View TaskStatus')
                                        <li><a class="dropdown-item" href="{{ route('task-status.index') }}">Task Status</a>
                                        </li>
                                    @endcan
                                    @can('View TaskStages')
                                        <li><a class="dropdown-item" href="{{ route('task-stages.index') }}">Task Stage</a>
                                        </li>
                                    @endcan
                                    @can('View TaskPriorities')
                                        <li><a class="dropdown-item" href="{{ route('task-priorities.index') }}">Task
                                                Priorities</a>
                                        </li>
                                    @endcan
                                    @can('View ExternalStatus')
                                        <li><a class="dropdown-item" href="{{ route('external-status.index') }}">External
                                                Status</a>
                                        </li>
                                    @endcan
                                    <hr>
                                    @can('View UserStatus')
                                        <li><a class="dropdown-item" href="{{ route('user-status.index') }}">User Status</a>
                                        </li>
                                    @endcan
                                    @can('View UserLogs')
                                        <li><a class="dropdown-item" href="{{ route('user-logs.index') }}">User Logs</a>
                                        </li>
                                    @endcan
                                    <li><a class="dropdown-item" href="{{ route('target.index') }}">Team Target</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('target.show') }}">Show Target</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </nav>
</div>
