<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Task;
use App\Models\User;
use App\Models\UserLog;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user             = Auth::user();
        $userRole         = Auth::user()->roles->pluck('name')->toArray();
        $userId           = Auth::id();
        $teamId           = $user->team_id;
        $userDepartmentId = $user->department_id;
        $userDepartments  = $user->user_departments;

        $filteredQuery = Task::select([
            'id',
            'project_id',
            'task_name',
            'task_type',
            'team_id',
            'task_stage',
            'task_priority',
            'author',
        ])->with([
            'project'              => function ($query) {
                $query->select('id');
            },
            'team'                 => function ($query) {
                $query->select('id', 'name');
            },
            'taskAssignments.user' => function ($query) {
                $query->select('id', 'name');
            },
            'taskType'             => function ($query) {
                $query->select('id', 'title');
            },
            'taskStage'            => function ($query) {
                $query->select('id', 'title');
            },
            'taskStatusLogs'       => function ($query) {
                $query->select('id', 'task_id', 'task_status_id')
                    ->whereRaw('task_status_logs.id = (SELECT MAX(tsl.id) FROM task_status_logs AS tsl WHERE tsl.task_id = task_status_logs.task_id)')
                    ->with(['task_status' => function ($q) {
                        $q->select('id', 'title');
                    }]);
            },
            'taskPriority'         => function ($query) {
                $query->select('id', 'title');
            },
            'author'               => function ($query) {
                $query->select('id', 'name');
            },
        ]);

        switch ($userRole[0] ?? '') {
            case 'Super Admin':
                $filteredQuery->whereHas('taskStatusLogs', function ($query) {
                    $query->where('task_status_id', 6)
                        ->whereRaw('task_status_logs.id = (
                            SELECT MAX(id)
                            FROM task_status_logs
                            WHERE task_status_logs.task_id = tasks.id
                        )');
                });
                $loadView = 'dashboard';
                break;

            case 'Team Lead':
                $filteredQuery->where('team_id', $teamId)
                    ->whereHas('taskStatusLogs', function ($query) {
                        $query->where('task_status_id', 5)
                            ->whereRaw('task_status_logs.id = (
                                SELECT MAX(id)
                                FROM task_status_logs
                                WHERE task_status_logs.task_id = tasks.id
                            )');
                    });
                $loadView = 'userTasks.tl-tasks';
                break;

            case 'CSRs':
                $filteredQuery->whereHas('author', function ($query) use ($userDepartmentId, $userDepartments) {
                    $query->where('department_id', $userDepartmentId)
                        ->orWhereRaw("FIND_IN_SET(department_id, ?)", [$userDepartments]);
                })->whereHas('taskStatusLogs', function ($query) {
                    $query->where('task_status_id', 4)
                        ->whereRaw('task_status_logs.id = (
                            SELECT MAX(id)
                            FROM task_status_logs
                            WHERE task_status_logs.task_id = tasks.id
                        )');
                });
                $loadView = 'userTasks.csr-tasks';
                break;

            default:
                $filteredQuery->whereHas('taskAssignments', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->whereHas('taskStatusLogs', function ($query) {
                    $query->where('task_status_id', 6)
                        ->whereRaw('task_status_logs.id = (
                            SELECT MAX(id)
                            FROM task_status_logs
                            WHERE task_status_logs.task_id = tasks.id
                        )');
                });
                $loadView = 'userTasks.my-tasks';
                break;
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $filteredQuery->where(function ($query) use ($searchTerm) {
                $query->where('tasks.id', 'like', '%' . $searchTerm . '%');
                $query->orWhere('tasks.project_id', 'like', '%' . $searchTerm . '%');
                $query->orWhere('tasks.task_name', 'like', '%' . $searchTerm . '%');
                $query->orWhereHas('taskType', function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%');
                });
                $query->orWhereHas('team', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
                $query->orWhereHas('taskAssignments.user', function ($q) use ($searchTerm) {
                    $q->where('users.name', 'like', '%' . $searchTerm . '%');
                });
                $query->orWhereHas('taskStatusLogs.task_status', function ($q) use ($searchTerm) {
                    $q->where('task_statuses.title', 'like', '%' . $searchTerm . '%');
                });
                $query->orWhereHas('taskStage', function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%');
                });
                $query->orWhereHas('taskPriority', function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%');
                });
                $query->orWhereHas('author', function ($q) use ($searchTerm) {
                    $q->where('users.name', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        $recordsTotalQuery = clone $filteredQuery;
        switch ($userRole[0] ?? '') {
            case 'Super Admin':
                $recordsTotal = Task::count();
                break;

            case 'Team Lead':
                $recordsTotal = Task::where('team_id', $teamId)
                    ->whereHas('taskStatusLogs', function ($query) {
                        $query->where('task_status_id', 5)
                            ->whereRaw('task_status_logs.id = (
                                SELECT MAX(id)
                                FROM task_status_logs
                                WHERE task_status_logs.task_id = tasks.id
                            )');
                    })->count();
                break;

            case 'CSRs':
                $recordsTotal = Task::whereHas('taskStatusLogs', function ($query) {
                    $query->where('task_status_id', 4)
                        ->whereRaw('task_status_logs.id = (
                            SELECT MAX(id)
                            FROM task_status_logs
                            WHERE task_status_logs.task_id = tasks.id
                        )');
                })->count();
                break;

            default:
                $recordsTotal = Task::whereHas('taskAssignments', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->whereHas('taskStatusLogs', function ($query) {
                    $query->where('task_status_id', 6);
                })->count();
                break;
        }

        $recordsFiltered = $filteredQuery->count();

        $columns         = ['id', 'task_name', 'task_type', 'team', 'task_status_logs', 'task_stage', 'task_priority', 'author'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn      = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder       = $request->input('order.0.dir', 'desc');

        $tasks = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get();

        $completedTasksCount = Task::whereHas('taskStatusLogs', function ($query) {
            $query->where('task_status_id', 1)
                ->whereRaw('task_status_logs.id = (
                        SELECT MAX(id)
                        FROM task_status_logs
                        WHERE task_status_logs.task_id = tasks.id
                    )');
        })->count();

        $incompleteTasksCount = Task::whereHas('taskStatusLogs', function ($query) {
            $query->where('task_status_id', '<>', 1)
                ->whereRaw('task_status_logs.id = (
                        SELECT MAX(id)
                        FROM task_status_logs
                        WHERE task_status_logs.task_id = tasks.id
                    )');
        })->count();

        $highPriorityTasksCount = Task::whereHas('taskPriority', function ($query) {
            $query->where('task_priority', 2);
        })->count();

        if ($request->ajax()) {
            return response()->json([
                'data'                   => $tasks,
                'draw'                   => intval($request->draw),
                'recordsTotal'           => $recordsTotal,
                'recordsFiltered'        => $recordsFiltered,
                'completedTasksCount'    => $completedTasksCount,
                'incompleteTasksCount'   => $incompleteTasksCount,
                'highPriorityTasksCount' => $highPriorityTasksCount,
            ]);
        }

        return view($loadView, compact(
            'tasks',
            'completedTasksCount',
            'incompleteTasksCount',
            'highPriorityTasksCount'
        ));
    }

    public function taskDetails(Request $request, $id)
    {
        $task = Task::with('project', 'department', 'team', 'assign', 'author', 'taskType', 'taskStage', 'taskStatusLogs.task_status', 'timeLogs')
            ->findOrFail($id);

        $totalTimeLogged = $task->timeLogs()
            ->selectRaw('SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, start_time, end_time))) AS total_time')
            ->value('total_time');

        return view('userTasks.task-details', compact('task', 'totalTimeLogged'));
    }

    public function updateTaskStatus(Request $request, $id)
    {
        $request->validate([
            'task_status' => 'required|exists:task_statuses,id',
        ]);

        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Task not found.',
            ], 404);
        }

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

        if (!$userChannelId) {
            return 0;
        }

        $whereRawCondition = "(seen IS NULL OR JSON_UNQUOTE(seen) = '[]')";

        return DB::table('ch_messages')
            ->where('to_channel_id', $userChannelId)
            ->where('from_id', '!=', Auth::id())
            ->whereNotNull('from_id')
            ->whereRaw($whereRawCondition)
            ->count();
    }

    public function getTimeProgress(Request $request)
    {
        $userId        = $request->user_id;
        $dailyHours    = 8;
        $minutesPerDay = $dailyHours * 60;

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

        $workingLogs = UserLog::where('user_id', $userId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereIn('status', ['Online', 'Offline'])
            ->orderBy('created_at', 'asc')
            ->get(['status', 'created_at']);

        $totalSeconds   = 0;
        $lastOnlineTime = null;

        foreach ($workingLogs as $log) {
            if ($log->status === 'Online') {
                $lastOnlineTime = Carbon::parse($log->created_at);
            } elseif ($log->status === 'Offline' && $lastOnlineTime) {
                $totalSeconds += $lastOnlineTime->diffInSeconds(Carbon::parse($log->created_at));
                $lastOnlineTime = null;
            }
        }

        $totalMinutes = floor($totalSeconds / 60);
        $totalHours   = floor($totalSeconds / 3600);

        $startOfMonth = Carbon::now('UTC')->startOfMonth();
        $endOfMonth   = Carbon::now('UTC')->endOfMonth();
        $workingDays  = collect(CarbonPeriod::create($startOfMonth, $endOfMonth))
            ->filter(fn($date) => $date->isWeekday())
            ->count();

        $minutesPerDay        = 8 * 60;
        $totalPossibleMinutes = $workingDays * $minutesPerDay;

        $progressPercentage = ($totalPossibleMinutes > 0) ? ($totalMinutes / $totalPossibleMinutes) * 100 : 0;

        return response()->json([
            'today_time'          => $todayTime,
            'progress_percentage' => number_format($progressPercentage, 2),
        ]);
    }

    public function getTaskProgress(Request $request)
    {
        $userId = $request->user_id;

        $tasks          = FacadesDB::table('tasks')->whereRaw("FIND_IN_SET(?, finalized)", [$userId])->get();
        $totalTaskValue = $tasks->sum('task_value');
        $maxTaskValue   = FacadesDB::table('tasks')->sum('task_value');

        $progressPercentage = $maxTaskValue > 0 ? min(($totalTaskValue / $maxTaskValue) * 100, 100) : 0;

        return response()->json([
            'progress_percentage' => round($progressPercentage, 2),
        ]);
    }
}
