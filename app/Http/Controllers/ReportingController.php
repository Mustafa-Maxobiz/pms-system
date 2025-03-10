<?php
namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Department;
use App\Models\Project;
use App\Models\Source;
use App\Models\Task;
use App\Models\Team;
use App\Models\TeamTarget;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class ReportingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Reports', only: ['index']),
        ];
    }

    public function index(Request $request)
    {
        $filteredQuery = Task::with([
            'project:id,project_name',
            'team:id,name',
            'assign',
            'taskAssignments.user:id,name',
            'taskType:id,title',
            'taskStage:id,title',
            'taskPriority:id,title',
            'author:id,name',
            'taskStatusLogs.task_status',
        ]);

        // Apply Filters
        if ($request->filled('department_id')) {
            $filteredQuery->whereHas('team', fn($query) => $query->where('department_id', $request->department_id));
        }

        if ($request->filled('team_id')) {
            $filteredQuery->where('team_id', $request->team_id);
        }

        if ($request->filled('member_id')) {
            $filteredQuery->whereHas('taskAssignments.user', fn($query) => $query->where('user_id', $request->member_id));
        }

        if ($request->filled('client_id')) {
            $filteredQuery->whereHas('project.client', fn($query) => $query->where('id', $request->client_id));
        }

        if ($request->filled('project_id')) {
            $filteredQuery->where('project_id', $request->project_id);
        }

        if ($request->filled('source_id')) {
            $filteredQuery->whereHas('project.source', fn($query) => $query->where('id', $request->source_id));
        }

        if (! $request->filled('from_date') || ! $request->filled('to_date')) {
            $fromDate = Carbon::now()->startOfDay();
            $toDate   = Carbon::now()->endOfDay();
        } else {
            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $toDate   = Carbon::parse($request->to_date)->endOfDay();
        }

        $filteredQuery->whereBetween('created_at', [$fromDate, $toDate]);

        // Search Filtering
        if ($request->filled('search') && is_string($request->search)) {
            $searchTerm = trim($request->search);
            $filteredQuery->where(function ($query) use ($searchTerm) {
                $query->where('task_name', 'like', "%{$searchTerm}%")
                    ->orWhere('task_description', 'like', "%{$searchTerm}%")
                    ->orWhereHas('author', fn($q) => $q->where('name', 'like', "%{$searchTerm}%"))
                    ->orWhereHas('team', fn($q) => $q->where('name', 'like', "%{$searchTerm}%"));
            });
        }

        // Sorting & Pagination
        $columns         = ['id', 'task_name', 'task_description', 'author.name', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn      = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder       = $request->input('order.0.dir', 'desc');

        if ($sortColumn === 'author.name') {
            $filteredQuery->leftJoin('users as authors', 'tasks.author_id', '=', 'authors.id')
                ->orderBy('authors.name', $sortOrder);
        } else {
            $filteredQuery->orderBy($sortColumn, $sortOrder);
        }

        // Total Records Count
        $recordsTotal    = Task::count();
        $recordsFiltered = (clone $filteredQuery)->count();

        // Fetch Paginated Tasks
        $tasks = $filteredQuery
            ->skip((int) $request->input('start', 0))
            ->take((int) $request->input('length', 10))
            ->get();

        // Calculate total time for each task based on from_date and to_date
        foreach ($tasks as $task) {
            $task->total_time = $task->timeLogs()
                ->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->whereBetween('start_time', [$fromDate, $toDate]) // Only get logs within selected dates
                ->selectRaw("SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, start_time, end_time))) AS total_time")
                ->value('total_time') ?? '00:00:00';
        }

        if ($request->ajax()) {
            return response()->json([
                'data'            => $tasks,
                'draw'            => (int) $request->draw,
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        $data = [
            'departments' => Department::select('id', 'name')->orderBy('id', 'desc')->get(),
            'teams'       => Team::select('id', 'name')->orderBy('id', 'desc')->get(),
            'members'     => User::select('id', 'name')->orderBy('id', 'desc')->get(),
            'clients'     => Client::select('id', 'client_name')->orderBy('id', 'desc')->get(),
            'projects'    => Project::select('id', 'project_name')->orderBy('id', 'desc')->get(),
            'sorces'      => Source::select('id', 'source_name')->orderBy('id', 'desc')->get(),
        ];

        return view('reports.list', compact('tasks', 'data'));
    }

    public function seeMyReport(Request $request)
    {
        $today  = now()->toDateString();
        $userId = Auth::user()->id;

        $filteredQuery = Task::select(['id', 'task_name', 'task_value', 'task_description', 'start_date', 'end_date'])
            ->with(['timeLogs' => function ($query) {
                $query->select('task_id', 'created_at', 'start_time', 'end_time', 'user_id');
            }]);

        if ($request->has('search') && is_string($request->search) && $request->search !== '') {
            $searchTerm = $request->search;
            $filteredQuery->where(function ($query) use ($searchTerm) {
                $query->orWhere('id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('task_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('task_description', 'like', '%' . $searchTerm . '%');
            });
        }

        if (! $request->has('search_filter') && ! $request->has('from_date') && ! $request->has('to_date')) {
            $filteredQuery->whereHas('timeLogs', function ($q) use ($today, $userId) {
                $q->whereDate('created_at', $today)
                    ->where('user_id', $userId);
            });
        }

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = $request->from_date;
            $toDate   = $request->to_date;
            $filteredQuery->whereHas('timeLogs', function ($q) use ($fromDate, $toDate, $userId) {
                $q->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
                    ->where('user_id', $userId);
            });
        }

        if ($request->filled('search_filter')) {
            $filterType = $request->search_filter;
            if ($filterType == 'daily') {
                $filteredQuery->whereHas('timeLogs', function ($q) use ($today, $userId) {
                    $q->whereDate('created_at', $today)
                        ->where('user_id', $userId);
                });
            } elseif ($filterType == 'monthly') {
                $filteredQuery->whereHas('timeLogs', function ($q) use ($userId) {
                    $q->whereYear('created_at', now()->year)
                        ->whereMonth('created_at', now()->month)
                        ->where('user_id', $userId);
                });
            }
        }

        $tasks = $filteredQuery->get()->map(function ($task) {
            $firstLog             = $task->timeLogs->sortBy('created_at')->first();
            $task->log_created_at = $firstLog ? $firstLog->created_at->toDateString() : null;

            return $task;
        });

        //Calculate total time for each task and filter tasks with total_time > 0
        $tasks = $tasks->filter(function ($task) {
            $totalTimeInSeconds = $task->timeLogs()
                ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, start_time, end_time)) AS total_seconds')
                ->value('total_seconds');

            $task->total_time = gmdate('H:i:s', $totalTimeInSeconds ?? 0);

            return $totalTimeInSeconds > 0;
        });

        if ($request->ajax()) {
            return response()->json([
                'data'            => $tasks->values(),
                'draw'            => intval($request->draw),
                'recordsTotal'    => $tasks->count(),
                'recordsFiltered' => $tasks->count(),
            ]);
        }

        return view('reports.see-my-report');
    }

    public function myProgress(Request $request)
    {
        $user = Auth::user();

        // Get user's team target with related tasks
        $teamTarget = TeamTarget::with(['user.tasks.task' => function ($query) use ($user) {
            $query->where('task_stage', 2)
                ->whereRaw("FIND_IN_SET(?, finalized)", [$user->id]);
        }])
            ->where('user_id', $user->id)
            ->first();

        $totalTargetAmount = $teamTarget->target_amount ?? 0;
        $totalTaskValue    = 0;

        // Calculate total task value from related tasks
        if ($teamTarget && $teamTarget->user) {
            foreach ($teamTarget->user->tasks as $task) {
                if ($task->task) {
                    $totalTaskValue += $task->task->task_value;
                }
            }
        }

        // Calculate percentage achieved
        $percentage = $totalTargetAmount > 0 ? ($totalTaskValue / $totalTargetAmount) * 100 : 0;

        return response()->json([
            'totalTaskValue'    => $totalTaskValue,
            'totalTargetAmount' => $totalTargetAmount,
            'percentage'        => round($percentage, 2), // Rounded for cleaner display
        ]);
    }

    public function getTeam(Request $request)
    {
        $teams = Team::where('department_id', $request->department_id)->orderBy('id', 'desc')->get();

        return response()->json($teams);
    }

    public function getMember(Request $request)
    {
        $member = User::where('team_id', $request->team_id)->orderBy('id', 'desc')->get();

        return response()->json($member);
    }

    public function getProject(Request $request)
    {
        $project = Project::where('client_id', $request->client_id)->orderBy('id', 'desc')->get();

        return response()->json($project);
    }

    public function red_flag(Request $request)
    {
        $filteredQuery = Task::with([
            'taskType', 'team', 'taskAssignments.user', 'finalized', 'taskStage',
            'taskPriority', 'csr', 'author', 'project.client', 'timeLogs', 'taskStatusLogs.task_status',
        ]);
        // Search Filtering
        if ($request->filled('search') && is_string($request->search)) {
            $searchTerm = trim($request->search);
            $filteredQuery->where(function ($query) use ($searchTerm) {
                $query->where('task_name', 'like', "%{$searchTerm}%")
                    ->orWhere('task_description', 'like', "%{$searchTerm}%")
                    ->orWhereHas('author', fn($q) => $q->where('name', 'like', "%{$searchTerm}%"))
                    ->orWhereHas('team', fn($q) => $q->where('name', 'like', "%{$searchTerm}%"));
            });
        }

        // Total Records
        $recordsTotal    = Task::count();
        $recordsFiltered = $filteredQuery->count();

        // Sorting & Pagination
        $columns         = ['id', 'task_name', 'task_description', 'author.name', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn      = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder       = $request->input('order.0.dir', 'desc');

        $tasks = $filteredQuery
            ->orderBy($sortColumn, $sortOrder)
            ->skip((int) $request->input('start', 0))
            ->take((int) $request->input('length', 10))
            ->get();

        // Calculate total time for each task
        foreach ($tasks as $task) {
            $task->total_time = $task->timeLogs()
                ->selectRaw("SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, start_time, end_time))) AS total_time")
                ->value('total_time') ?? '00:00:00';
        }

        if ($request->ajax()) {
            return response()->json([
                'data'            => $tasks,
                'draw'            => (int) $request->draw,
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('reports.red-flag', compact('tasks'));
    }

}
