<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Task;
use App\Models\Project;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserLog;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userRole         = Auth::user()->roles->pluck('name')->toArray();
        $userId           = Auth::id();
        $teamId           = Auth::user()->team_id;
        $userDepartmentId = Auth::user()->department_id;
        $userDepartments  = Auth::user()->user_departments;

        if (in_array('Super Admin', $userRole)) {
            $loadView = 'dashboard';
        } elseif (in_array('CSRs', $userRole)) {
            $loadView = 'userTasks.csr-tasks';
        } elseif (in_array('Team Lead', $userRole)) {
            $loadView = 'userTasks.tl-tasks';
        } else {
            $loadView = 'userTasks.my-tasks';
        }
        // Count completed tasks
        $completedTasksCount = Task::whereHas('taskStatusLogs', function ($query) {
            $query->where('task_status_id', 1) // Ensure this matches "Completed"
                ->whereRaw('task_status_logs.id = (
                SELECT MAX(id)
                FROM task_status_logs
                WHERE task_status_logs.task_id = tasks.id
            )');
        })->count();
        // Count incomplete tasks
        $incompleteTasksCount = Task::whereHas('taskStatusLogs', function ($query) {
            $query->where('task_status_id', '<>', 1) // Exclude "Completed" status
                ->whereRaw('task_status_logs.id = (
                SELECT MAX(id)
                FROM task_status_logs
                WHERE task_status_logs.task_id = tasks.id
            )');
        })->count();
        // Count high-priority tasks
        $highPriorityTasksCount = Task::whereHas('taskPriority', function ($query) {
            $query->where('task_priority', 2); // Adjust field name if needed
        })->count();
        // Return the view
        return view($loadView, compact('completedTasksCount', 'incompleteTasksCount', 'highPriorityTasksCount'));
    }

    public function taskDetails(Request $request, $id)
    {
        // Fetch task with relationships
        $task = Task::with('project', 'department', 'team', 'assign', 'author', 'taskType', 'taskStage', 'taskStatusLogs.task_status', 'timeLogs')
            ->findOrFail($id);

        // Calculate total time logged using Eloquent relationship
        $totalTimeLogged = $task->timeLogs()
            ->selectRaw('SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, start_time, end_time))) AS total_time')
            ->value('total_time');

        // Return the detailed task view
        return view('userTasks.task-details', compact('task', 'totalTimeLogged'));
    }
    public function updateTaskStatus(Request $request, $id)
    {
        // Validate the task status passed in the request
        $request->validate([
            'task_status' => 'required|exists:task_statuses,id',
        ]);

        // Ensure the task exists
        $task = Task::find($id);

        if (! $task) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Task not found.',
            ], 404);
        }

        // Update the task status log if 'task_status' is provided
        if ($request->filled('task_status')) {
            $task->taskStatusLogs()->create([
                'task_status_id' => $request->input('task_status'),
                'user_id'        => Auth::id(),
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Task status updated successfully.',
        ]);
    }

    public function notificationsCount(Request $request)
    {
        $userChannelId = DB::table('ch_channel_user')
            ->where('user_id', Auth::id())
            ->value('channel_id');

        if (! $userChannelId) {
            return 0; // No channel assigned
        }

        $whereRawCondition = "(seen IS NULL OR JSON_UNQUOTE(seen) = '[]')";

        return DB::table('ch_messages')
            ->where('to_channel_id', $userChannelId)
            ->where('from_id', '!=', Auth::id())
            ->whereNotNull('from_id')
            ->whereRaw($whereRawCondition)
            ->count();
    }
    // Unassigned Task
    public function getUnassignedTasks()
    {
        $tasks = Task::with(['project.source', 'taskStage'])
            ->whereHas('taskStage', function ($query) {
                $query->where('title', 'Open');
            })
            ->get(['id as task_id', 'task_name', 'project_id']);

        $tasks = $tasks->map(function ($task) {
            return [
                'task_id' => $task->task_id,
                'task_name' => $task->task_name,
                'source_id' => $task->project->source_id,
                'source_name' => $task->project->source->source_name ?? 'N/A',
            ];
        });

        return response()->json($tasks);
    }
    // Delayed Task
    public function getDelayedTasks()
    {
        $tasks = Task::with(['project.source', 'taskStage'])
            ->whereHas('taskStage', function ($query) {
                $query->where('title', 'Delayed');
            })
            ->get(['id as task_id', 'task_name', 'project_id']);

        $tasks = $tasks->map(function ($task) {
            return [
                'task_id' => $task->task_id,
                'task_name' => $task->task_name,
                'project_id' => $task->project_id,
            ];
        });

        return response()->json($tasks);
    }
    // Project Without Task
    public function getProjectsWithoutTasks()
    {
        $projects = Project::with('source')
            ->leftJoin('tasks', 'projects.id', '=', 'tasks.project_id')
            ->leftJoin('sources', 'projects.source_id', '=', 'sources.id')
            ->whereNull('tasks.id')
            ->select('projects.id as project_id', 'projects.project_name', 'projects.source_id', 'sources.source_name')
            ->get();

        $projects = $projects->map(function ($project) {
            return [
                'project_id' => $project->project_id,
                'project_name' => $project->project_name,
                'source_id' => $project->source_id,
                'source_name' => $project->source_name ?? 'N/A',
            ];
        });

        return response()->json($projects);
    }
    // Pending Payment
    public function getPendingPayments()
    {
        $payments = Payment::with('project.source')
            ->where('remaining_payment', '>', 0)
            ->orderBy('project_id')
            ->select('payments.id as payment_id', 'payments.project_id', 'payments.title', 'payments.remaining_payment', 'projects.project_name', 'sources.source_name')
            ->leftJoin('projects', 'payments.project_id', '=', 'projects.id')
            ->leftJoin('sources', 'projects.source_id', '=', 'sources.id')
            ->get();

        $payments = $payments->map(function ($payment) {
            return [
                'payment_id' => $payment->payment_id,
                'project_id' => $payment->project_id,
                'title' => $payment->title,
                'remaining_payment' => $payment->remaining_payment,
                'project_name' => $payment->project_name ?? 'N/A',
                'source_name' => $payment->source_name ?? 'N/A',
            ];
        });

        return response()->json($payments);
    }
    public function getTimeProgress(Request $request)
    {
        $userId        = $request->user_id;
        $dailyHours    = 8;
        $minutesPerDay = $dailyHours * 60;

        // Calculate today's worked time
        $todayLogs = UserLog::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'asc')
            ->pluck('created_at');

        $todayTotalMinutes = 0;
        if ($todayLogs->count() > 1) {
            for ($i = 0; $i < $todayLogs->count() - 1; $i++) {
                $start = Carbon::parse($todayLogs[$i]);
                $end   = Carbon::parse($todayLogs[$i + 1]);
                $todayTotalMinutes += $start->diffInMinutes($end);
            }
        }

        $todayTime = Helper::formatTime($todayTotalMinutes);

        // Fetch logs where status is 'Online' or 'Offline' for the current month
        $workingLogs = UserLog::where('user_id', $userId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereIn('status', ['Online', 'Offline'])
            ->orderBy('created_at', 'asc')
            ->get(['status', 'created_at']);

        $totalSeconds   = 0;
        $lastOnlineTime = null;

        // Loop through logs to calculate total worked seconds
        foreach ($workingLogs as $log) {
            if ($log->status === 'Online') {
                $lastOnlineTime = Carbon::parse($log->created_at); // Save Online time
            } elseif ($log->status === 'Offline' && $lastOnlineTime) {
                // If we have an Online time, calculate the difference
                $totalSeconds += $lastOnlineTime->diffInSeconds(Carbon::parse($log->created_at));
                $lastOnlineTime = null; // Reset for next pair
            }
        }

        // Convert seconds to minutes
        $totalMinutes = floor($totalSeconds / 60);
        $totalHours   = floor($totalSeconds / 3600);

        // Calculate total working days in the current month
        $startOfMonth = Carbon::now('UTC')->startOfMonth();
        $endOfMonth   = Carbon::now('UTC')->endOfMonth();
        $workingDays  = collect(CarbonPeriod::create($startOfMonth, $endOfMonth))
            ->filter(fn($date) => $date->isWeekday())
            ->count();

        $minutesPerDay        = 8 * 60; // Assuming 8 hours per working day
        $totalPossibleMinutes = $workingDays * $minutesPerDay;

        // Calculate progress percentage correctly
        $progressPercentage = ($totalPossibleMinutes > 0) ? ($totalMinutes / $totalPossibleMinutes) * 100 : 0;

        return response()->json([
            'today_time'          => $todayTime,
            'progress_percentage' => number_format($progressPercentage, 2),
        ]);
    }

    public function getTaskProgress(Request $request)
    {
        $userId = $request->user_id;

        $tasks          = DB::table('tasks')->whereRaw("FIND_IN_SET(?, finalized)", [$userId])->get();
        $totalTaskValue = $tasks->sum('task_value');
        $maxTaskValue   = DB::table('tasks')->sum('task_value');

        // Ensure percentage does not exceed 100
        $progressPercentage = $maxTaskValue > 0 ? min(($totalTaskValue / $maxTaskValue) * 100, 100) : 0;

        return response()->json([
            'progress_percentage' => round($progressPercentage, 2),
        ]);
    }

    // public function getTodayWorkTime($userId)
    // {
    //     $today = Carbon::today();

    //     // Fetch user_logs where status is Online or Offline and date is today
    //     $logs = DB::table('user_logs')
    //         ->where('user_id', $userId)
    //         ->whereDate('created_at', $today)
    //         ->whereIn('status', ['Online', 'Offline'])
    //         ->orderBy('created_at', 'asc')
    //         ->get();

    //     $totalSeconds   = 0;
    //     $lastOnlineTime = null;

    //     foreach ($logs as $log) {
    //         if ($log->status === 'Online') {
    //             $lastOnlineTime = Carbon::parse($log->created_at);
    //         } elseif ($log->status === 'Offline' && $lastOnlineTime) {
    //             $offlineTime = Carbon::parse($log->created_at);
    //             $totalSeconds += $offlineTime->diffInSeconds($lastOnlineTime);
    //             $lastOnlineTime = null;
    //         }
    //     }

    //     return gmdate("H:i:s", $totalSeconds);
    // }

    public function getOnlineToOfflineTime()
    {
        $user = Auth::user(); // Logged-in user

        // Sirf uss user ke logs get karein
        $logs = DB::table('user_logs')
            ->where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->orderBy('created_at', 'asc') // Oldest to newest sort karein
            ->get();

        $totalOnlineTime = 0;
        $lastOnlineTime  = null;

        foreach ($logs as $log) {
            if ($log->status === 'Online') {
                $lastOnlineTime = strtotime($log->created_at);
            } elseif ($log->status === 'Offline' && $lastOnlineTime) {
                $offlineTime = strtotime($log->created_at);
                $totalOnlineTime += ($offlineTime - $lastOnlineTime);
                $lastOnlineTime = null;
            }
        }

        return response()->json([
            'total_online_time' => $totalOnlineTime, // Seconds me return hoga
        ]);
    }
}
