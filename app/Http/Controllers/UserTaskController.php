<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTaskController extends Controller
{
    public function myTasks(Request $request)
    {
        $userRole         = Auth::user()->roles->pluck('name')->toArray(); // Get all roles
        $userId           = Auth::id();
        $teamId           = Auth::user()->team_id;
        $userDepartmentId = Auth::user()->department_id;
        $userDepartments  = Auth::user()->user_departments;
       // Dasbord task Query
        $filteredQuery = Task::with([
            'project:id,project_name', 
            'team:id,name',
            'assign', 
            'taskAssignments.user:id,name', 
            'taskType:id,title',
            'taskStage:id,title',
            'taskPriority:id,title',
            'author:id,name',
            'taskStatusLogs.task_status'
        ]);

        if (in_array('Super Admin', $userRole)) {
            if ($request->input('task') === "completed") {
                $filteredQuery->whereHas('taskStatusLogs', function ($query) {
                    $query->where('task_status_id', 1)
                        ->whereRaw('task_status_logs.id = (
                            SELECT MAX(id)
                            FROM task_status_logs
                            WHERE task_status_logs.task_id = tasks.id
                        )');
                });
            } elseif ($request->input('task') === "incomplete") {
                $filteredQuery->whereHas('taskStatusLogs', function ($query) {
                    $query->where('task_status_id', '!=', 1)
                        ->whereRaw('task_status_logs.id = (
                            SELECT MAX(id)
                            FROM task_status_logs
                            WHERE task_status_logs.task_id = tasks.id
                        )');
                });
            } elseif ($request->input('task') === "priority") {
                $filteredQuery->where('task_priority', 2);
            } else {
                $filteredQuery->whereHas('taskStatusLogs', function ($query) {
                    $query->where('task_status_id', '!=', 1)
                        ->whereRaw('task_status_logs.id = (
                            SELECT MAX(id)
                            FROM task_status_logs
                            WHERE task_status_logs.task_id = tasks.id
                        )');
                });
            }
        } elseif (in_array('CSRs', $userRole)) {
            if ($request->input('csr')) {
                /*$filteredQuery->where(function ($query) use ($userDepartmentId, $userDepartments) {
                    $query->where('department_id', $userDepartmentId)->orWhereRaw("FIND_IN_SET(department_id, ?)", [$userDepartments]);
                })*/
                $filteredQuery->where(function ($query) use ($userDepartmentId, $userDepartments) {
                    $userDepartmentsArray = explode(',', $userDepartments); // Ensure it's an array
                    $query->where('department_id', $userDepartmentId)
                        ->orWhereIn('department_id', $userDepartmentsArray);
                })
                    ->whereHas('taskStatusLogs', function ($query) {
                        $query->where('task_status_id', '!=', 1)
                            ->whereRaw('task_status_logs.id = (
                              SELECT MAX(id)
                              FROM task_status_logs
                              WHERE task_status_logs.task_id = tasks.id
                          )');
                    });
            } else {
                $filteredQuery->where('department_id', $userDepartmentId)
                    ->whereHas('taskStatusLogs', function ($query) {
                        $query->where('task_status_id', 4)
                            ->whereRaw('task_status_logs.id = (
                                SELECT MAX(id)
                                FROM task_status_logs
                                WHERE task_status_logs.task_id = tasks.id
                            )');
                    });
            }
        } elseif (in_array('Team Lead', $userRole)) {
            if ($request->input('tl')) {
                $filteredQuery->where('team_id', $teamId)
                    ->whereHas('taskStatusLogs', function ($query) {
                        $query->where('task_status_id', '!=', 1)
                            ->whereRaw('task_status_logs.id = (
                                SELECT MAX(id)
                                FROM task_status_logs
                                WHERE task_status_logs.task_id = tasks.id
                            )');
                    });
            } else {
                $filteredQuery->where('team_id', $teamId)
                    ->whereHas('taskStatusLogs', function ($query) {
                        $query->where('task_status_id', 5)
                            ->whereRaw('task_status_logs.id = (
                                SELECT MAX(id)
                                FROM task_status_logs
                                WHERE task_status_logs.task_id = tasks.id
                            )');
                    });
            }
        } else {
            // Default for other roles
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
        }

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
        
            $filteredQuery->where(function ($query) use ($searchTerm) {
                // Basic task fields
                $query->where('tasks.id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tasks.project_id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tasks.task_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('tasks.task_description', 'like', '%' . $searchTerm . '%');
        
                // Related table searches
                $query->orWhereHas('project', function ($q) use ($searchTerm) {
                    $q->where('project_name', 'like', '%' . $searchTerm . '%');
                });
        
                $query->orWhereHas('team', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
        
                $query->orWhereHas('taskType', function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%');
                });
        
                $query->orWhereHas('taskStage', function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%');
                });
        
                $query->orWhereHas('taskPriority', function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%');
                });
        
                $query->orWhereHas('author', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
        
                $query->orWhereHas('taskAssignments.user', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
        
                $query->orWhereHas('taskStatusLogs.task_status', function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%');
                });
            });
        }
        //dd($filteredQuery->toSql(), $filteredQuery->getBindings());

        // Count total records based on role
        $recordsTotalQuery = clone $filteredQuery;
        switch ($userRole) {
            case 'Super Admin':
                $query = Task::query(); // Start with the base query

                // Apply filters based on the 'task' input
                if ($request->input('task') && $request->input('task') == "completed") {
                    $query->whereHas('taskStatusLogs', function ($query) {
                        $query->where('task_status_id', 1)
                            ->whereRaw('task_status_logs.id = (
                                SELECT MAX(id)
                                FROM task_status_logs
                                WHERE task_status_logs.task_id = tasks.id
                            )');
                    });
                } elseif ($request->input('task') && $request->input('task') == "incomplete") {
                    $query->whereHas('taskStatusLogs', function ($query) {
                        $query->where('task_status_id', '!=', 1)
                            ->whereRaw('task_status_logs.id = (
                                SELECT MAX(id)
                                FROM task_status_logs
                                WHERE task_status_logs.task_id = tasks.id
                            )');
                    });
                } elseif ($request->input('task') && $request->input('task') == "priority") {
                    $query->where('task_priority', 2);
                } else {
                    $query->whereHas('taskStatusLogs', function ($q) {
                        $q->where('task_status_id', 6)
                            ->whereRaw('task_status_logs.id = (
                                SELECT MAX(id)
                                FROM task_status_logs
                                WHERE task_status_logs.task_id = tasks.id
                            )');
                    });
                }
                // Get the total count after applying all filters
                $recordsTotal = $query->count();
                break;

            case 'Team Lead':
                if ($request->input('tl')) {
                    $recordsTotal = Task::where('team_id', $teamId)
                        ->whereHas('taskStatusLogs', function ($query) {
                            $query->where('task_status_id', '!=', 1)
                                ->whereRaw('task_status_logs.id = (
                                    SELECT MAX(id)
                                    FROM task_status_logs
                                    WHERE task_status_logs.task_id = tasks.id
                                )');
                        })->count();
                } else {
                    $recordsTotal = Task::where('team_id', $teamId)
                        ->whereHas('taskStatusLogs', function ($query) {
                            $query->where('task_status_id', 5)
                                ->whereRaw('task_status_logs.id = (
                                    SELECT MAX(id)
                                    FROM task_status_logs
                                    WHERE task_status_logs.task_id = tasks.id
                                )');
                        })->count();
                }
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

        // Columns for sorting
        $columns         = ['id', 'task_name', 'task_description', 'task_type', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn      = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder       = $request->input('order.0.dir', 'desc');

        // Apply sorting and pagination
        $tasks = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get();

        // Return JSON for AJAX
        if ($request->ajax()) {
            return response()->json([
                'data'            => $tasks,
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'showIDs'         => $userDepartments,
            ]);
        }

        // Return the view with tasks data
        return view('userTasks.my-tasks', compact('tasks'));
    }

    public function taskDetails(Request $request, $id)
    {
        $user = Auth::user();
        // Fetch task with relationships
        $task = Task::with('project', 'department', 'team', 'assign', 'author', 'taskType', 'taskStage', 'taskStatusLogs.task_status', 'timeLogs', 'taskAssignments.user')
            ->findOrFail($id);

        // Calculate total time logged using Eloquent relationship
        // $totalTimeLogged = $task->timeLogs()
        //     ->selectRaw('SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, start_time, end_time))) AS total_time')
        //     ->value('total_time');

        $totalTimeLogged = $task->timeLogs()
            ->selectRaw('SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, start_time, end_time))) AS total_time')
            // ->where('user_id', $user->id)
            ->value('total_time');

        $getUsers      = User::where('team_id', $task->team_id)->get();
        $AssignedUsers = User::whereIn('id', $task->taskAssignments->pluck('user_id'))->get(); // Fetch assigned users

        // Return the detailed task view
        return view('userTasks.task-details', compact('task', 'totalTimeLogged', 'getUsers', 'AssignedUsers'));
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
}
