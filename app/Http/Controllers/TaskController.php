<?php
namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Department;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskPriority;
use App\Models\TaskStage;
use App\Models\TaskStatus;
use App\Models\TaskType;
use App\Models\Team;
use App\Models\TimeLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Tasks', only: ['index']),
            new Middleware('permission:Edit Task', only: ['edit']),
            new Middleware('permission:Add New Task', only: ['create']),
            new Middleware('permission:Delete Task', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $project)
    {
        $filteredQuery = Task::with([
            'taskType:id,title',
            'team:id,name',
            'taskAssignments:user_id,task_id,t_assigned1,created_at',
            'taskAssignments.user:id,name',
            'finalized:id,name',
            'taskStage:id,title',
            'csr:id,name',
            'author:id,name',
            'project.client:id,client_name',
        ])
            ->where('project_id', $project);
        if ($request->has('search') && is_string($request->search) && $request->search !== '') {
            $searchTerm = $request->search;
            $filteredQuery->where(function ($query) use ($searchTerm) {
                $query->where('task_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('task_description', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('author', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('team', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        $recordsTotal    = Task::where('project_id', $project)->count();
        $recordsFiltered = $filteredQuery->count();

        $columns         = ['id', 'name', 'task_description', 'author', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn      = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder       = $request->input('order.0.dir', 'desc');

        $taskQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        $tasks = $taskQuery->get();

        // Calculate total time for each task
        $tasks->each(function ($task) {
            $totalTime = $task->timeLogs()
                ->selectRaw('SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, start_time, end_time))) AS total_time')
                ->value('total_time');
            $task->total_time = $totalTime ?? '00:00:00';
        });

        // Map Sources to include action column for Table
        $tasks->transform(function ($task) use ($project) {
            if (strpos($task->finalized, ',') !== false) {
                $userIds              = explode(',', $task->finalized);
                $finalizedUsers       = User::whereIn('id', $userIds)->pluck('name')->toArray();
                $task->finalized_user = implode(', ', $finalizedUsers);
            } else {
                // Single user ID
                $finalizedUser        = User::find($task->finalized);
                $task->finalized_user = $finalizedUser ? $finalizedUser->name : 'No Finalized User';
            }

            // Generate view action if permission is granted
            $viewAction = Auth::user()->can('View Tasks')
            ? '<a href="' . route('projects.tasks.details', ['project' => $project, 'task' => $task->id]) . '" class="btn btn-info btn-sm py-2" title="View">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                </a>'
            : '';
            // Generate edit action if permission is granted
            $editAction = Auth::user()->can('Edit Task')
            ? '<a href="' . route('projects.tasks.edit', ['project' => $project, 'task' => $task->id]) . '" class="btn btn-success btn-sm py-2" title="Edit">
                        <i class="fa fa-edit"></i>
                   </a>'
            : '';
            // Generate delete action if permission is granted
            $deleteAction = Auth::user()->can('Delete Task')
            ? '<a href="#" class="btn btn-warning btn-sm py-2 delete-task-btn" title="Delete" data-id="' . $task->id . '">
            <i class="fa fa-trash"></i>
       </a>'
            : '';

            // Combine actions
            $task->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $task;
        });

        if ($request->ajax()) {
            return response()->json([
                'data'            => $tasks,
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('tasks.list', compact('tasks'));
    }

    public function show($id)
    {
        $task = Task::with('author')->findOrFail($id);
        return response()->json($task);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($projectId)
    {
        $project      = Project::findOrFail($projectId);
        $teams        = Team::orderBy('name', 'ASC')->get();
        $taskPriority = TaskPriority::orderBy('order_by', 'ASC')->get();
        $taskStatus   = TaskStatus::orderBy('order_by', 'ASC')->get();
        $taskStage    = TaskStage::orderBy('order_by', 'ASC')->get();
        $taskType     = TaskType::orderBy('order_by', 'ASC')->get();
        $departments  = Department::orderBy('name', 'ASC')->get();
        $users        = User::orderBy('name', 'ASC')->get();
        $assignCSR    = User::whereHas('roles', function ($query) {
            $query->where('name', 'CSRs');
        })->orderBy('name', 'ASC')->get();

        return view('projects.partials.tasks.create', compact('project', 'departments', 'teams', 'taskPriority', 'taskStatus', 'taskStage', 'taskType', 'users', 'assignCSR'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $project)
    {
        $validator = Validator::make($request->all(), [
            'task_name'         => 'required|string|max:255',
            'task_type'         => 'required|string|max:255',
            'evg_time'          => 'nullable|numeric',
            'task_value'        => 'nullable|string',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'task_status'       => 'nullable|string',
            'task_stage'        => 'nullable|string',
            'department_id'     => 'nullable|exists:departments,id',
            'team_id'           => 'nullable|exists:teams,id',
            'assign_id'         => 'nullable|exists:users,id',
            'finalized'         => 'nullable|exists:users,id',
            'csr'               => 'nullable|string|max:255',
            'task_priority'     => 'nullable|string',
            'personal_email'    => 'nullable|email',
            'task_description'  => 'nullable',
            'attachments'       => 'nullable|array',
            'attachments.*'     => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:10240',
            'sub_tasks'         => 'nullable|array',
            'sub_tasks.*.name'  => 'nullable|string|max:255',
            'sub_tasks.*.value' => 'nullable|string|max:255',
        ]);

        if ($validator->passes()) {
            $finalized       = $request->input('finalized');
            $finalizedString = implode(',', $finalized);

            $taskTypeTitle = $request->input('task_type');
            $evgTime       = $request->input('evg_time');
            $taskType      = TaskType::where('title', $taskTypeTitle)->first();

            if (! $taskType) {
                $taskType = TaskType::create(['title' => $taskTypeTitle, 'evg_time' => $evgTime, 'author' => Auth::user()->id, 'order_by' => 1]);
            } else {
                // If task type exists, update the evg_time
                $taskType->update(['evg_time' => $evgTime]);
            }

            // Handle file uploads
            $attachmentDetails = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $attachmentDetails[] = [
                        'path'          => $file->store('projects-task-attachments', 'public'),
                        'original_name' => $file->getClientOriginalName(),
                    ];
                }
            }

            $task = Task::create([
                'project_id'       => $request->input('project_id'),
                'task_name'        => $request->input('task_name'),
                'task_type'        => $taskType->id,
                'task_value'       => $request->input('task_value'),
                'start_date'       => $request->input('start_date'),
                'end_date'         => $request->input('end_date'),
                'task_stage'       => $request->input('task_stage'),
                'department_id'    => $request->input('department_id'),
                'team_id'          => $request->input('team_id'),
                'finalized'        => $finalizedString,
                'csr'              => $request->input('csr'),
                'task_priority'    => $request->input('task_priority'),
                'personal_email'   => $request->input('personal_email'),
                'task_description' => $request->input('task_description'),
                'attachments'      => json_encode($attachmentDetails),
                'author'           => Auth::user()->id,
            ]);

            if ($request->has('sub_tasks') && is_array($request->input('sub_tasks'))) {
                foreach ($request->input('sub_tasks') as $subtask) {
                    if (! empty($subtask['name']) && ! empty($subtask['value'])) {
                        $task->subtasks()->create([
                            'name'  => $subtask['name'],
                            'value' => $subtask['value'],
                        ]);
                    }
                }
            }

            if ($request->filled('task_status')) {
                $task->taskStatusLogs()->create([
                    'task_status_id' => $request->input('task_status'),
                    'user_id'        => Auth::id(),
                ]);
            }

            if ($request->has('assign_id') && is_array($request->input('assign_id'))) {
                foreach ($request->input('assign_id') as $assignId) {
                    $task->taskAssignments()->create([
                        'user_id' => $assignId,
                    ]);

                    Helper::createTaskNotification($assignId, $task->id, "You have been assigned a task!");
                    $message = "You have been assigned a new task!";
                    Helper::broadcastTaskNotifications($message, $assignId);
                }
            }

            return redirect()->to(route('projects.details', $project) . '#related-task')->with('success', 'Task added successfully.');
        } else {
            return redirect()->route('projects.tasks.create', ['project' => $project])->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($projectId, $taskId)
    {
        $task          = Task::with('subtasks', 'taskAssignments')->findOrFail($taskId);
        $assignedUsers = User::whereIn('id', $task->taskAssignments->pluck('user_id'))->get();
        $project       = Project::findOrFail($projectId);
        $teams         = Team::orderBy('name', 'ASC')->get();
        $taskPriority  = TaskPriority::orderBy('order_by', 'ASC')->get();
        $taskStatus    = TaskStatus::orderBy('order_by', 'ASC')->get();
        $taskStage     = TaskStage::orderBy('order_by', 'ASC')->get();
        $taskType      = TaskType::orderBy('order_by', 'ASC')->get();
        $departments   = Department::orderBy('name', 'ASC')->get();
        $users         = User::orderBy('name', 'ASC')->get();
        $assignCSR     = User::whereHas('roles', function ($query) {
            $query->where('name', 'CSRs');
        })->orderBy('name', 'ASC')->get();

        $totalTime = $task->timeLogs()
            ->selectRaw('SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, start_time, end_time))) AS total_time')
            ->value('total_time');

        $task->total_time = $totalTime ?? '00:00:00';

        // return $task->total_time;

        return view('projects.partials.tasks.edit', compact('task', 'project', 'departments', 'teams', 'taskPriority', 'taskStatus', 'taskStage', 'taskType', 'users', 'assignCSR', 'assignedUsers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $project, $id)
    {

        $validator = Validator::make($request->all(), [
            'task_name'         => 'required|string|max:255',
            'task_type'         => 'nullable|string|max:255',
            'evg_time'          => 'nullable|numeric',
            'task_value'        => 'nullable|string',
            'start_date'        => 'nullable|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'task_status'       => 'nullable|string',
            'task_stage'        => 'nullable|string',
            'department_id'     => 'nullable|exists:departments,id',
            'team_id'           => 'nullable|exists:teams,id',
            'assign_id'         => 'nullable|exists:users,id',
            'finalized'         => 'nullable|exists:users,id',
            'csr'               => 'nullable|string|max:255',
            'task_priority'     => 'nullable|string',
            'personal_email'    => 'nullable|email',
            'task_description'  => 'nullable',
            'attachments'       => 'nullable|array',
            'attachments.*'     => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:10240',
            'sub_tasks'         => 'nullable|array',
            'sub_tasks.*.name'  => 'required|string|max:255',
            'sub_tasks.*.value' => 'nullable|string|max:255',
        ]);

        if ($validator->passes()) {

            $task = Task::findOrFail($id);

            $newTotalTime = $request->input('task_time');

            if ($newTotalTime) {
                $totalSeconds = DB::select("SELECT TIME_TO_SEC(?) AS seconds", [$newTotalTime])[0]->seconds;

                $currentTotalTime = $task->timeLogs()
                    ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, start_time, end_time)) AS total_seconds')
                    ->value('total_seconds');

                $secondsDifference = $totalSeconds - $currentTotalTime;

                $lastLog = $task->timeLogs()->latest('end_time')->first();

                if ($lastLog) {
                    if ($secondsDifference > 0) {
                        $lastLog->end_time = DB::raw("ADDTIME(end_time, SEC_TO_TIME($secondsDifference))");
                    } elseif ($secondsDifference < 0) {
                        $lastLog->end_time = DB::raw("SUBTIME(end_time, SEC_TO_TIME(ABS($secondsDifference)))");
                    }

                    $lastLog->save();
                }
            }

            $existingAttachments = json_decode($task->attachments, true) ?? [];

            $finalized       = $request->input('finalized');
            $finalizedString = implode(',', $finalized);

            $evgTime  = $request->evg_time;
            $taskType = TaskType::where('id', $request->task_type)->first();

            if ($taskType) {
                // Update existing task type
                $taskType->update(['evg_time' => $evgTime]);
            } else {
                // Create new task type
                $taskType = TaskType::create([
                    'id'       => $request->task_type,
                    'evg_time' => $evgTime,
                ]);
            }

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $existingAttachments[] = [
                        'path'          => $file->store('projects-task-attachments', 'public'),
                        'original_name' => $file->getClientOriginalName(),
                    ];
                }
            }

            // Update the fields from the request
            $task->project_id       = $request->project_id;
            $task->task_name        = $request->task_name;
            $task->task_type        = $request->task_type;
            $task->task_value       = $request->task_value;
            $task->start_date       = $request->start_date;
            $task->end_date         = $request->end_date;
            $task->task_stage       = $request->task_stage;
            $task->department_id    = $request->department_id;
            $task->team_id          = $request->team_id;
            $task->finalized        = $finalizedString;
            $task->csr              = $request->csr;
            $task->task_priority    = $request->task_priority;
            $task->personal_email   = $request->personal_email;
            $task->task_description = $request->task_description;
            $task->attachments      = json_encode($existingAttachments);
            $task->save();

            if ($request->has('sub_tasks')) {
                $task->subtasks()->delete();
                foreach ($request->input('sub_tasks') as $subtask) {
                    $task->subtasks()->create([
                        'name'  => $subtask['name'],
                        'value' => $subtask['value'],
                    ]);
                }
            }

            if ($request->filled('task_status')) {
                $task->taskStatusLogs()->create([
                    'task_status_id' => $request->input('task_status'),
                    'user_id'        => Auth::id(),
                ]);
            }

            if ($request->has('assign_id') && is_array($request->input('assign_id'))) {
                $task->taskAssignments()->delete();
                foreach ($request->input('assign_id') as $assignId) {
                    $task->taskAssignments()->create([
                        'user_id' => $assignId,
                    ]);

                    Helper::updateTaskNotification($assignId, $task->id);
                    $message = "You have been assigned a new task!";
                    Helper::broadcastTaskNotifications($message, $assignId);
                }
            }

            return redirect()->to(route('projects.details', $project) . '#related-task')->with('success', 'Task updated successfully.');
        } else {
            return redirect()->route('projects.tasks.edit', ['project' => $project, 'task' => $id])
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($project, $id)
    {
        $task = Task::findOrFail($id);

        if ($task->delete()) {
            return response()->json(['success' => true, 'message' => 'Task deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to delete task.'], 500);
    }

    public function deleteAttachment(Request $request, $project, $id)
    {
        $request->validate([
            'attachment_index' => 'required|integer',
        ]);

        $task        = Task::findOrFail($id);
        $attachments = json_decode($task->attachments, true) ?? [];

        $attachmentIndex = $request->input('attachment_index');

        if (isset($attachments[$attachmentIndex])) {
            $filePath = $attachments[$attachmentIndex]['path'];

            if (\Storage::disk('public')->exists($filePath)) {
                \Storage::disk('public')->delete($filePath);
            }

            unset($attachments[$attachmentIndex]);
            $task->attachments = json_encode(array_values($attachments));
            $task->save();

            return redirect()->back()->with('success', 'Attachment deleted successfully.');
        }

        return redirect()->back()->with('error', 'Attachment not found.');
    }

    public function details(Request $request, $project, $task)
    {
        $task         = Task::with('subtasks', 'assign', 'finalize', 'taskStatusLogs.task_status', 'taskAssignments')->findOrFail($task);
        $project      = Project::findOrFail($project);
        $teams        = Team::orderBy('name', 'ASC')->get();
        $taskPriority = TaskPriority::orderBy('order_by', 'ASC')->get();
        $taskStatus   = TaskStatus::orderBy('order_by', 'ASC')->get();
        $taskStage    = TaskStage::orderBy('order_by', 'ASC')->get();
        $taskType     = TaskType::orderBy('order_by', 'ASC')->get();
        $departments  = Department::orderBy('name', 'ASC')->get();
        $users        = User::orderBy('name', 'ASC')->get();
        $assignCSR    = User::whereHas('roles', function ($query) {
            $query->where('name', 'CSRs');
        })->orderBy('name', 'ASC')->get();

        $totalTime = $task->timeLogs()
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->selectRaw('SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, start_time, end_time))) AS total_time')
            ->value('total_time');

        $task->total_time = $totalTime ?? '00:00:00';

        return view('projects.partials.tasks.details', compact('task', 'project', 'departments', 'teams', 'taskPriority', 'taskStatus', 'taskStage', 'taskType', 'users', 'assignCSR'));
    }

    public function save_time_log(Request $request)
    {
        $request->validate([
            'action'     => 'required|in:check,start,stop',
            'task_id'    => 'required|integer',
            'project_id' => 'required|integer',
        ]);
    
        $action    = $request->input('action');
        $taskId    = $request->input('task_id');
        $projectId = $request->input('project_id');
        $userId    = Auth::id();
    
        DB::beginTransaction();
    
        try {
            $activeLog = TimeLog::where('user_id', $userId)->whereNull('end_time')->first();
    
            if ($action === 'check') {
                if ($activeLog) {
                    return response()->json([
                        'status'        => 'active',
                        'activeTask'    => $activeLog->task_id,
                        'activeProject' => $activeLog->project_id,
                        'start_time'    => $activeLog->start_time,
                        'message'       => 'You have an active timer running.',
                    ]);
                }
                return response()->json(['status' => 'no_active_timer']);
            }
    
            if ($action === 'start') {
                if ($activeLog) {
                    return response()->json([
                        'status'        => 'error',
                        'message'       => 'An active timer already exists. Stop it before starting a new one.',
                        'activeTask'    => $activeLog->task_id,
                        'activeProject' => $activeLog->project_id,
                    ]);
                }
    
                $newLog = TimeLog::create([
                    'user_id'    => $userId,
                    'task_id'    => $taskId,
                    'project_id' => $projectId,
                    'start_time' => Carbon::now('Asia/Karachi'),
                ]);
    
                DB::commit();
    
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Timer started successfully.',
                    'log'       => $newLog,
                ]);
            }
    
            if ($action === 'stop') {
                if ($activeLog && $activeLog->task_id == $taskId) {
                    $activeLog->update(['end_time' => Carbon::now('Asia/Karachi')]);
    
                    DB::commit();
    
                    return response()->json([
                        'status'  => 'success',
                        'message' => 'Timer stopped successfully.',
                        'log'     => $activeLog,
                    ]);
                } else {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'No active timer found for this task.',
                    ]);
                }
            }
    
            return response()->json(['status' => 'error', 'message' => 'Invalid action.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving time log for user: ' . $userId, [
                'error' => $e->getMessage(),
            ]);
    
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while saving the time log.',
            ]);
        }
    }
    

    public function re_assign_task(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assign_id'   => 'nullable|array',
            'assign_id.*' => 'exists:users,id',
            'finalized'   => 'nullable|exists:users,id',
        ]);
        if ($validator->passes()) {
            $task            = Task::findOrFail($request->task);
            $task->finalized = $request->finalized;
            $task->save();

            if ($request->has('assign_id') && is_array($request->input('assign_id'))) {
                $task->taskAssignments()->delete();
                foreach ($request->input('assign_id') as $assignId) {
                    $task->taskAssignments()->create([
                        'user_id' => $assignId,
                    ]);
                }
            }
            return response()->json([
                'status'  => 'success',
                'message' => 'ReAssign Task successfully.',
            ]);
        } else {
            return response()->json([
                'status'  => 'error',
                'message' => 'An error occurred while saving the Task.',
            ]);
        }
    }
}
