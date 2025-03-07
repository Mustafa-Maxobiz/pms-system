<x-app-layout>
    <div id="members">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Card Container -->
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-body table-body pt-5">
                            <div class="row" id="teams-container">
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
                const teamTablesContainer = document.getElementById("teams-container");
                const loadingSpinner = document.getElementById("loadingSpinner");

                function loadTeams() {
                    const queryParams = new URLSearchParams();

                    // Show loading spinner
                    loadingSpinner.style.display = 'block';

                    fetch(`./get-teams?${queryParams}`)
                        .then((response) => response.json())
                        .then((teams) => {
                            // Hide loading spinner after data is fetched
                            loadingSpinner.style.display = 'none';
                            teamTablesContainer.innerHTML = '';

                            teams.forEach((team) => {
                                const teamTable = `
                                <div class="col-6 col-md-6 mb-4">
                                    <div class="card shadow p-0">
                                        <div class="card-header p-3 table-heading d-flex justify-content-between">
                                            <h5> ${team.name} - ${team.department.name}</h5>
                                        </div>
                                        <div class="card-body table-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="team-table-${team.id}" data-team-id="${team.id}">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Status</th>
                                                            <th>Total Tasks</th>
                                                            <th>URL</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="team-users-${team.id}">
                                                        ${team.users.map((user) => ` 
                                                                            <tr>
                                                                                <td>
                                                                                    <a href="./users/${user.id}/edit">
                                                                                        <img
                                                                                            src="${'storage/app/public/' + user.avatar}"
                                                                                            alt="Profile"
                                                                                            onerror="this.src='./public/no-image.png'"
                                                                                            class="rounded-circle me-2"
                                                                                            width="40"
                                                                                            height="40"
                                                                                        />
                                                                                        ${user.name}
                                                                                    </a>
                                                                                </td>
                                                                                <td>
                                                                                    <a href="#" class="status" id="${user.logs && user.logs.length > 0 && user.logs[0].status != 'Offline' ? 'active' : 'offline'}">
                                                                                        <span>${user.logs && user.logs.length > 0 ? user.logs[0].status : 'Offline'}</span>
                                                                                    </a>
                                                                                </td>
                                                                                <td>${user.tasks ? user.tasks.length : 0}</td>
                                                                                <td><a href="#" target="_blank" class="text-decoration-none"><i class="fa fa-eye fa-2x"></i></a></td>
                                                                            </tr>`).join('')}
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                                teamTablesContainer.insertAdjacentHTML("beforeend", teamTable);

                                // Initialize DataTable for the newly created table
                                $(document).ready(function() {
                                    $('#team-table-' + team.id).DataTable({
                                        searching: true, // Enable search
                                        paging: true, // Enable paging
                                        info: true, // Enable info display
                                        autoWidth: true, // Enable auto width adjustment for columns
                                    });
                                });
                            });
                        });
                }


                loadTeams();



                Pusher.logToConsole = true;

                var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
                    cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
                    forceTLS: true
                });


                // Public Channel Subscribe
                var channel = pusher.subscribe("member");


                channel.bind("user-status-updated", function(data) {
                    console.log(data.team_id);
                    
                    // Find the table with the corresponding data-team-id
                    const teamTable = document.getElementById(`team-table-'${data.team_id}']`);

                    if (teamTable) {
                        // Reload DataTable only for this team
                        $(teamTable).DataTable().ajax.reload(null, false);
                    }
                    loadTeams();
                });

            });
        </script>
    @endsection
</x-app-layout>
