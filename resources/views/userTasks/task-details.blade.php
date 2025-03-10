<div class="row">
    <div class="col-sm-12">
        <div class="card shadow">
            <div class="card-header p-3 table-heading">
                <h6>Task Details</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover" width="100%" cellspacing="0">
                        <thead class="table-head">
                            <tr class="table-light">
                                <th>Task ID</th>
                                <th>Task Name</th>
                                <th>Task Type</th>
                                <th>Task Stage</th>
                                <th>Active Time</th>
                                <th>Task Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $task->id }}</td>
                                <td>{{ $task->task_name }}</td>
                                <td>{{ $task->taskType->title ?? '' }}</td>
                                <td>{{ $task->taskStage->title ?? '' }}</td>
                                <td><span class="TimeLogged">{{ $totalTimeLogged ?? '00:00:00' }}</span></td>
                                <td>{{ $task->taskStatusLogs->last()->task_status->title ?? '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        @if ($task->task_description)
                            <div class="col-12 col-md-12 p-4">
                                <label class="form-label">Task Description</label>
                                <p>{!! $task->task_description !!}</p>
                            </div>
                        @endif
                        @php
                            $attachments = json_decode($task->attachments, true) ?? [];
                        @endphp
                        @if (!empty($attachments))
                            <div class="col-12 col-md-12 p-4">
                                <label class="form-label">Attachments</label>
                                <ul>
                                    @foreach ($attachments as $attachment)
                                        <li>
                                            <a href="{{ asset('storage/app/public/' . $attachment['path']) }}"
                                                target="_blank">
                                                {{ $attachment['original_name'] ?? basename($attachment['path']) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="col-8">
                            <form id="conversation-form" class="p-4 border-end" method="POST"
                                action="{{ route('projects.tasks.conversations.store', ['project' => $task->project_id, 'task' => $task->id]) }}"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                <div class="col-md-12 mb-3">
                                    <label for="title_conversation" class="form-label">Title</label>
                                    <input type="text"
                                        class="form-control @error('title_conversation') is-invalid @enderror"
                                        id="title_conversation" name="title_conversation"
                                        placeholder="Title Conversation"
                                        value="{{ old('title_conversation', $task->task_name . ' / ' . date('d-M-Y h:i A')) }}" />
                                    @error('title_conversation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="description_conversation" class="form-label">Description</label>
                                    <textarea class="summernote" id="summernote" name="description_conversation" placeholder="Description Conversation">{{ old('description_conversation') }}</textarea>
                                    @error('description_conversation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="attachments_conversation" class="form-label">Attachments</label>
                                    <input type="file"
                                        class="form-control @error('attachments') is-invalid @enderror"
                                        id="attachments_conversation" name="attachments_conversation[]" multiple
                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.zip">
                                    @error('attachments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-4">
                            <div class="col-md-12 mb-3 mt-3 d-flex flex-column gap-4">
                                <h3 style="font-size:25px">Start Time Tracker</h3>
                                <div class="start-timer-row d-flex align-items-center">
                                    <button id="startPauseBtn-{{ $task->id }}"
                                        aria-label="Start or pause timer for task {{ $task->id }}"
                                        class="btn btn-primary me-2 startPauseBtn" data-task-id="{{ $task->id }}"
                                        data-project-id="{{ $task->project_id }}">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <div id="timer-{{ $task->id }}" class="h2">00:00:00</div>
                                </div>
                                <div class="controls d-flex gap-1">
                                    @php
                                        $userRole = Auth::user()->roles->pluck('name')->toArray();
                                    @endphp

                                    @if (in_array('CSRs', $userRole))
                                        @if (in_array('Team Lead', $userRole))
                                            <label>
                                                <input type="radio" name="taskStatus" id="completeRadio"
                                                    value="4" class="square-radio"
                                                    data-task-id="{{ $task->id }}"
                                                    data-project-id="{{ $task->project_id }}" />
                                                Verify TL
                                            </label>
                                        @endif
                                        <label>
                                            <input type="radio" name="taskStatus" id="completeRadio" value="7"
                                                class="square-radio" data-task-id="{{ $task->id }}"
                                                data-project-id="{{ $task->project_id }}" />
                                            Verify CSRs
                                        </label>
                                        <label>
                                            <input type="radio" name="taskStatus" id="completeRadio" value="1"
                                                class="square-radio" data-task-id="{{ $task->id }}"
                                                data-project-id="{{ $task->project_id }}" />
                                            RTC
                                        </label>
                                    @elseif(in_array('Team Lead', $userRole))
                                        <label>
                                            <input type="radio" name="taskStatus" id="completeRadio" value="4"
                                                class="square-radio" data-task-id="{{ $task->id }}"
                                                data-project-id="{{ $task->project_id }}" />
                                            Verify TL 
                                        </label>
                                    @else
                                        <label>
                                            <input type="radio" name="taskStatus" id="completeRadio"
                                                value="5" class="square-radio"
                                                data-task-id="{{ $task->id }}"
                                                data-project-id="{{ $task->project_id }}" />
                                            Complete
                                        </label>
                                        <label>
                                            <input type="radio" name="taskStatus" id="delayRadio" value="3"
                                                class="square-radio" data-task-id="{{ $task->id }}"
                                                data-project-id="{{ $task->project_id }}" />
                                            Delay
                                        </label>
                                    @endif

                                    @can('Reassign Task')
                                        <a href="#" class="btn btn-sm btn-success m-1 p-1 re-assign"
                                            data-bs-toggle="modal" data-bs-target="#re-assign-modal"
                                            data-task-id="{{ $task->id }}"
                                            data-project-id="{{ $task->project_id }}">ReAssign</a>
                                    @endcan
                                </div>
                            </div>
                            <div class="alert alert-danger d-none mt-3"></div>
                            <div class="alert alert-success d-none mt-3"></div>
                        </div>
                        <hr>
                        <div class="col-12 load-conversations p-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="re-assign-modal" tabindex="-1" aria-labelledby="re-assign-modal-label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="re-assign-form" method="POST"
                action="{{ route('re-assign-task', ['project' => $task->project_id, 'task' => $task->id]) }}"
                enctype="multipart/form-data" autocomplete="off">

                @csrf
                <input type="hidden" name="task_id" value="{{ $task->id }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="re-assign-modal-label">ReAssign Task</h5>
                </div>


                <div class="modal-body">
                    <div class="alert alert-success d-none mt-3"></div>

                    <div class="col-md-12 mb-3">
                        <label for="assign" class="form-label">Assign To</label>
                        <select class="form-control select2 @error('assign_id') is-invalid @enderror" id="assign_id"
                            name="assign_id[]" multiple>
                            <option value="">Select Assign</option>
                            @foreach ($getUsers as $user)
                                <option value="{{ $user->id }}"
                                    {{ in_array($user->id, $AssignedUsers->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assign_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>



                    <div class="col-md-12 mb-3">
                        <label for="finalized" class="form-label">Finalized</label>
                        <select class="form-control select2 @error('finalized') is-invalid @enderror" id="finalized"
                            name="finalized">
                            <option value="">Select Finalized</option>
                            @foreach ($getUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('finalized')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success re-assign-submit"
                        data-task-id="{{ $task->id }}" data-project-id="{{ $task->project_id }}">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        const saveTimeLogUrl = "{{ route('projects.tasks.save-time-log', ['project' => $task->project_id, 'task' => $task->id]) }}";
        const browserKey = `${navigator.userAgent}-${navigator.platform}-${navigator.language}`;
        let timerInterval;
        let isRunning = false;
        let startTime;
        let elapsedTime = 0;

        function formatTime(milliseconds) {
            const totalSeconds = Math.floor(milliseconds / 1000);
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;
            return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
        }

        function getStorage(key) {
            try {
                return JSON.parse(localStorage.getItem(key)) || null;
            } catch {
                return null;
            }
        }

        function setStorage(key, value) {
            localStorage.setItem(key, JSON.stringify(value));
        }

        async function saveTimeLog(action, projectId, taskId) {
            try {
                const response = await fetch(saveTimeLogUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    },
                    body: JSON.stringify({ action, project_id: projectId, task_id: taskId, timestamp: new Date().toISOString() }),
                });

                const result = await response.json();
                if (!response.ok) throw new Error(result.message || "Failed to save time log.");
                return result;
            } catch (error) {
                console.error("Failed to save time log:", error);
                return { status: "error", message: error.message };
            }
        }

        async function stopTimer(taskId, startPauseBtn, timerDisplay, projectId) {
            clearInterval(timerInterval);
            isRunning = false;
            startPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
            
            const saveResponse = await saveTimeLog("stop", projectId, taskId);
            if (saveResponse?.status === "error") return;

            setStorage(`timerState-${taskId}`, { isRunning, elapsedTime, browserKey });

            // Remove active task entry to prevent duplicate message
            localStorage.removeItem("activeTask");

            $(".TimeLogged").text(formatTime(elapsedTime));
            timerDisplay.textContent = formatTime(elapsedTime);

            // Notify other tabs
            localStorage.setItem("stopTimer", JSON.stringify({ taskId, timestamp: Date.now() }));
        }


        $(".startPauseBtn").each(function () {
            const startPauseBtn = this;
            const projectId = $(this).data("project-id");
            const taskId = $(this).data("task-id");
            const timerDisplay = document.getElementById(`timer-${taskId}`);
            const savedState = getStorage(`timerState-${taskId}`);

            if (savedState) {
                elapsedTime = savedState.elapsedTime || 0;
                if (savedState.isRunning && savedState.browserKey === browserKey) {
                    startTime = savedState.startTime;
                    timerInterval = setInterval(() => {
                        elapsedTime = Date.now() - startTime;
                        timerDisplay.textContent = formatTime(elapsedTime);
                    }, 1000);
                    isRunning = true;
                    startPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                } else {
                    timerDisplay.textContent = formatTime(elapsedTime);
                }
            }

            startPauseBtn.addEventListener("click", async function () {
                const activeTaskData = getStorage("activeTask");

                if (!isRunning) {
                    if (activeTaskData && activeTaskData.taskId) {
                        $(".alert-danger").html(`
                            ${activeTaskData.taskId !== taskId
                            ? 'Another task is already running. Stop it first. <button class="btn btn-dark p-2 bg-gray btn-sm view-task-details" data-id="' + activeTaskData.taskId + '">View Task Details</button>'
                            : 'Task timer is already running. <button class="btn btn-dark p-2 bg-gray btn-sm stop-active-task">Stop Active Task</button>'
                        }
                        `).removeClass("d-none");

                        $(".stop-active-task").on("click", async function () {
                            await stopTimer(activeTaskData.taskId, startPauseBtn, timerDisplay, activeTaskData.projectId);
                            $(".alert-danger").addClass("d-none");

                            // Remove active task from storage
                            localStorage.removeItem("activeTask");

                            startPauseBtn.click();
                        });

                        return;
                    }

                    startTime = Date.now();
                    elapsedTime = 0;
                    timerInterval = setInterval(() => {
                        elapsedTime = Date.now() - startTime;
                        timerDisplay.textContent = formatTime(elapsedTime);
                    }, 1000);
                    isRunning = true;
                    startPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';

                    const saveResponse = await saveTimeLog("start", projectId, taskId);
                    if (saveResponse?.status === "error") return;

                    setStorage(`timerState-${taskId}`, { isRunning, startTime, browserKey });
                    setStorage("activeTask", { projectId, taskId });
                } else {
                    await stopTimer(taskId, startPauseBtn, timerDisplay, projectId);
                    localStorage.removeItem("activeTask");
                    //localStorage.removeItem(`timerState-${taskId}`);
                }
            });
        });

        // Listen for stop event in other tabs
        window.addEventListener("storage", (event) => {
            if (event.key === "stopTimer") {
                const data = JSON.parse(event.newValue);
                if (data && data.taskId) {
                    $(".startPauseBtn").each(function () {
                        const taskId = $(this).data("task-id");
                        const projectId = $(this).data("project-id");
                        const startPauseBtn = this;
                        const timerDisplay = document.getElementById(`timer-${taskId}`);

                        if (taskId === data.taskId) {
                            stopTimer(taskId, startPauseBtn, timerDisplay, projectId);
                        }
                    });
                }
            }
        });

        // Track navigation and prevent accidental exits
        let isNavigating = false;

        document.addEventListener("click", (event) => {
            if (event.target.closest("a")) {
                isNavigating = true;
                setTimeout(() => (isNavigating = false), 300);
            }
        });

        window.addEventListener("popstate", () => {
            isNavigating = true;
            setTimeout(() => (isNavigating = false), 300);
        });

        window.addEventListener("beforeunload", (event) => {
            if (!isNavigating) {
                stopAllTimers();
                event.preventDefault();
                event.returnValue = '';
            }
        });

        function stopAllTimers() {
            $(".startPauseBtn").each(function () {
                const taskId = $(this).data("task-id");
                const projectId = $(this).data("project-id");
                const startPauseBtn = this;
                const timerDisplay = document.getElementById(`timer-${taskId}`);
                const savedState = getStorage(`timerState-${taskId}`);

                if (savedState?.isRunning) {
                    startPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
                    clearInterval(timerInterval);

                    const formData = new FormData();
                    formData.append("action", "stop");
                    formData.append("project_id", projectId);
                    formData.append("task_id", taskId);
                    formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute("content"));

                    navigator.sendBeacon(saveTimeLogUrl, formData);

                    setStorage(`timerState-${taskId}`, { isRunning: false, elapsedTime: savedState.elapsedTime, browserKey: savedState.browserKey });
                    $(".TimeLogged").text(formatTime(savedState.elapsedTime));
                    timerDisplay.textContent = formatTime(savedState.elapsedTime);
                }
            });
        }

        var editor1 = new RichTextEditor("#summernote", {
            editorResizeMode: "both"
        });


        // Handle form submission
        $('#conversation-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Create a FormData object
            let formData = new FormData(this);

            // Send AJAX request
            $.ajax({
                url: $(this).attr('action'), // Use the form's action URL
                method: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting the content type
                success: function(response) {
                    // Show success message
                    $(".alert-success").text(response.message).removeClass("d-none");
                    $('#conversation-form')[0].reset(); // Reset form fields
                    //var editor1 = new RichTextEditor("#summernote");
                    editor1.clearHistory();
                    // Optionally update the UI or redirect
                    // window.location.href = '/somepage';
                    loadConversations({{ $task->id }});
                },
                error: function(xhr, status, error) {
                    // Show error message
                    $(".alert-danger").text(
                        'An error occurred while saving the conversation.').show();
                    console.error(xhr.responseText);
                },
            });
        });
        loadConversations({{ $task->id }});

        function loadConversations(taskId) {
            $.ajax({
                url: "{{ route('projects.tasks.conversations.loadConversations', ['project' => $task->project_id, 'task' => $task->id]) }}", // Use the correct route and replace `:taskId` with the actual task ID
                method: 'GET',
                success: function(response) {
                    // Inject the conversation data into the target div
                    $('.load-conversations').html(response); // Replace the content of the div
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while loading conversations.');
                }
            });
        }

        // Handle task status change
        $("input[name='taskStatus']").on('change', function() {
            const selectedStatus = $("input[name='taskStatus']:checked").val();
            const taskId = $(this).data("task-id");
            const projectId = $(this).data("project-id");

            if (!selectedStatus) {
                alert("Please select a task status.");
                return;
            }
            if (!taskId) {
                alert("Task ID is missing.");
                return;
            }
            // Fetch task details for relevant buttons and displays
            const startPauseBtn = $(".startPauseBtn[data-task-id='" + taskId + "']")[0];
            const timerDisplay = document.getElementById(`timer-${taskId}`);
            // Stop the timer when task status changes
            stopTimer(taskId, startPauseBtn, timerDisplay, projectId);

            // You can continue with the rest of the task status change logic, such as saving the new status via AJAX.
            $.ajax({
                url: './my-tasks/' + taskId + '/update-status',
                method: 'POST',
                data: {
                    _token: $("meta[name='csrf-token']").attr("content"),
                    task_status: selectedStatus,
                    taskId: taskId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $(".alert-success").text(response.message).removeClass("d-none");
                        // setTimeout(() => {
                        //     location.reload();
                        // }, 2000);  
                        setTimeout(() => {
                            $('.alert').alert('close');
                        }, 5000);
                        $(".controls").hide();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred while updating the task status.');
                }
            });
        });

        $('#re-assign-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
            // Create a FormData object
            let formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'), // Use the form's action URL
                method: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting the content type
                success: function(response) {
                    // Show success message
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                    $(".alert-success").text(response.message).removeClass("d-none");
                },
                error: function(xhr, status, error) {
                    // Show error message
                    $(".alert-danger").text(
                        'An error occurred while saving the Task Assign.').show();
                    console.error(xhr.responseText);
                },
            });
        });

    });
</script>
