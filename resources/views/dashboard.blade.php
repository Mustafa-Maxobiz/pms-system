<x-app-layout>
    <div class="dash-bord">
        <div class="container-fluid">
            <div class="row d-flex justify-content-between">
                <!-- First Column -->
                <div class="col-lg-2 col-md-4 col-sm-6 col-6 p-1">
                    <a href="{{ route('members') }}" class="nav-link">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body-1 d-flex flex-column gap-4">
                                <div class="d-flex justify-content-between p-2 align-items-center">
                                    <h6 class="font-weight-bold">Members</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="text-center">
                                            <h5 class="mb-0" id="active-count">0</h5>
                                            <small class="text-success">Active</small>
                                        </div>
                                        <div class="text-center mx-3">
                                            <h5 class="mb-0" id="inactive-count">0</h5>
                                            <small class="text-danger">Off</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="avatars d-flex justify-content-end" id="avatars-container">
                                    <!-- Avatars will be loaded dynamically -->
                                </div>
                                <div id="statuses-container">
                                    <!-- User statuses will be displayed here -->
                                </div>
                            </div>
                        </div>
                    </a>
                    {{-- Sidebar Toggle Button --}}
                    <svg class="side-bar-btn toggle-icon" width="53" height="50" viewBox="0 0 53 50"
                        fill="none" xmlns="http://www.w3.org/2000/svg" class="toggle-icon" data-bs-toggle="collapse"
                        data-bs-target="#sidebarMenu" aria-expanded="false" aria-controls="sidebarMenu"
                        style="cursor: pointer">
                        <g filter="url(#filter0_d_525_7581)">
                            <rect x="4" width="45" height="42" rx="10" fill="#595CFE"
                                shape-rendering="crispEdges" />
                            <path
                                d="M19 16C19 15.4469 19.4788 15 20.0714 15H32.9286C33.5212 15 34 15.4469 34 16C34 16.5531 33.5212 17 32.9286 17H20.0714C19.4788 17 19 16.5531 19 16ZM22.9286 21C22.9286 20.4469 23.4074 20 24 20H32.9286C33.5212 20 34 20.4469 34 21C34 21.5531 33.5212 22 32.9286 22H24C23.4074 22 22.9286 21.5531 22.9286 21ZM34 26C34 26.5531 33.5212 27 32.9286 27H20.0714C19.4788 27 19 26.5531 19 26C19 25.4469 19.4788 25 20.0714 25H32.9286C33.5212 25 34 25.4469 34 26Z"
                                fill="white" />
                        </g>
                        <defs>
                            <filter id="filter0_d_525_7581" x="0" y="0" width="53" height="50"
                                filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                <feColorMatrix in="SourceAlpha" type="matrix"
                                    values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                <feOffset dy="4" />
                                <feGaussianBlur stdDeviation="2" />
                                <feComposite in2="hardAlpha" operator="out" />
                                <feColorMatrix type="matrix"
                                    values="0 0 0 0 0.772549 0 0 0 0 0.772549 0 0 0 0 0.827451 0 0 0 1 0" />
                                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_525_7581" />
                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_525_7581"
                                    result="shape" />
                            </filter>
                        </defs>
                    </svg>
                    <!-- Sidebar Content (Wrapped in a Collapse) -->
                    <div class="collapse position-absolute" id="sidebarMenu" style="z-index: 9999; top: 180px">
                        <div class="card shadow mb-0 mt-0">
                            <div class="card-box p-4 mb-2 mt-2 align-items-center d-flex flex-column">
                                <h2 style="font-size:16px; width:100%; text-align:left;">M Umar Pervez</h2>
                                <img src="./Images/Line 37.png" alt="" />
                                <div class="main-content-list mt-2">
                                    <ul>
                                        <li>
                                            <a class="nav-link side-bar" href="#resource-monitor"
                                                data-bs-toggle="collapse" data-bs-target="#sidebarMenu">Resource
                                                Monitor</a>
                                        </li>
                                        <li>
                                            <a class="nav-link side-bar" href="#unassigned-tasks"
                                                data-bs-toggle="collapse" data-bs-target="#sidebarMenu">Unassigned
                                                Tasks</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link side-bar" href="#delayed-tasks" data-bs-toggle="collapse"
                                                data-bs-target="#sidebarMenu">Delayed Tasks</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link side-bar" href="#project-without-tasks"
                                                data-bs-toggle="collapse" data-bs-target="#sidebarMenu">Projects without
                                                any Tasks</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link side-bar" href="#escrow-status" data-bs-toggle="collapse"
                                                data-bs-target="#sidebarMenu">Escrow Status (Not Funded)</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link side-bar" href="#pending-payments"
                                                data-bs-toggle="collapse" data-bs-target="#sidebarMenu">Pending
                                                Payments</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link side-bar" href="#delayed-feedback"
                                                data-bs-toggle="collapse" data-bs-target="#sidebarMenu">Delayed
                                                Feedback</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link side-bar" href="#awaiting-rating"
                                                data-bs-toggle="collapse" data-bs-target="#sidebarMenu">Awaiting
                                                Rating</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Side Bar Content Pane --}}
                </div>
                <!-- Completed Task -->
                <div class="col-lg-2 col-md-4 col-sm-6 col-6 p-1">
                    <a href="?task=completed" class="nav-link">
                        <div class="card border-2 shadow-sm bg-light-green">
                            <div class="card-body-1 text-left">
                                <svg width="29" height="30" viewBox="0 0 29 30" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.2495 18.2625L13.062 20.075L17.8953 15.2417" stroke="#71DD37"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M12.0832 7.74984H16.9165C19.3332 7.74984 19.3332 6.5415 19.3332 5.33317C19.3332 2.9165 18.1248 2.9165 16.9165 2.9165H12.0832C10.8748 2.9165 9.6665 2.9165 9.6665 5.33317C9.6665 7.74984 10.8748 7.74984 12.0832 7.74984Z"
                                        stroke="#71DD37" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M19.3333 5.35742C23.3571 5.57492 25.375 7.06117 25.375 12.5833V19.8333C25.375 24.6666 24.1667 27.0833 18.125 27.0833H10.875C4.83333 27.0833 3.625 24.6666 3.625 19.8333V12.5833C3.625 7.07326 5.64292 5.57492 9.66667 5.35742"
                                        stroke="#71DD37" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <p>Completed Task</p>
                                <h2>{{ $completedTasksCount }}</h2>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Incomplete Task -->
                <div class="col-lg-2 col-md-4 col-sm-6 col-6 p-1">
                    <a href="?task=incomplete" class="nav-link">
                        <div class="card border-2 shadow-sm bg-light-orange">
                            <div class="card-body-1 text-left">
                                <svg width="29" height="30" viewBox="0 0 29 30" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16.9168 20.0267L12.1318 15.2417" stroke="#FFAB00" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M16.8685 15.29L12.0835 20.075" stroke="#FFAB00" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M12.0832 7.74984H16.9165C19.3332 7.74984 19.3332 6.5415 19.3332 5.33317C19.3332 2.9165 18.1248 2.9165 16.9165 2.9165H12.0832C10.8748 2.9165 9.6665 2.9165 9.6665 5.33317C9.6665 7.74984 10.8748 7.74984 12.0832 7.74984Z"
                                        stroke="#FFAB00" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M19.3333 5.35742C23.3571 5.57492 25.375 7.06117 25.375 12.5833V19.8333C25.375 24.6666 24.1667 27.0833 18.125 27.0833H10.875C4.83333 27.0833 3.625 24.6666 3.625 19.8333V12.5833C3.625 7.07326 5.64292 5.57492 9.66667 5.35742"
                                        stroke="#FFAB00" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p>Incomplete Task</p>
                                <h2>{{ $incompleteTasksCount }}</h2>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- High Priority Task -->
                <div class="col-lg-2 col-md-4 col-sm-6 col-6 p-1">
                    <a href="?task=priority" class="nav-link">
                        <div class="card border-2 shadow-sm bg-light-blue">
                            <div class="card-body-1 text-left">
                                <svg width="29" height="30" viewBox="0 0 29 30" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14.5 9.86475V16.2085" stroke="#03C3EC" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M25.4716 10.8674V19.1323C25.4716 20.4857 24.7466 21.7424 23.5745 22.4311L16.397 26.5757C15.2249 27.2524 13.7749 27.2524 12.5907 26.5757L5.41321 22.4311C4.24112 21.7545 3.51611 20.4978 3.51611 19.1323V10.8674C3.51611 9.51405 4.24112 8.25733 5.41321 7.56858L12.5907 3.424C13.7628 2.74734 15.2128 2.74734 16.397 3.424L23.5745 7.56858C24.7466 8.25733 25.4716 9.50196 25.4716 10.8674Z"
                                        stroke="#03C3EC" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M14.5 20.0752V20.196" stroke="#03C3EC" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <p>High Priority Task</p>
                                <h2>{{ $highPriorityTasksCount }}</h2>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Red Flags -->
                <div class="col-lg-2 col-md-4 col-sm-6 col-6 p-1">
                    <a href="red-flag" class="nav-link">
                        <div class="card border-2 shadow-sm bg-light-red">
                            <div class="card-body-1 text-left">
                                <svg width="29" height="30" viewBox="0 0 29 30" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.22314 2.9165V27.0832" stroke="#FF3E1D" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M6.22314 5.3335H19.7565C23.019 5.3335 23.744 7.146 21.4481 9.44183L19.9981 10.8918C19.0315 11.8585 19.0315 13.4293 19.9981 14.2752L21.4481 15.7252C23.744 18.021 22.8981 19.8335 19.7565 19.8335H6.22314"
                                        stroke="#FF3E1D" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <p>Red Flags</p>
                                <h2>15</h2>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Knowledge Base -->
                <div class="col-lg-2 col-md-4 col-sm-6 col-6 p-1">
                    <a href="{{ route('knowledge-base.index') }}" class="nav-link">
                        <div class="card border-2 shadow-sm bg-light-gray">
                            <div class="card-body-1 text-left">
                                <svg width="29" height="30" viewBox="0 0 29 30" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M26.5832 20.7277V6.14312C26.5832 4.69312 25.399 3.61771 23.9611 3.73854H23.8886C21.3511 3.95604 17.4965 5.24896 15.3457 6.60229L15.1403 6.73521C14.7898 6.95271 14.2098 6.95271 13.8594 6.73521L13.5573 6.55396C11.4065 5.21271 7.564 3.93187 5.0265 3.72646C3.58859 3.60562 2.4165 4.69312 2.4165 6.13104V20.7277C2.4165 21.8877 3.359 22.9752 4.519 23.1202L4.86942 23.1685C7.4915 23.519 11.5394 24.8481 13.8594 26.1169L13.9078 26.141C14.234 26.3223 14.7536 26.3223 15.0678 26.141C17.3878 24.8602 21.4478 23.519 24.0819 23.1685L24.4807 23.1202C25.6407 22.9752 26.5832 21.8877 26.5832 20.7277Z"
                                        stroke="#8592A3" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M14.5 7.13379V25.2588" stroke="#8592A3" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.36475 10.7588H6.646" stroke="#8592A3" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10.271 14.3838H6.646" stroke="#8592A3" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <h3>
                                    Knowledge <br />
                                    Base
                                </h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid sidebar-content-panel">
        <!-- Resource Monitor Content -->
        <div id="resource-monitor" class="tab-pane fade in side-pane">
            <div class="card shadow mb-4 mt-4  w-100">
                <div class="card-header p-3 table-heading">
                    <h6>Resource Monitor</h6>
                </div>
                <div class="card-body table-body p-0">
                    <div class="table-responsive ">
                        <table class="table table-hover p-5" id="dataTable" width="100%" cellspacing="0">
                            <thead class="table-head">
                                <tr class="table-light">
                                    <th>Current Status</th>
                                    <th>Name</th>
                                    <th>Initial</th>
                                    <th>Update</th>
                                    <th>Final</th>
                                    <th>New Concepts</th>
                                    <th>Updated Final</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <a class="current-status" href="#">
                                            <span class="icon-2"><img src="./Images/pen-tool.svg"
                                                    alt="Working" /></span>Working
                                        </a>
                                    </td>
                                    <td>
                                        <a class="team-member" href="#">
                                            <span class="icon-2"><img src="./Images/Group 1000002798.png"
                                                    alt="Ahsan Munir" /></span>Ahsan Munir
                                        </a>
                                    </td>
                                    <td>3</td>
                                    <td>2</td>
                                    <td>4</td>
                                    <td>0</td>
                                    <td>1</td>
                                    <td>10</td>
                                </tr>
                                <tr>
                                    <td>
                                        <a class="current-status" href="#">
                                            <span class="icon-2"><img src="./Images/Group 1000002881.svg"
                                                    alt="Not Working" /></span>Not Working
                                        </a>
                                    </td>
                                    <td>
                                        <a class="team-member" href="#">
                                            <span class="icon-2"><img src="./Images/Group 1000002791.png"
                                                    alt="Muhammad Umar Zulfiqar" /></span>Muhammad Umar
                                            Zulfiqar
                                        </a>
                                    </td>
                                    <td>3</td>
                                    <td>2</td>
                                    <td>4</td>
                                    <td>0</td>
                                    <td>1</td>
                                    <td>10</td>
                                </tr>
                                <tr>
                                    <td>
                                        <a class="current-status" href="#">
                                            <span class="icon-2"><img src="./Images/Group 1000002821.svg"
                                                    alt="Prayer" /></span>Prayer
                                        </a>
                                    </td>
                                    <td>
                                        <a class="team-member" href="#">
                                            <span class="icon-2"><img src="./Images/Group 1000002811.png"
                                                    alt="Tayyab Afzal" /></span>Tayyab Afzal
                                        </a>
                                    </td>
                                    <td>3</td>
                                    <td>2</td>
                                    <td>4</td>
                                    <td>0</td>
                                    <td>1</td>
                                    <td>10</td>
                                </tr>
                                <!-- End of row repeat -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="unassigned-tasks" class="tab-pane fade in side-pane">
            <div class="card shadow mb-4 mt-4 w-100">
                <div class="card-header p-3 table-heading">
                    <h6>Unassigned Tasks</h6>
                </div>
                <div class="card-body table-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover p-5" id="unassignedTasksTable" width="100%"
                            cellspacing="0">
                            <thead class="table-head">
                                <tr class="table-light">
                                    <th>Project ID</th>
                                    <th>Task Name/Type</th>
                                    <th>Source</th>
                                </tr>
                            </thead>
                            <tbody id="unassignedTasksTableBody">
                                <!-- Data will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Delayed Tasks -->
        <div id="delayed-tasks" class="tab-pane fade in side-pane">
            <div class="card shadow mb-4 mt-4 w-100">
                <div class="card-header p-3 table-heading">
                    <h6>Delayed Tasks</h6>
                </div>
                <div class="card-body table-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover p-5" id="delayedTasksTable" width="100%" cellspacing="0">
                            <thead class="table-head">
                                <tr class="table-light">
                                    <th>Project ID</th>
                                    <th>Task ID</th>
                                    <th>Task Name/Type</th>
                                </tr>
                            </thead>
                            <tbody id="delayedTasksTableBody">
                                <!-- Data will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="project-without-tasks" class="tab-pane fade in side-pane">
            <div class="card shadow mb-4 mt-4 w-100">
                <div class="card-header p-3 table-heading">
                    <h6>Projects without any Tasks</h6>
                </div>
                <div class="card-body table-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover p-5" id="projectsWithoutTasksTable" width="100%"
                            cellspacing="0">
                            <thead class="table-head">
                                <tr class="table-light">
                                    <th>Project ID</th>
                                    <th>Project Name</th>
                                    <th>Source</th>
                                </tr>
                            </thead>
                            <tbody id="projectsWithoutTasksTableBody">
                                <!-- Data will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Escrow Status(Not Funded)-->
        <div id="escrow-status" class="tab-pane fade in side-pane">
            <div class="card shadow mb-4 mt-4  w-100">
                <div class="card-header p-3 table-heading">
                    <h6>Escrow Status(Not Funded)</h6>
                </div>
                <div class="card-body table-body p-0">
                    <div class="table-responsive ">
                        <table class="table table-hover p-5" id="dataTable" width="100%" cellspacing="0">
                            <thead class="table-head">
                                <tr class="table-light">
                                    <th>Project ID</th>
                                    <th>Milestone</th>
                                    <th>Task Name/Type</th>
                                    <th>Source</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Walker Rice, Lifted</td>
                                    <td>Timetable February</td>
                                    <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                    <td>GoMedia Upwork</td>
                                </tr>
                                <tr>
                                    <td>Walker Rice, Lifted</td>
                                    <td>Timetable February</td>
                                    <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                    <td>GoMedia Upwork</td>
                                </tr>
                                <tr>
                                    <td>Walker Rice, Lifted</td>
                                    <td>Timetable February</td>
                                    <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                    <td>GoMedia Upwork</td>
                                </tr>
                                <!-- End of row repeat -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pending Payments-->
        <div id="pending-payments" class="tab-pane fade in side-pane">
            <div class="card shadow mb-4 mt-4 w-100">
                <div class="card-header p-3 table-heading">
                    <h6>Pending Payments</h6>
                </div>
                <div class="card-body table-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover p-5" id="pendingPaymentsTable" width="100%"
                            cellspacing="0">
                            <thead class="table-head">
                                <tr class="table-light">
                                    <th>Project ID</th>
                                    <th>Task Name/Type</th>
                                    <th>Milestone</th>
                                    <th>Source</th>
                                </tr>
                            </thead>
                            <tbody id="pendingPaymentsTableBody">
                                <!-- Data will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Delayed Feedback-->
        <div id="delayed-feedback" class="tab-pane fade in side-pane">
            <div class="card shadow mb-4 mt-4  w-100">
                <div class="card-header p-3 table-heading">
                    <h6>Delayed Feedback</h6>
                </div>
                <div class="card-body table-body p-0">
                    <div class="table-responsive ">
                        <table class="table table-hover p-5" id="dataTable" width="100%" cellspacing="0">
                            <thead class="table-head">
                                <tr class="table-light">
                                    <th>Project ID</th>
                                    <th>Task ID</th>
                                    <th>Task Name/Type</th>
                                    <th>Source</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Orders.1</td>
                                    <td>27809</td>
                                    <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                    <td>GoMedia Upwork</td>
                                </tr>
                                <tr>
                                    <td>Orders.1</td>
                                    <td>27809</td>
                                    <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                    <td>GoMedia Upwork</td>
                                </tr>
                                <tr>
                                    <td>Orders.1</td>
                                    <td>27809</td>
                                    <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                    <td>GoMedia Upwork</td>
                                </tr>
                                <!-- End of row repeat -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Awaiting Rating-->
        <div id="awaiting-rating" class="tab-pane fade in side-pane">
            <div class="card shadow mb-4 mt-4  w-100">
                <div class="card-header p-3 table-heading">
                    <h6>Awaiting Rating</h6>
                </div>
                <div class="card-body table-body p-0">
                    <div class="table-responsive ">
                        <table class="table table-hover p-5" id="dataTable" width="100%" cellspacing="0">
                            <thead class="table-head">
                                <tr class="table-light">
                                    <th>Project ID</th>
                                    <th>Milestone</th>
                                    <th>Task Name/Type</th>
                                    <th>Source</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Walker Rice, Lifted</td>
                                    <td>Timetable February</td>
                                    <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                    <td>GoMedia Upwork</td>
                                </tr>
                                <tr>
                                    <td>Walker Rice, Lifted</td>
                                    <td>Timetable February</td>
                                    <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                    <td>GoMedia Upwork</td>
                                </tr>
                                <tr>
                                    <td>Walker Rice, Lifted</td>
                                    <td>Timetable February</td>
                                    <td>Commercial Demolition Solutions, LLC / Business Card</td>
                                    <td>GoMedia Upwork</td>
                                </tr>
                                <!-- End of row repeat -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                            <h6>Tasks</h6>
                        </div>
                        <div class="p-0">
                            <div class="table-responsive">
                                <table class="table table-hover" id="all-task-table" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>Project ID</th>
                                            <th>Task ID</th>
                                            <th>Task Name</th>
                                            <!--<th>Task Description</th>-->
                                            <th>Task Type</th>
                                            <th>Team</th>
                                            {{-- <th>Task Value</th> --}}
                                            {{-- <th>Start Date</th> --}}
                                            {{-- <th>End Date</th> --}}
                                            <th>Assigned Members</th>
                                            <th>Status</th>
                                            <th>Task Stage</th>
                                            <th>Priority</th>
                                            <th>Author</th>
                                            {{-- <th>Created</th> --}}
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

                updateMemberStats();

                table = $('#all-task-table').DataTable({
                    processing: true,
                    serverSide: true,
                    paging: true,
                    stateSave: true,
                    ajax: {
                        url: "{{ route('myTasks', ['task' => request()->task]) }}",
                        type: 'GET',
                        cache: false,
                        data: function(d) {
                            // Pass search term and pagination details to server
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
                        // { data: 'task_value', name: 'task_value' },
                        // { data: 'start_date', name: 'start_date', render: function(data) {
                        //     return moment(data).format('D MMM, YYYY');
                        // }},
                        // { data: 'end_date', name: 'end_date', render: function(data) {
                        //     return moment(data).format('D MMM, YYYY');
                        // }},
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
                        // { data: 'created_at', name: 'created_at', render: function(data) {
                        //     return moment(data).format('D MMM, YYYY');
                        // }},
                        // Action Column to link to task details
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
                            // Scroll to the task details section
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


            // Public Channel Subscribe
            var channel = pusher.subscribe("dashboard-task");
            var userChannel = pusher.subscribe("user-status");

            channel.bind("dashboard-task-show", function(data) {
                // Reload DataTable
                table.ajax.reload(null, false);
            });

            userChannel.bind("user-status-updated", function(data) {

                toastr.success(`${data.status} ${data.username}`, "Success", {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-top-right",
                    timeOut: 10000,
                });
                updateMemberStats();
            });


            function updateMemberStats() {
                fetch("{{ route('getMemberStats') }}")
                    .then(response => response.json())
                    .then(data => {

                        console.log('Dashboard Member:', data);

                        document.getElementById('active-count').textContent = data.active;
                        document.getElementById('inactive-count').textContent = data.inactive;

                        const avatarsContainer = document.getElementById('avatars-container');
                        avatarsContainer.innerHTML = ''; // Clear previous avatars

                        // Loop through the avatars array (each item contains name and profile_picture)
                        data.avatars.forEach((user) => {
                            const avatarPath = user.profile_picture;
                            const name = user.name; // Get the user's name
                            createAvatarElement(avatarPath, name); // Create avatar element for each user
                        });

                        function createAvatarElement(avatarPath, name) {
                            const avatarWrapper = document.createElement('div');
                            avatarWrapper.classList.add('avatar-wrapper'); // You can use this to style the avatar container

                            const avatarImg = document.createElement('img');
                            avatarImg.src = avatarPath ? `storage/app/public/${avatarPath}` :
                                './public/user.png'; // Use default if no avatar
                            avatarImg.alt = name || 'User'; // Use name as alt text
                            avatarImg.classList.add('rounded-circle', 'img-fluid', 'mx-1');

                            // Set a fallback image if there's an error loading the image
                            avatarImg.onerror = function() {
                                this.onerror = null; // Prevent infinite loop
                                this.src = './public/user.png'; // Fallback image
                            };

                            // Create a tooltip to show the user name on hover
                            const tooltip = document.createElement('span');
                            tooltip.classList.add('tooltip');
                            tooltip.textContent = name || 'User'; // Show the user's name in the tooltip

                            avatarWrapper.appendChild(avatarImg);
                            avatarWrapper.appendChild(tooltip);

                            avatarsContainer.appendChild(avatarWrapper);
                        }
                    });
            }
        </script>
        <script>
            $(document).ready(function() {
                $(".side-pane").addClass("d-none");
                var tabId = $(this).attr("href");
                // Show the clicked tab
                $(tabId).removeClass("d-none").addClass("show active");
                // Check if the 'All Projects' tab (or any other specific tab) is clicked
                if (["#all-project", "#new-project", "#in-working", "#department-management",
                        "#team-management", "#add-member", "#client-management", "#add-client",
                        "#add-source", "#add-new-source", "#reports", "#project-details", "#add-new-task",
                        "#completed-task", "#in-completed-task", "#high-priority-task", "#red-flag",
                        "#knowledge-base", "#members", "#edit-task"
                    ].includes(tabId)) {
                    $(".split").hide(); // Hide the .split section when these tabs are active
                } else {
                    $(".split").show(); // Show the .split section for other tabs
                }
            });
            // Event listener for sidebar clicks
            $(".side-bar").click(function(event) {
                event.preventDefault(); // Prevent default anchor behavior
                var sideId = $(this).attr("href");
                $(".side-pane").addClass("d-none");
                $(".split").addClass("d-none");
                $(".side-bar").removeClass("active");
                $(this).addClass("active");

                $(sideId).removeClass("d-none").addClass("show active");
            });

            // Logo click to reload page
            $("#logo").click(function() {
                location.reload();
            });
            // Unassigned Task
            function fetchUnassignedTasks() {
                $.ajax({
                    url: "./unassigned-tasks",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        addTasksToTable(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching tasks:", {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        $("#unassignedTasksTableBody").html(
                            '<tr><td colspan="2" class="text-center text-danger">Error loading tasks</td></tr>'
                        );
                    }
                });
            }

            function addTasksToTable(tasks) {
                let tableBody = $("#unassignedTasksTableBody");
                tableBody.empty();

                if (Array.isArray(tasks) && tasks.length > 0) {
                    tasks.forEach((task) => {
                        let row = `
                    <tr id="taskRow_${task.task_id}">
                        <td id="taskId_${task.task_id}">${task.task_id}</td>
                        <td id="taskName_${task.task_id}">${task.task_name}</td>
                        <td id="sourceName_${task.task_id}">${task.source_name || 'N/A'}
                    </tr>
                `;
                        tableBody.append(row);
                    });
                } else {
                    tableBody.append(
                        '<tr><td colspan="2" class="text-center">No unassigned tasks found</td></tr>'
                    );
                }
            }
            fetchUnassignedTasks();
            // Delayed Task
            function fetchDelayedTasks() {
                $.ajax({
                    url: "./delayed-tasks",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        addDelayedTasksToTable(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching delayed tasks:", {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        $("#delayedTasksTableBody").html(
                            '<tr><td colspan="4" class="text-center text-danger">Error loading delayed tasks</td></tr>'
                        );
                    }
                });
            }

            function addDelayedTasksToTable(tasks) {
                let tableBody = $("#delayedTasksTableBody");
                tableBody.empty();

                if (Array.isArray(tasks) && tasks.length > 0) {
                    tasks.forEach((task) => {
                        let row = `
                <tr id="taskRow_${task.task_id}">
                    <td id="projectId_${task.task_id}">${task.project_id}</td>
                    <td id="taskId_${task.task_id}">${task.task_id}</td>
                    <td id="taskName_${task.task_id}">${task.task_name}</td>
                </tr>
            `;
                        tableBody.append(row);
                    });
                } else {
                    tableBody.append(
                        '<tr><td colspan="4" class="text-center">No delayed tasks found</td></tr>' // Adjust colspan to 4
                    );
                }
            }
            fetchDelayedTasks();
            // Function to fetch projects without tasks
            function fetchProjectsWithoutTasks() {
                $.ajax({
                    url: "./projects-without-tasks",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        addProjectsWithoutTasksToTable(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching projects without tasks:", {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        $("#projectsWithoutTasksTableBody").html(
                            '<tr><td colspan="3" class="text-center text-danger">Error loading projects</td></tr>'
                        );
                    }
                });
            }

            function addProjectsWithoutTasksToTable(projects) {
                let tableBody = $("#projectsWithoutTasksTableBody");
                tableBody.empty();

                if (Array.isArray(projects) && projects.length > 0) {
                    projects.forEach((project) => {
                        let row = `
                <tr id="projectRow_${project.project_id}">
                    <td id="projectId_${project.project_id}">${project.project_id}</td>
                    <td id="projectName_${project.project_id}">${project.project_name}</td>
                    <td id="sourceName_${project.project_id}">${project.source_name || 'N/A'}</td>
                </tr>
            `;
                        tableBody.append(row);
                    });
                } else {
                    tableBody.append(
                        '<tr><td colspan="3" class="text-center">No projects without tasks found</td></tr>'
                    );
                }
            }
            fetchProjectsWithoutTasks();
            // Function to fetch pending payments
            function fetchPendingPayments() {
                $.ajax({
                    url: "./pending-payments", 
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        addPendingPaymentsToTable(response); 
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching pending payments:", {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        $("#pendingPaymentsTableBody").html(
                            '<tr><td colspan="4" class="text-center text-danger">Error loading pending payments</td></tr>'
                        );
                    }
                });
            }
            function addPendingPaymentsToTable(payments) {
                let tableBody = $("#pendingPaymentsTableBody");
                tableBody.empty(); 

                if (Array.isArray(payments) && payments.length > 0) {
                    payments.forEach((payment) => {
                        let row = `
                <tr id="paymentRow_${payment.payment_id}">
                    <td id="projectId_${payment.project_id}">${payment.project_id}</td>
                    <td id="taskName_${payment.payment_id}">${payment.title || 'N/A'}</td>
                    <td id="milestone_${payment.payment_id}">${payment.remaining_payment}</td> <!-- Assuming this is the milestone -->
                    <td id="sourceName_${payment.payment_id}">${payment.source_name || 'N/A'}</td>
                </tr>
            `;
                        tableBody.append(row);
                    });
                } else {
                    tableBody.append(
                        '<tr><td colspan="4" class="text-center">No pending payments found</td></tr>'
                    );
                }
            }
            fetchPendingPayments();
        </script>
    @endsection
</x-app-layout>
