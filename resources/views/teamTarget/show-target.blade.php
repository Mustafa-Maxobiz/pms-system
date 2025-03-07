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

                                <div class="col-md-2">
                                    <label for="">From</label>
                                    <input type="date" name="from_date" id="fromDate" class="form-control">
                                </div>

                                <div class="col-md-2">
                                    <label for="">To</label>
                                    <input type="date" name="to_date" id="toDate" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label for="team" class="form-label">Department:</label>
                                    <select class="form-select" id="department" onchange="getDepTeam(this.value)">
                                        {{-- <option value="">All</option> --}}
                                        <option value="" selected disabled>Select Team</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label for="team" class="form-label">Teams:</label>
                                    <select class="form-select" id="team" onchange="fetchTeams(this.value)">
                                        {{-- <option value="">All</option> --}}
                                        <option value="" selected disabled>Select Team</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}"
                                                data-department-id="{{ $team->department_id }}">{{ $team->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <canvas id="pieChartTeamPrgress" style="width: 200px; height: 100px;"></canvas>
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
            function getDepTeam(departmentId) {
                $.ajax({
                    url: '{{ route('target.get.dep.teams') }}',
                    type: 'GET',
                    data: {
                        department_id: departmentId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.data.length > 0) {
                            let teamDropdown = $('#team');
                            teamDropdown.empty();
                            teamDropdown.append('<option value="" selected disabled>Select Team</option>');

                            $.each(response.data, function(index, team) {
                                teamDropdown.append(`<option value="${team.id}">${team.name}</option>`);
                            });

                            // teamDropdown.trigger('change');
                        } else {
                            alert('No teams found for the selected department.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                    }
                });
            }

            function fetchTeams(teamId) {

                var fromDate = document.getElementById('fromDate').value;
                var toDate = document.getElementById('toDate').value;

                getTeamProgress(teamId, fromDate, toDate);

                $("#loadingSpinner").show();

                $.ajax({
                    url: '{{ route('target.get.target.teams') }}',
                    type: 'GET',
                    data: {
                        team_id: teamId,
                        from_date: fromDate,
                        to_date: toDate,
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
                return team.users.map(user => {
                    const department = team.department ? team.department.name : 'No Department';
                    const profileImage = user.profile_picture ? `../storage/app/public/${user.profile_picture}` :
                        '../public/no-image.png';

                    console.log("Profile Image is ", profileImage);


                    const roles = user.roles.map(role => role.name).join(', ');
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
                                        ${getTargetForm(user, team)}
                                </div>
                            </li>
                        `;
                }).join('');
            }

            function getTargetForm(user, team) {
                const userTargets = team.target
                    .filter(target => target.user_id === user.id)
                    .map(target => ({
                        id: target.id,
                        target_amount: target.target_amount,
                        task_value_total: target.task_value_total,
                        achievement_percentage: target.achievement_percentage
                    }));

                let formHtml = `<div class="row">`;

                userTargets.forEach(target => {
                    let percentage = target.achievement_percentage;

                    formHtml += `
                            <div class="col-md-12 d-flex align-items-center">
                                <strong>Target:</strong> <span>&nbsp;${target.target_amount}</span>
                                <br>
                                <strong style="margin-left: 13px;">Achieved: </strong> <span> &nbsp;${target.task_value_total}</span>
                                <!-- Pie Chart Container -->
                                <canvas id="pieChart-${user.id}-${target.id}" width="70" height="70"></canvas>
                            </div>
                        `;

                    setTimeout(() => {
                        drawChart(user.id, target.id, percentage);
                    }, 500);
                });

                formHtml += `</div>`;
                return formHtml;
            }

            var charts = {}; // Store charts globally to prevent duplication

            function drawChart(userId, targetId, percentage) {
                var canvasId = `pieChart-${userId}-${targetId}`;
                var ctx = document.getElementById(canvasId)?.getContext('2d');

                if (!ctx) {
                    console.error(`Canvas context not found for ${canvasId}!`);
                    return;
                }

                var adjustedPercentage = Math.min(percentage, 200);
                var maxValue = Math.max(adjustedPercentage, 100);

                var achieved = adjustedPercentage;
                var remaining = Math.max(maxValue - adjustedPercentage, 0);

                // **Destroy previous chart if exists (prevents duplication)**
                if (charts[canvasId]) {
                    charts[canvasId].destroy();
                }

                // **Create new chart**
                charts[canvasId] = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        // labels: ['Achieved', 'Remaining'],
                        datasets: [{
                            data: [achieved, remaining],
                            backgroundColor: ['#4caf50', '#f44336']
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        rotation: 0,
                        circumference: 360,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        let value = tooltipItem.raw.toFixed(1) + '%';
                                        return `${value}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }


            // function drawChart(userId, targetId, percentage) {
            //     google.charts.load("current", {
            //         packages: ["corechart"]
            //     });
            //     google.charts.setOnLoadCallback(function() {
            //         var adjustedPercentage = Math.min(percentage, 200); // Limit to max 200%
            //         var maxValue = Math.max(adjustedPercentage, 100); // Ensure dynamic scaling

            //         var data = google.visualization.arrayToDataTable([
            //             ['Category', 'Value'],
            //             ['Achieved', adjustedPercentage], // Allow up to 200%
            //             ['Remaining', Math.max(maxValue - adjustedPercentage, 0)]
            //         ]);

            //         var options = {
            //             title: 'Progress',
            //             pieHole: 0.4,
            //             colors: ['#4caf50', '#f44336'],
            //             pieSliceText: 'percentage',
            //             pieStartAngle: 270
            //         };

            //         var chart = new google.visualization.PieChart(document.getElementById(
            //             `piechart-${userId}-${targetId}`));
            //         chart.draw(data, options);
            //     });
            // }

            function getTeamProgress(teamId, fromDate, toDate) {

                $.ajax({
                    url: '{{ route('target.get.team.progress') }}',
                    type: 'GET',
                    data: {
                        team_id: teamId,
                        from_date: fromDate,
                        to_date: toDate,
                    },
                    dataType: 'json',
                    success: function(response) {
                        drawSimplePieChart(response.completed, response.remaining);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                    }
                });
            }

            var ctx = document.getElementById('pieChartTeamPrgress').getContext('2d');

            function drawSimplePieChart(completed, remaining) {

                var scaledCompleted = Math.min(completed, 100) + (completed > 100 ? (completed - 100) / 5 : 0);
                var scaledRemaining = Math.max(0, 100 - scaledCompleted);

                if (window.myChart) {
                    window.myChart.destroy(); // Pehle wala chart delete karna zaroori hai warna overlay hoga
                }

                window.myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Completed', 'Remaining'],
                        datasets: [{
                            data: [scaledCompleted, scaledRemaining],
                            backgroundColor: ['#4caf50', '#f44336']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        let value = tooltipItem.raw.toFixed(1) + '%';
                                        return `${value}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function filterUsers(input) {
                let searchText = input.value.toLowerCase(); // User input ko lowercase me convert karein
                let teamId = input.getAttribute("data-team-id"); // Team ka ID lein

                // Team ke users list ko access karein
                let users = document.querySelectorAll(`#team-users-${teamId} li`);

                users.forEach(user => {
                    let userName = user.querySelector("strong a").textContent.toLowerCase();

                    if (userName.includes(searchText)) {
                        user.style.display = ""; // Agar match kare to show karein
                    } else {
                        user.style.display = "none"; // Nahi to hide karein
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
                    // if ($teamSelect.find("option").length > 1) {
                    //     var firstTeamValue = $teamSelect.find("option:not(:disabled)").first().val();
                    //     $teamSelect.val(firstTeamValue).trigger("change"); // Trigger change event to call fetchTeams
                    // }
                }

                // When dropdown value changes, save it in sessionStorage
                $teamSelect.on("change", function() {
                    var selectedTeam = $(this).val();
                    sessionStorage.setItem("selectedTeam", selectedTeam);
                });
            });


            // Get the current date in YYYY-MM-DD format
            let today = new Date().toISOString().split('T')[0];

            // Set default value to the current date
            document.getElementById("fromDate").value = today;
            document.getElementById("toDate").value = today;

            document.getElementById("fromDate").addEventListener("change", resetFilters);
            document.getElementById("toDate").addEventListener("change", resetFilters);

            function resetFilters() {
                // Reset department dropdown
                let department = document.getElementById("department");
                department.value = ""; // Reset value
                department.querySelectorAll("option").forEach(option => option.removeAttribute("selected"));
                department.querySelector("option[disabled]").setAttribute("selected", true);

                // Reset team dropdown
                let team = document.getElementById("team");
                team.value = ""; // Reset value
                team.querySelectorAll("option").forEach(option => option.removeAttribute("selected"));
                team.querySelector("option[disabled]").setAttribute("selected", true);
            }
        </script>
    @endsection
</x-app-layout>
