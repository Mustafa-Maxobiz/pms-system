<x-app-layout>
    <div id="team-management" class="mb-4 mt-4 split">
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
                        <div class="card-header table-heading p-3 d-flex justify-content-between align-items-center">
                            <h6>Team Target</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">

                                <div class="col-md-12">
                                    <label for="team" class="form-label">Teams:</label>
                                    <select class="form-select select2" id="team"
                                        onchange="fetchTeams(this.value)">
                                        {{-- <option value="">All</option> --}}
                                        <option value="" selected disabled>Select Team</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}"
                                                data-department-id="{{ $team->department_id }}">{{ $team->name }}
                                            </option>
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
            function fetchTeams(teamId) {

                $("#loadingSpinner").show(); // Loading spinner show کریں

                $.ajax({
                    url: '{{ route('target.get.target.teams') }}',
                    type: 'GET',
                    data: {
                        team_id: teamId
                    },
                    dataType: 'json',
                    success: function(response) {
                        $("#loadingSpinner").hide();

                        if (response.success) {
                            renderTeams(response.teams);
                        } else {
                            $("#teams-container").html('<p class="text-danger">No team found.</p>');
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#loadingSpinner").hide();
                        console.error("Error:", error);
                    }
                });
            }

            function renderTeams(teams) {
                let teamsContainer = $("#teams-container");
                teamsContainer.empty();

                teams.forEach(team => {
                    let teamCard = `
                        <div class="col-md-12">
                            <div class="card shadow">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 col-6">${team.name}</h6>
                                    <div class="search-container">
                                        <input type="text" class="form-control-sm team-search" placeholder="Search" data-team-id="${team.id}" oninput="filterUsers(this)">
                                    </div>
                                </div>
                                <div class="card-body table-body">
                                    <ul class="list-group list-group-flush" id="team-users-${team.id}">
                                        ${renderUsers(team)}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `;

                    teamsContainer.append(teamCard);
                });
            }

            function renderUsers(team) {
                return `
                    <form id="targetForm-${team.id}" class="target-form">
                        ${team.users.map(user => {
                            const department = team.department ? team.department.name : 'No Department';
                            const profileImage = user.profile_picture ? `../storage/app/public/${user.profile_picture}` : './public/no-image.png';
                            const roles = user.roles.map(role => role.name).join(', ');
                            const userTarget = team.target.find(target => target.user_id === user.id);

                            return `
                                <li class="list-group-item border-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <img src="${profileImage}" alt="Profile" class="rounded-circle me-3" width="40" height="40">
                                            <div>
                                                <strong><a href="./users/${user.id}/edit">${user.name}</a></strong> (${roles})
                                                <br>
                                                <span class="badge bg-orange">${department}</span>
                                            </div>
                                        </div>
                                        <div class="target-inputs d-flex">
                                            <input type="hidden" name="user_id[]" value="${user.id}">
                                            <input type="hidden" name="team_id[]" value="${team.id}">
                                            ${userTarget ? `<input type="hidden" name="target_id[]" value="${userTarget.id}">` : ''}
                                            <input type="number" class="form-control me-2" name="target_amount[]" step="any" placeholder="Target" value="${userTarget ? userTarget.target_amount : ''}" required>
                                            <input type="number" class="form-control me-2" name="hours[]" step="any" placeholder="Hours" value="${userTarget ? userTarget.hours : ''}" required>
                                        </div>
                                    </div>
                                </li>
                            `;
                            }).join('')}
                            
                            <!-- "Save All" Button (Only Once at the Bottom) -->
                            <div class="text-end mt-3">
                                <button type="button" class="btn btn-primary btn-sm" onclick="saveAllTargets(${team.id}, this)">
                                    Save All
                                </button>
                            </div>
                        </form>
                    `;
            }


            function getTargetForm(user, team) {
                const userTarget = team.target.find(target => target.user_id === user.id);

                return `
                        <div class="row">
                            <div class="col-md-12">
                                <form id="targetForm" class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="user_id" value="${user.id}">
                                    <input type="hidden" name="team_id" value="${team.id}">
                                    ${userTarget ? `<input type="hidden" name="target_id" value="${userTarget.id}">` : ''}
                                    <input type="number" class="form-control me-2" name="target_amount" step="any" placeholder="Target" value="${userTarget ? userTarget.target_amount : ''}" required>
                                    <input type="number" class="form-control me-2" name="hours" step="any" placeholder="Hours" value="${userTarget ? userTarget.hours : ''}" required>
                                    <button type="button" class="btn ${userTarget ? 'btn-success' : 'btn-info'} btn-sm col-3" onclick="${userTarget ? `updateTarget(${team.id}, this)` : `setTarget(${team.id}, this)`}">
                                        ${userTarget ? 'Update Target' : 'Set Target'}
                                    </button>
                                </form>
                            </div>
                        </div>
                    `;
            }


            function filterUsers(input) {
                let searchText = input.value.toLowerCase(); // User input ko lowercase me convert karein
                let teamId = input.getAttribute("data-team-id"); // Team ka ID lein

                // Team ke users list ko access karein
                let users = document.querySelectorAll(`#team-users-${teamId} li`);

                users.forEach(user => {
                    let userName = user.querySelector("strong a").textContent.toLowerCase(); // User ka naam lein

                    if (userName.includes(searchText)) {
                        user.style.display = ""; // Agar match kare to show karein
                    } else {
                        user.style.display = "none"; // Nahi to hide karein
                    }
                });
            }

            // function setTarget(teamId, buttonElement) {
            //     var button = $(buttonElement);
            //     button.prop("disabled", true).text("Waiting...");
            //     var formData = $("#setTargetForm").serialize();

            //     $.ajax({
            //         url: "{{ route('target.store') }}", // Laravel route
            //         type: "POST",
            //         data: formData,
            //         headers: {
            //             'X-CSRF-TOKEN': $('input[name="_token"]').val()
            //         }, // CSRF token bhejna
            //         success: function(response) {
            //             fetchTeams(teamId);
            //             setTimeout(() => {
            //                 button.prop("disabled", false).text("Set Target");
            //             }, 2000);
            //         },
            //         error: function(xhr) {
            //             console.log('Error');
            //             button.prop("disabled", false).text("Set Target");

            //         }
            //     });
            // }

            // function updateTarget(teamId, buttonElement) {
            //     var button = $(buttonElement);
            //     button.prop("disabled", true).text("Waiting...");

            //     var form = $(buttonElement).closest("form");
            //     var formUpdateData = form.serialize();

            //     $.ajax({
            //         url: "{{ route('target.update') }}", // Laravel route
            //         type: "POST",
            //         data: formUpdateData,
            //         headers: {
            //             'X-CSRF-TOKEN': $('input[name="_token"]').val()
            //         },
            //         success: function(response) {
            //             fetchTeams(teamId);
            //             setTimeout(() => {
            //                 button.prop("disabled", false).text("Update Target");
            //             }, 2000);
            //         },
            //         error: function(xhr) {
            //             console.log('Error');
            //             button.prop("disabled", false).text("Update Target");
            //         }
            //     });
            // }

            function saveAllTargets(teamId) {
                const form = $(`#targetForm-${teamId}`);
                const formData = form.serialize();
                const saveButton = form.find("button");

                saveButton.prop("disabled", true).text("Waiting...");

                $.ajax({
                    url: "{{ route('target.save.all.targets') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        "X-CSRF-TOKEN": $('input[name="_token"]').val()
                    },
                    success: function(response) {
                        saveButton.prop("disabled", false).text("Save All");
                        fetchTeams(teamId);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        saveButton.prop("disabled", false).text("Save All");
                        alert("Something went wrong! Please try again.");
                    }
                });
            }



            $(document).ready(function() {
                var savedTeam = sessionStorage.getItem("selectedTeam");
                var $teamSelect = $("#team");

                if (savedTeam) {
                    $teamSelect.val(savedTeam);
                    fetchTeams(savedTeam);
                } else {
                    // Select the first available team (skipping the disabled "Select Team" option)
                    if ($teamSelect.find("option").length > 1) {
                        var firstTeamValue = $teamSelect.find("option:not(:disabled)").first().val();
                        $teamSelect.val(firstTeamValue).trigger("change"); // Trigger change event to call fetchTeams
                    }
                }

                // When dropdown value changes, save it in sessionStorage
                $teamSelect.on("change", function() {
                    var selectedTeam = $(this).val();
                    sessionStorage.setItem("selectedTeam", selectedTeam);
                });
            });
        </script>
    @endsection
</x-app-layout>
