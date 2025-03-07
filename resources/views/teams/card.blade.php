<x-app-layout>
    <div id="team-management" class="mb-4 mt-4 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header table-heading p-3 d-flex justify-content-between align-items-center">
                            <h6>Team Management</h6>
                            <div class="d-flex gap-2">
                                @can('Add New User')
                                    <a href="{{ route('users.create') }}" class="btn btn-dark"><i class="fa fa-plus"></i> Add
                                        New Member</a>
                                @endcan
                                @can('Add New Team')
                                    <a href="{{ route('teams.create') }}" class="btn btn-dark"><i class="fa fa-plus"></i>
                                        Add New Team</a>
                                @endcan
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="department" class="form-label">Department:</label>
                                    <select class="form-select" id="department">
                                        <option value="">All</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="team" class="form-label">Teams:</label>
                                    <select class="form-select" id="team">
                                        <option value="">All</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}"
                                                data-department-id="{{ $team->department_id }}">{{ $team->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="member" class="form-label">Member:</label>
                                    <select class="form-select" id="member">
                                        <option value="">All</option>
                                        @foreach ($members as $member)
                                            <option value="{{ $member->id }}" data-team-id="{{ $member->team_id }}">
                                                {{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="teams-container" class="row">
                                <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const departmentFilter = document.getElementById("department");
                const teamFilter = document.getElementById("team");
                const memberFilter = document.getElementById("member");
                const teamCardsContainer = document.getElementById("teams-container");

                let allTeams = [];
                fetch(`./teams/get-teams`)
                .then((response) => response.json())
                .then((teams) => {
                    allTeams = teams;
                    renderTeams(allTeams);
                });


                function filterTeamsByDepartment() {
                    const selectedDepartmentId = departmentFilter.value;
                    const teamOptions = teamFilter.querySelectorAll("option");

                    teamOptions.forEach(option => {
                        const teamDepartmentId = option.getAttribute("data-department-id");
                        if (selectedDepartmentId === "" || teamDepartmentId === selectedDepartmentId) {
                            option.style.display = "block";
                        } else {
                            option.style.display = "none";
                        }
                    });
                    teamFilter.value = "";
                    filterMembersByTeam();
                }

                function filterMembersByTeam() {
                    const selectedTeamId = teamFilter.value;
                    const memberOptions = memberFilter.querySelectorAll("option");

                    memberOptions.forEach(option => {
                        const memberTeamId = option.getAttribute("data-team-id");
                        if (selectedTeamId === "" || memberTeamId === selectedTeamId) {
                            option.style.display = "block";
                        } else {
                            option.style.display = "none";
                        }
                    });

                    filterAndRenderTeams();
                }

                function filterAndRenderTeams() {
                    const selectedDepartmentId = departmentFilter.value;
                    const selectedTeamId = teamFilter.value;
                    const selectedMemberId = memberFilter.value;

                    const filteredTeams = allTeams.filter(team => {
                        if (selectedDepartmentId && team.department && team.department.id != selectedDepartmentId) {
                            return false;
                        }

                        if (selectedTeamId && team.id != selectedTeamId) {
                            return false;
                        }
                        if (selectedMemberId && !team.users.some(user => user.id == selectedMemberId)) {
                            return false;
                        }
                        return true;
                    });

                    renderTeams(filteredTeams);
                }

                function renderTeams(teams) {
                    teamCardsContainer.innerHTML = '';
                    teams.forEach((team) => {
                        const editRoute = `{{ url('teams') }}/${team.id}/edit`;
                        const deleteFormId = `${team.id}`;
                        const deleteRoute = `
                            <a href="#" class="btn btn-warning btn-sm p-1" title="Delete" onclick="confirmDelete('${deleteFormId}')">
                                <i class="fa fa-trash"></i>
                            </a>
                            <form id="delete-form-${deleteFormId}" action="{{ url('teams') }}/${team.id}" method="POST" style="display:none;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </form>`;
                        const teamCard = `
                            <div class="col-md-4 mb-4">
                                <div class="card shadow">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div class="table-heading bg-transparent col-6">
                                            <h6 class="mb-0">${team.name}
                                            <div class="btn-group" role="group" aria-label="Btn Group">
                                            @can('Edit Team')
                                                <a href="${editRoute}" class="btn btn-dark btn-sm p-1"><i class="fa fa-edit"></i></a>
                                            @endcan
                                            @can('Delete Team')
                                                ${deleteRoute}
                                            @endcan
                                            </div>
                                            </h6>
                                        </div>
                                        <div class="search-container">
                                            <input
                                                type="text"
                                                class="form-control-sm team-search"
                                                id="search-${team.id}"
                                                placeholder="Search"
                                                data-team-id="${team.id}"
                                            />
                                        </div>
                                    </div>
                                    <div class="card-body table-body">
                                        <ul class="list-group list-group-flush" id="team-users-${team.id}">
                                            ${team.users.map((user) => {
                                                const department = team.department ? team.department.name : 'No Department';
                                                return `
                                                        <li class="list-group-item border-0">
                                                            <div class="d-flex align-items-center">
                                                                <img
                                                                    src="${'storage/app/public/' + user.profile_picture}"
                                                                    alt="Profile"
                                                                    onerror="this.src='./public/no-image.png'"
                                                                    class="rounded-circle me-3"
                                                                    width="40"
                                                                    height="40"
                                                                />
                                                                <div>
                                                                    <strong><a href="./users/${user.id}/edit">${user.name}</a></strong> (${user.roles.map((role) => role.name).join(', ')})
                                                                    <br />
                                                                    <span class="bg-orange">${department}</span>
                                                                </div>
                                                            </div>
                                                        </li>`;
                                            }).join('')}
                                        </ul>
                                    </div>
                                </div>
                            </div>`;

                        teamCardsContainer.insertAdjacentHTML("beforeend", teamCard);
                    });

                    attachSearchListeners(teams);
                }

                function attachSearchListeners(teams) {
                    teams.forEach((team) => {
                        const searchInput = document.getElementById(`search-${team.id}`);
                        if (searchInput) {
                            searchInput.addEventListener("input", function () {
                                const searchTerm = this.value.toLowerCase();
                                const userList = document.getElementById(`team-users-${team.id}`);
                                if (userList) {
                                    const users = userList.querySelectorAll("li");
                                    users.forEach((user) => {
                                        const userName = user.textContent.toLowerCase();
                                        user.style.display = userName.includes(searchTerm) ? "block" : "none";
                                    });
                                }
                            });
                        }
                    });
                }

                departmentFilter.addEventListener("change", filterTeamsByDepartment);
                teamFilter.addEventListener("change", filterMembersByTeam);
                memberFilter.addEventListener("change", filterAndRenderTeams);
                filterTeamsByDepartment();
            });
            


            function confirmDelete(deleteFormId) {
                if (confirm("Are you sure you want to delete this Team?")) {
                    document.getElementById('delete-form-' + deleteFormId).submit();
                }
            }
        </script>
    @endsection
</x-app-layout>
