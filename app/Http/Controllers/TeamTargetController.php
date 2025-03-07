<?php
namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Team;
use App\Models\TeamTarget;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeamTargetController extends Controller
{
    public function target()
    {
        $teams = Team::all();
        return view('teamTarget.team-target', compact('teams'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'target_amount' => 'required|numeric',
            'hours'         => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('target.index')
                ->withInput()
                ->withErrors($validator);
        }

        $data = [
            'author'        => Auth::user()->id,
            'user_id'       => $request->input('user_id'),
            'team_id'       => $request->input('team_id'),
            'target_amount' => $request->input('target_amount'),
            'hours'         => $request->input('hours'),
        ];

        $teamTarget = TeamTarget::create($data);

        if ($teamTarget) {
            return redirect()->route('target.index')
                ->with('success', 'Target saved successfully.');
        }

        return redirect()->route('target.index')
            ->with('error', 'Failed to save target. Please try again.');
    }

    public function edit(Request $request)
    {
        $teamTarget = TeamTarget::find($request->target_id);
        return view('teamTarget.edit', compact('teamTarget'));
    }

    public function update(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'target_amount' => 'required|numeric',
            'hours'         => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->route('target.index')
                ->withInput()
                ->withErrors($validator);
        }

        // Find the existing record by ID
        $teamTarget = TeamTarget::find($request->target_id);

        if (! $teamTarget) {
            return redirect()->route('target.index')
                ->with('error', 'Target not found.');
        }

        // Update the record using the update() method
        $teamTarget->update([
            'target_amount' => $request->input('target_amount'),
            'hours'         => $request->input('hours'),
        ]);

        return redirect()->route('target.index')
            ->with('success', 'Target updated successfully.');
    }

    public function show()
    {
        $teams       = Team::all();
        $departments = Department::select('id', 'name')->get();
        return view('teamTarget.show-target', compact('teams', 'departments'));
    }

    public function saveAllTargets(Request $request)
    {
        $users         = $request->user_id;
        $teams         = $request->team_id;
        $targetAmounts = $request->target_amount;
        $hours         = $request->hours;
        $targetIds     = $request->target_id;

        foreach ($users as $index => $userId) {
            if (! empty($targetAmounts[$index]) && ! empty($hours[$index])) {
                // Check if record exists
                $existingTarget = TeamTarget::where('user_id', $userId)
                    ->where('team_id', $teams[$index])
                    ->first();

                if ($existingTarget) {
                    // Update the existing record
                    $existingTarget->update([
                        'author'        => Auth::user()->id,
                        'target_amount' => $targetAmounts[$index],
                        'hours'         => $hours[$index],
                    ]);
                } else {
                    // Create a new record
                    TeamTarget::create([
                        'author'        => Auth::user()->id,
                        'user_id'       => $userId,
                        'team_id'       => $teams[$index],
                        'target_amount' => $targetAmounts[$index],
                        'hours'         => $hours[$index],
                    ]);
                }
            }
        }

        return response()->json(['message' => 'All targets saved successfully!']);
    }

    public function getTargetTeams(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate   = $request->to_date;

        $teams = Team::with([
            'users'            => function ($query) {
                $query->select('id', 'name', 'team_id', 'department_id', 'profile_picture');
            },
            'users.roles'      => function ($query) {
                $query->select('roles.id', 'roles.name');
            },
            'users.tasks'      => function ($query) use ($fromDate, $toDate) {
                $query->select('id', 'user_id', 'task_id')
                    ->whereHas('task', function ($taskQuery) use ($fromDate, $toDate) {
                        $taskQuery->whereBetween('created_at', [$fromDate, $toDate]);
                    });
            },
            'users.tasks.task' => function ($query) use ($fromDate, $toDate) {
                $query->select('id', 'task_name', 'task_stage', 'finalized', 'task_value', 'created_at')
                    ->whereBetween('created_at', [$fromDate, $toDate]);
            },
            'department',
            'target',
        ])
            ->where('id', $request->team_id)
            ->select('id', 'name', 'department_id')
            ->get();

        if ($teams->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No team found.',
            ], 404);
        }

        // Process each team
        $teams->each(function ($team) {
            if (! $team->users) {
                return;
            }

            $team->users->each(function ($user) use ($team) {

                $totalTaskValue = $user->tasks
                ? $user->tasks->filter(fn($task) =>
                    isset($task->task) &&
                    $task->task->task_stage == 2 &&
                    in_array($user->id, explode(',', $task->task->finalized))
                )->sum(fn($task) => isset($task->task->task_value) ? (float) $task->task->task_value : 0)
                : 0;

                if (! $team->target) {
                    return;
                }

                $team->target->each(function ($target) use ($user, $totalTaskValue) {
                    if ($target->user_id === $user->id) {
                        $target->task_value_total = $totalTaskValue;

                        $percentage = $target->target_amount > 0
                        ? round(($totalTaskValue / $target->target_amount) * 100, 2)
                        : 0;

                        $target->achievement_percentage = $percentage;
                    }
                });
            });
        });

        return response()->json([
            'success' => true,
            'teams'   => $teams,
        ]);
    }

    public function getTeamProgress(Request $request)
    {
        $teamId   = $request->team_id;
        $fromDate = $request->from_date;
        $toDate   = $request->to_date;

        // Team ka total target_amount sum karo
        $totalTarget = TeamTarget::where('team_id', $teamId)->sum('target_amount');

        // Team ke sabhi users ke task_value ka sum nikalna
        $totalAchieved = TeamTarget::with([
            'user.tasks'      => function ($query) use ($fromDate, $toDate) {
                $query->whereHas('task', function ($taskQuery) use ($fromDate, $toDate) {
                    $taskQuery->whereBetween('created_at', [$fromDate, $toDate]);
                });
            },
            'user.tasks.task' => function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            },
        ])
            ->where('team_id', $teamId)
            ->get()
            ->sum(function ($teamTarget) {
                return $teamTarget->user->tasks
                    ->filter(function ($task) {
                        return $task->task && $task->task->task_stage == 2; // task_stage ko nested task se check kar rahe hain
                    })
                    ->sum(function ($task) {
                        return (float) ($task->task->task_value ?? 0); // Agar task_value null ho to 0 le lo
                    });
            });

        // Percentage Calculate
        $completedPercentage = ($totalTarget > 0) ? ($totalAchieved / $totalTarget) * 100 : 0;
        $remainingPercentage = max(0, 100 - $completedPercentage); // Ensure remaining is not negative

        return response()->json([
            'completed' => round($completedPercentage, 2),
            'remaining' => max(0, round($remainingPercentage, 2)), // Remaining kabhi negative nahi hoga
        ]);
    }

    public function getDepTeams(Request $request)
    {
        $departmentId = $request->department_id;

        if (! $departmentId) {
            return response()->json([
                'data' => [],
            ], 400);
        }

        $teams = Team::where('department_id', $departmentId)->get();

        if ($teams->isEmpty()) {
            return response()->json([
                'data' => [],
            ], 404);
        }

        return response()->json([
            'data' => $teams,
        ], 200);
    }

}
