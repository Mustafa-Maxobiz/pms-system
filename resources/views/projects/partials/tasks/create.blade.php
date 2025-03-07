<x-app-layout>
    <div id="add-task" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Add New Task</h6>
                        </div>
                        <form class="p-4" method="POST"
                            action="{{ route('projects.tasks.store', ['project' => $project->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="project_id" class="form-label">Project ID</label>
                                            <input type="text"
                                                class="form-control @error('project_id') is-invalid @enderror"
                                                id="project_id" name="project_id" placeholder="Project ID:"
                                                value="{{ $project->id }}" readonly />
                                            @error('project_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3 d-none">
                                            <!-- Timer Display Row -->
                                            <label for="startPauseBtn" class="form-label"></label>
                                            <div class="start-timer-row d-flex align-items-center">
                                                <button id="startPauseBtn" class="btn btn-primary me-2" type="button">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <div id="timer" class="h2">00:00:00</div>
                                                <button id="resetBtn" class="btn btn-danger ms-2" type="button">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="task_name" class="form-label">Task Name</label>
                                            <input type="text"
                                                class="form-control @error('task_name') is-invalid @enderror"
                                                id="task_name" name="task_name" placeholder="Task Name:"
                                                value="{{ old('task_name') }}" />
                                            @error('task_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <button type="button" id="show-sub-task"
                                                class="btn btn-primary p-2 float-end"><i class="fa fa-plus"></i> Add Sub
                                                Task</button>
                                        </div>
                                        <div class="subTask row m-0 p-0">
                                            <div id="subtask-container">
                                                <!-- Example Subtask -->
                                                <div class="subtask-item row mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Sub Task Name</label>
                                                        <input type="text" class="form-control"
                                                            name="sub_tasks[0][name]" placeholder="Sub Task Name" />
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="form-label">Sub Task Value</label>
                                                        <input type="number" class="form-control sub_tasks_values"
                                                            name="sub_tasks[0][value]" placeholder="Sub Task Value" />
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label class="form-label">&nbsp;</label>
                                                        <button type="button" class="btn btn-dark remove-subtask"><i
                                                                class="fa fa-trash"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="button" id="add-subtask" class="btn btn-primary p-2"><i
                                                        class="fa fa-plus"></i> Add More</button>
                                            </div>
                                            <hr class="my-3">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="task_type" class="form-label">Task Type</label>
                                            <input type="text"
                                                class="form-control @error('task_type') is-invalid @enderror"
                                                id="task_type" name="task_type" value="{{ old('task_type') }}"
                                                placeholder="Start typing to search task types..." autocomplete="off" />
                                            <div id="task_type_suggestions" class="list-group"
                                                style="position: absolute; z-index: 1000; display: none;"></div>
                                            @error('task_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="task_type" class="form-label">Task Avg Time</label>
                                            <input type="number"
                                                class="form-control @error('evg_time') is-invalid @enderror"
                                                id="evg_time" name="evg_time" value="{{ old('evg_time') }}"
                                                placeholder="Avg Time" autocomplete="off" />
                                            @error('evg_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="task_value" class="form-label">Task Value</label>
                                            <input type="number"
                                                class="form-control task_value @error('task_value') is-invalid @enderror"
                                                id="task_value" name="task_value" placeholder="Task Value:"
                                                value="{{ old('task_value') }}" />
                                            @error('task_value')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date"
                                                class="form-control @error('start_date') is-invalid @enderror"
                                                id="start_date" name="start_date" placeholder="Start Date:"
                                                value="{{ old('start_date', date('Y-m-d')) }}" />
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date"
                                                class="form-control @error('end_date') is-invalid @enderror"
                                                id="end_date" name="end_date" placeholder="End Date:"
                                                value="{{ old('end_date') }}" />
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="task_status" class="form-label">Task Status</label>
                                            <select
                                                class="form-control select2 form-select @error('task_status') is-invalid @enderror"
                                                id="task_status" name="task_status">
                                                <option value="">Select Task Status</option>
                                                @foreach ($taskStatus as $tStatus)
                                                    <option value="{{ $tStatus->id }}"
                                                        {{ old('task_status', 6) == $tStatus->id ? 'selected' : '' }}>
                                                        {{ $tStatus->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('task_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="department" class="form-label">Department</label>
                                            <select
                                                class="form-control select2 form-select @error('department_id') is-invalid @enderror"
                                                id="department_id" name="department_id">
                                                <option value="">Select Department</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="task_stage" class="form-label">Task Stage</label>
                                            <select
                                                class="form-control select2 form-select @error('task_stage') is-invalid @enderror"
                                                id="task_stage" name="task_stage">
                                                <option value="">Select Task Stage</option>
                                                @foreach ($taskStage as $tStage)
                                                    <option value="{{ $tStage->id }}"
                                                        {{ old('task_stage', 3) == $tStage->id ? 'selected' : '' }}>
                                                        {{ $tStage->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('task_stage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="team" class="form-label">Team</label>
                                            <select
                                                class="form-control select2 @error('team_id') is-invalid @enderror"
                                                id="team_id" name="team_id">
                                                <option value="" disabled selected>Team</option>
                                            </select>
                                            @error('team_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="assign" class="form-label">Assign To</label>
                                            <select
                                                class="form-control select2 @error('assign_id') is-invalid @enderror"
                                                id="assign_id" name="assign_id[]" multiple>
                                                <option value="" disabled selected>Assign To</option>
                                            </select>
                                            @error('assign_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="finalized" class="form-label">Finalized</label>
                                            <select
                                                class="form-control select2 @error('finalized') is-invalid @enderror"
                                                id="finalized" name="finalized[]" multiple>
                                                <option value="" disabled selected>Finalized</option>
                                            </select>
                                            @error('finalized')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="csr" class="form-label">Assign CSR</label>
                                            <select
                                                class="form-control select2 form-select @error('csr') is-invalid @enderror"
                                                id="csr" name="csr">
                                                <option value="">Select CSR</option>
                                                @foreach ($assignCSR as $csr)
                                                    <option value="{{ $csr->id }}"
                                                        {{ old('csr') == $csr->id ? 'selected' : '' }}>
                                                        {{ $csr->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('csr')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="task_priority" class="form-label">Task Priority</label>
                                            <select
                                                class="form-control select2 form-select @error('task_priority') is-invalid @enderror"
                                                id="task_priority" name="task_priority">
                                                <option value="">Select Task Priority</option>
                                                @foreach ($taskPriority as $tPriority)
                                                    <option value="{{ $tPriority->id }}"
                                                        {{ old('task_priority') == $tPriority->id ? 'selected' : '' }}>
                                                        {{ $tPriority->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('task_priority')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="personal_email" class="form-label">Personal Email</label>
                                            <input type="email"
                                                class="form-control @error('personal_email') is-invalid @enderror"
                                                id="personal_email" name="personal_email"
                                                placeholder="Personal Email" value="{{ old('personal_email') }}" />
                                            @error('personal_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 p-4">
                                    <div class="col-md-12 mb-3">
                                        <label for="task_description" class="form-label">Task Description</label>
                                        <textarea class="summernote" id="summernote" name="task_description">{{ old('task_description') }}</textarea>
                                        @error('task_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="attachments" class="form-label">Attachments</label>
                                        <input type="file"
                                            class="form-control @error('attachments') is-invalid @enderror"
                                            id="attachments" name="attachments[]" multiple
                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip">
                                        @error('attachments')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-start">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                        <a href="{{ route('projects.details', $project->id) }}#related-task"
                                            class="btn btn-warning">
                                            <i class="fa fa-arrow-rotate-left"></i> Back
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
        <link rel="stylesheet" href="{{ asset('public/richtexteditor/richtexteditor/rte_theme_default.css') }}" />
        <script type="text/javascript" src="{{ asset('public/richtexteditor/richtexteditor/rte.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/richtexteditor/richtexteditor/plugins/all_plugins.js') }}">
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const taskTypeInput = document.getElementById("task_type");
                const suggestionsContainer = document.getElementById("task_type_suggestions");
                const evgTime = document.getElementById("evg_time");

                taskTypeInput.addEventListener("input", () => {
                    const query = taskTypeInput.value.trim();

                    if (query.length < 2) {
                        suggestionsContainer.style.display = "none";
                        return;
                    }

                    const fetchUrl = "{{ route('task-types-search') }}";

                    fetch(`${fetchUrl}?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsContainer.innerHTML = "";
                            if (data.length > 0) {
                                suggestionsContainer.style.display = "block";
                                data.forEach(item => {
                                    const suggestion = document.createElement("a");
                                    suggestion.href = "#";
                                    suggestion.classList.add("list-group-item",
                                        "list-group-item-action");
                                    suggestion.textContent = item.title;
                                    suggestion.dataset.value = item.id;

                                    suggestion.addEventListener("click", (e) => {
                                        e.preventDefault();
                                        taskTypeInput.value = item.title;
                                        suggestionsContainer.style.display = "none";
                                        evgTime.value = item.evg_time;
                                    });

                                    suggestionsContainer.appendChild(suggestion);
                                });
                            } else {
                                suggestionsContainer.style.display = "none";
                                evgTime.value = '';
                            }
                        })
                        .catch(error => {
                            console.error("Error fetching task types:", error);
                            suggestionsContainer.style.display = "none";
                        });
                });

                document.addEventListener("click", (event) => {
                    if (!suggestionsContainer.contains(event.target) && event.target !== taskTypeInput) {
                        suggestionsContainer.style.display = "none";
                    }
                });
            });

            $(document).ready(function() {
                $(".subTask").hide();
                $("#show-sub-task").click(function() {
                    $(".subTask").show();
                    $("#show-sub-task").hide();
                });

                // Disable dependent dropdowns initially
                $('#team_id').prop('disabled', true);
                $('#assign_id').prop('disabled', true);
                $('#finalized').prop('disabled', true);

                $('#department_id').on('select2:select', function(e) {
                    var selectedDepartmentId = $(this).val();

                    // Reset and clear the team, assign, and finalized dropdowns
                    $('#team_id').prop('disabled', false).val('').trigger('change').select2(); // Reset team
                    $('#assign_id').prop('disabled', true).val('').trigger('change').select2(); // Reset assign
                    $('#finalized').prop('disabled', true).val('').trigger('change')
                        .select2(); // Reset finalized

                    // Fetch teams based on the selected department
                    $.ajax({
                        url: '{{ route('teams.ajaxTeams') }}', // Replace with the correct endpoint for fetching teams
                        data: {
                            department_id: selectedDepartmentId
                        },
                        dataType: 'json',
                        success: function(data) {
                            // Create the first empty option
                            var firstOption = new Option('Select Team', '', true, false);

                            // Map team data to Option elements
                            var teamOptions = data.teams.map(function(team) {
                                return new Option(team.name, team.id, false, false);
                            });

                            // Empty the team dropdown, append the empty option first, then append the team options
                            $('#team_id').empty().append(firstOption).append(teamOptions).trigger(
                                'change');
                        },
                        error: function() {
                            alert('Error loading teams.');
                        }
                    });

                    // Optionally reset the assign and finalized fields when changing department
                    $('#assign_id').prop('disabled', true).val('').trigger('change').select2();
                    $('#finalized').prop('disabled', true).val('').trigger('change').select2();
                });

                $('#team_id').on('select2:select', function(e) {
                    var selectedTeamId = $(this).val();

                    // Enable assign and finalized dropdowns
                    $('#assign_id').prop('disabled', false).select2();
                    $('#finalized').prop('disabled', false).select2();

                    // Fetch users based on selected team for both assign and finalized dropdowns
                    $.ajax({
                        url: '{{ route('users.ajaxUsers') }}', // Replace with the correct endpoint for fetching users
                        data: {
                            team_id: selectedTeamId
                        },
                        dataType: 'json',
                        success: function(data) {
                            // Create the first empty option for assign dropdown
                            var firstAssignOption = new Option('Select Assign', '', true, false);

                            // Create the first empty option for finalized dropdown
                            var firstFinalizedOption = new Option('Select Finalized', '', true,
                                false);

                            // Map user data to Option elements for assign dropdown
                            var assignOptions = data.users.map(function(user) {
                                return new Option(user.name, user.id, false, false);
                            });

                            // Map user data to Option elements for finalized dropdown
                            var finalizedOptions = data.users.map(function(user) {
                                return new Option(user.name, user.id, false, false);
                            });

                            // Empty the assign dropdown, append the empty option first, then append the user options
                            $('#assign_id').empty().append(firstAssignOption).append(assignOptions)
                                .trigger('change');

                            // Empty the finalized dropdown, append the empty option first, then append the user options
                            $('#finalized').empty().append(firstFinalizedOption).append(
                                finalizedOptions).trigger('change');
                        },
                        error: function() {
                            alert('Error loading users.');
                        }
                    });
                });

                let timerInterval;
                let timerSeconds = 0;

                function updateTimerDisplay(seconds) {
                    const hrs = Math.floor(seconds / 3600).toString().padStart(2, "0");
                    const mins = Math.floor((seconds % 3600) / 60).toString().padStart(2, "0");
                    const secs = (seconds % 60).toString().padStart(2, "0");
                    document.getElementById("timer").textContent = `${hrs}:${mins}:${secs}`;
                }
                document.getElementById("startPauseBtn").addEventListener("click", function() {
                    if (timerInterval) {
                        clearInterval(timerInterval);
                        timerInterval = null;
                        this.innerHTML = `<i class="fas fa-play"></i>`;
                    } else {
                        timerInterval = setInterval(() => {
                            timerSeconds++;
                            updateTimerDisplay(timerSeconds);
                        }, 1000);
                        this.innerHTML = `<i class="fas fa-pause"></i>`;
                    }
                });
                document.getElementById("resetBtn").addEventListener("click", function() {
                    clearInterval(timerInterval);
                    timerInterval = null;
                    timerSeconds = 0;
                    updateTimerDisplay(timerSeconds);
                    document.getElementById("startPauseBtn").innerHTML = `<i class="fas fa-play"></i>`;
                });
                var editor1 = new RichTextEditor("#summernote", {
                    editorResizeMode: "both",
                    width: "10%"
                });
                // Use .on() for event delegation
                $("body").on("change", ".sub_tasks_values", function() {
                    var totalValue = 0;

                    // Iterate through all .sub_tasks_values and sum their values
                    $(".sub_tasks_values").each(function() {
                        totalValue += parseFloat($(this).val()) ||
                            0; // Add the value, defaulting to 0 if empty
                    });

                    // Set the total sum into the .task_value
                    $(".task_value").val(totalValue);
                });
            });
            document.addEventListener('DOMContentLoaded', () => {
                const subtaskContainer = document.getElementById('subtask-container');
                const addSubtaskButton = document.getElementById('add-subtask');
                addSubtaskButton.addEventListener('click', () => {
                    const subtaskCount = subtaskContainer.querySelectorAll('.subtask-item').length;
                    const newSubtask = `
                            <div class="subtask-item row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Sub Task Name</label>
                                    <input type="text" class="form-control" name="sub_tasks[${subtaskCount}][name]" placeholder="Sub Task Name" />
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Sub Task Value</label>
                                    <input type="number" class="form-control sub_tasks_values" name="sub_tasks[${subtaskCount}][value]" placeholder="Sub Task Value" />
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-dark remove-subtask"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>`;
                    subtaskContainer.insertAdjacentHTML('beforeend', newSubtask);
                });
                subtaskContainer.addEventListener('click', (event) => {
                    if (event.target.closest('.remove-subtask')) {
                        event.target.closest('.subtask-item').remove();
                        updateTaskValue();
                    }
                });
            });
            // Function to recalculate task value after removing a subtask
            function updateTaskValue() {
                var totalValue = 0;
                // Iterate through remaining .sub_tasks_values and sum their values
                $(".sub_tasks_values").each(function() {
                    totalValue += parseFloat($(this).val()) || 0; // Add the value, defaulting to 0 if empty
                });
                // Set the total sum into the .task_value
                $(".task_value").val(totalValue);
            }
        </script>
    @endsection
</x-app-layout>
