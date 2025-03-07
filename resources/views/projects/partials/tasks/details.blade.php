<x-app-layout>
    <div id="edit-task" class="mb-4 mt-4 pt-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 mb-4">
                    @if (Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                        @endif @if (Session::has('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ Session::get('error') }}
                            </div>
                        @endif
                        <!-- Success and Error Messages -->
                        <div class="alert alert-success" role="alert" style="display:none;">
                            <!-- Success message will be injected here -->
                        </div>
                        <div class="alert alert-danger" role="alert" style="display:none;">
                            <!-- Error message will be injected here -->
                        </div>

                </div>
            </div>
            <div class="card shadow mb-4 mt-4 rounded-0">
                <ul class="nav nav-tabs d-flex gap-2 border-0" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link1 active" id="task-details-form-tab" data-bs-toggle="tab"
                            data-bs-target="#task-details-form" type="button" role="tab"
                            aria-controls="task-details-form" aria-selected="true">
                            Task Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link1" id="conversation-tab" data-bs-toggle="tab"
                            data-bs-target="#conversation" type="button" role="tab" aria-controls="conversation"
                            aria-selected="false">
                            Conversation
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link1" id="task-notes-tab" data-bs-toggle="tab" data-bs-target="#task-notes"
                            type="button" role="tab" aria-controls="task-notes" aria-selected="false">
                            Task Notes
                        </button>
                    </li>
                </ul>
                <div class="card-body table-body p-0">
                    <div class="tab-content" id="myTabContent">
                        <div id="task-details-form" class="tab-pane fade show active" role="tabpanel"
                            aria-labelledby="task-details-form-tab">
                            <div class="row p-4">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Project ID:</label>
                                            <p>{{ $task->project_id }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Task Name:</label>
                                            <p>{{ $task->task_name }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Active Time:</label>
                                            <p>{{ $task->total_time ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if ($task->subtasks)
                                            <label class="form-label">Sub Tasks:</label>
                                            @foreach ($task->subtasks as $subtask)
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Sub Task Name</label>
                                                    <p>{{ $subtask->name }}</p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label">Sub Task Value</label>
                                                    <p>{{ $subtask->value }}</p>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Task Type</label>
                                            <p>{{ $taskType->where('id', $task->task_type)->first()->title ?? 'Not assigned' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Task Value</label>
                                            <p>{{ $task->task_value }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Start Date</label>
                                            <p>{{ $task->start_date }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">End Date</label>
                                            <p>{{ $task->end_date }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Task Status</label>
                                            <p>{{ $task->taskStatusLogs->last()->task_status->title ?? '' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Department</label>
                                            <p>{{ $departments->where('id', $task->department_id)->first()->name ?? 'Not assigned' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Task Stage</label>
                                            <p>{{ $taskStage->where('id', $task->task_stage)->first()->title ?? 'Not assigned' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Team</label>
                                            <p>{{ $task->team->name ?? 'Not assigned' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Assigned To</label>
                                            <p>
                                                @foreach ($task->taskAssignments->where('task_id', $task->id) as $taskAssignment)
                                                    {{ $taskAssignment->user->name ?? 'Not assigned' }}
                                                    <br>
                                                @endforeach
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Finalized</label>
                                            <p>{{ $task->finalize->name ?? 'Not assigned' }}</p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Assign CSR</label>
                                            <p>{{ $assignCSR->where('id', $task->csr)->first()->name ?? 'Not assigned' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Task Priority</label>
                                            <p>{{ $taskPriority->where('id', $task->task_priority)->first()->title ?? 'Not assigned' }}
                                            </p>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Personal Email</label>
                                            <p>{{ $task->personal_email }}</p>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <a href="{{ route('projects.tasks.edit', ['project' => $project, 'task' => $task->id]) }}"
                                                class="btn btn-primary"> <i class="fa fa-pen-to-square"></i> Edit
                                            </a>
                                            <a href="{{ route('projects.details', $project->id) }}#related-task"
                                                class="btn btn-warning"> <i class="fa fa-arrow-rotate-left"></i> Back
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 p-4">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Task Description</label>
                                        <p>{!! $task->task_description !!}</p>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Attachments</label>
                                        @php $attachments = json_decode($task->attachments, true) ?? []; @endphp @if (!empty($attachments))
                                            <div>
                                                <ul>
                                                    @foreach ($attachments as $index => $attachment)
                                                        <li>
                                                            <a href="{{ asset('storage/app/public/' . $attachment['path']) }}"
                                                                target="_blank">{{ $attachment['original_name'] ?? basename($attachment['path']) }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- <div class="col-md-12 mb-3" data-task-id="{{ $task->id }}"
                                        data-project-id="{{ $project->id }}">
                                        <div class="start-timer-row d-flex align-items-center">
                                            <button id="startPauseBtn-{{ $task->id }}"
                                                aria-label="Start or pause timer for task {{ $task->id }}"
                                                class="btn btn-primary me-2 startPauseBtn"
                                                data-task-id="{{ $task->id }}"
                                                data-project-id="{{ $project->id }}">
                                                <i class="fas fa-play"></i>
                                            </button>
                                            <div id="timer-{{ $task->id }}" class="h2">00:00:00</div>
                                        </div>
                                    </div> --}}
                                    <div class="alert alert-danger d-none mt-3"></div>
                                </div>
                            </div>
                        </div>
                        <div id="conversation" class="tab-pane fade" role="tabpanel"
                            aria-labelledby="conversation-tab">
                            <div class="row">
                                <form id="conversation-form" class="p-4" method="POST"
                                    action="{{ route('projects.tasks.conversations.store', ['project' => $project->id, 'task' => $task->id]) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                    <div class="col-md-12 mb-3">
                                        <label for="title_conversation" class="form-label">Title</label>
                                        <input type="text"
                                            class="form-control @error('title_conversation') is-invalid @enderror"
                                            id="title_conversation" name="title_conversation"
                                            placeholder="Title Conversation"
                                            value="{{ old('title_conversation', $task->task_name .' / '. date('d-M-Y h:i A')) }}" />
                                        @error('title_conversation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="description_conversation" class="form-label">Description</label>
                                        <textarea class="summernote" name="description_conversation" id="summernote" placeholder="Description Conversation">{{ old('description_conversation') }}</textarea>
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
                                        <a href="{{ route('projects.details', $project->id) }}#related-task"
                                            class="btn btn-warning">
                                            <i class="fa fa-arrow-rotate-left"></i> Back
                                        </a>
                                    </div>
                                </form>
                                <div class="col-12 load-conversations p-4"></div>
                            </div>
                        </div>
                        <div id="task-notes" class="tab-pane fade" role="tabpanel" aria-labelledby="task-notes-tab">
                            <div class="row p-4">
                                <div class="col-12 load-notes">
                                    load-notes
                                </div>
                            </div>
                        </div>
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
            $(document).ready(function() {
                const saveTimeLogUrl =
                    "{{ route('projects.tasks.save-time-log', ['project' => $task->project_id, 'task' => $task->id]) }}";
                const viewTaskUrlTemplate =
                    `{{ route('projects.tasks.details', ['project' => ':project', 'task' => ':task']) }}`;
                const browserKey = `${navigator.userAgent}-${navigator.platform}-${navigator.language}`;
                const timers = {};
                let timerInterval;
                let isRunning = false;
                let startTime;
                let elapsedTime = 0;
                async function stopTimer(taskId, startPauseBtn, timerDisplay, projectId) {
                    clearInterval(timerInterval);
                    isRunning = false;
                    startPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
                    const saveResponse = await saveTimeLog("stop", projectId, taskId);

                    if (saveResponse?.status === "error") {
                        console.error("Error stopping timer:", saveResponse.message);
                        return;
                    }

                    localStorage.setItem(`timerState-${taskId}`, JSON.stringify({
                        isRunning,
                        elapsedTime,
                        browserKey
                    }));
                    $(".TimeLogged").text(formatTime(elapsedTime));
                    timerDisplay.textContent = formatTime(elapsedTime);
                }

                function formatTime(milliseconds) {
                    const totalSeconds = Math.floor(milliseconds / 1000);
                    const hours = Math.floor(totalSeconds / 3600);
                    const minutes = Math.floor((totalSeconds % 3600) / 60);
                    const seconds = totalSeconds % 60;
                    return `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
                }

                async function saveTimeLog(action, projectId, taskId) {
                    const now = new Date().toISOString();
                    try {
                        const response = await fetch(saveTimeLogUrl, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute("content"),
                            },
                            body: JSON.stringify({
                                action,
                                project_id: projectId,
                                task_id: taskId,
                                timestamp: now
                            }),
                        });

                        const result = await response.json();
                        if (!response.ok) {
                            console.error("Backend Error:", result);
                            throw new Error(result.message || "Failed to save time log.");
                        }

                        return result;
                    } catch (error) {
                        console.error("Failed to save time log:", error);
                        return {
                            status: "error",
                            message: error.message
                        };
                    }
                }

                async function checkActiveTask(projectId, taskId) {
                    try {
                        const response = await fetch(saveTimeLogUrl, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute("content"),
                            },
                            body: JSON.stringify({
                                action: "check",
                                project_id: projectId,
                                task_id: taskId
                            }),
                        });
                        const result = await response.json();
                        if (!response.ok) {
                            throw new Error(result.message || "Failed to check active task.");
                        }
                        return result;
                    } catch (error) {
                        console.error("Failed to check active task:", error);
                        return {
                            status: "error",
                            message: error.message,
                            data: error
                        };
                    }
                }

                $(".startPauseBtn").each(function() {
                    const startPauseBtn = this;
                    const projectId = $(this).data("project-id");
                    const taskId = $(this).data("task-id");
                    const timerDisplay = document.getElementById(`timer-${taskId}`);



                    const savedState = JSON.parse(localStorage.getItem(`timerState-${taskId}`));
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

                    startPauseBtn.addEventListener("click", async function() {
                        if (!isRunning) {
                            const activeResponse = await checkActiveTask(projectId, taskId);

                            if (activeResponse?.status === "error" && activeResponse.activeTask
                                ?.toString() !== taskId.toString()) {
                                const taskUrl = viewTaskUrlTemplate
                                    .replace(':project', activeResponse.activeProject)
                                    .replace(':task', activeResponse.activeTask);

                                $(".alert-danger").html(`
                        ${activeResponse.message}
                        <a href="${viewTaskUrlTemplate.replace(':project', activeResponse.activeProject).replace(':task', activeResponse.activeTask)}" class="btn btn-dark p-2 bg-gray btn-sm view-task-details" data-task-id="${activeResponse.activeTask}" data-project-id="${activeResponse.activeTask}">View Task Details</button>
                    `).removeClass("d-none");

                                $("#stopActiveTimer").on("click", async function() {
                                    const stopResponse = await saveTimeLog("stop",
                                        activeResponse.activeProject, activeResponse
                                        .activeTask);

                                    if (stopResponse?.status === "success") {
                                        $(".alert-danger").addClass("d-none");
                                        startPauseBtn.click();
                                    } else {
                                        $(".alert-danger").html(
                                            `Failed to stop the active timer. ${stopResponse.message}`
                                        );
                                    }
                                });

                                return;
                            }

                            startTime = Date.now() - elapsedTime;
                            timerInterval = setInterval(() => {
                                elapsedTime = Date.now() - startTime;
                                timerDisplay.textContent = formatTime(elapsedTime);
                            }, 1000);

                            isRunning = true;
                            startPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                            const saveResponse = await saveTimeLog("start", projectId, taskId);

                            if (saveResponse?.status === "error") {
                                console.error("Error starting timer:", saveResponse.message);
                                return;
                            }

                            localStorage.setItem(`timerState-${taskId}`, JSON.stringify({
                                isRunning,
                                startTime,
                                browserKey
                            }));
                        } else {
                            clearInterval(timerInterval);
                            isRunning = false;
                            startPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
                            const saveResponse = await saveTimeLog("stop", projectId, taskId);

                            if (saveResponse?.status === "error") {
                                console.error("Error stopping timer:", saveResponse.message);
                                return;
                            }

                            localStorage.setItem(`timerState-${taskId}`, JSON.stringify({
                                isRunning,
                                elapsedTime,
                                browserKey
                            }));
                            $(".TimeLogged").text(formatTime(elapsedTime));
                            timerDisplay.textContent = formatTime(elapsedTime);
                        }
                    });
                });

                window.addEventListener("beforeunload", () => {
                    $(".startPauseBtn").each(function() {
                        const taskId = $(this).data("task-id");
                        const savedState = JSON.parse(localStorage.getItem(`timerState-${taskId}`));
                        if (savedState?.isRunning) {
                            saveTimeLog("reload", savedState.projectId, taskId);
                        }
                    });
                });

                var editor1 = new RichTextEditor("#summernote", {
                    editorResizeMode: "both",
                    width: "10%"
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
                            $(".alert-success").text(response.message).show();
                            $('#conversation-form')[0].reset(); // Reset form fields
                            //$(".summernote").summernote('reset'); // Reset Summernote editor content
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
                        url: "{{ route('projects.tasks.conversations.loadConversations', ['project' => $project->id, 'task' => $task->id]) }}", // Use the correct route and replace `:taskId` with the actual task ID
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
            });
        </script>
    @endsection
</x-app-layout>
