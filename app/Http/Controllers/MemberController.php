<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\UserLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

// Import the UserLog model for status relationship

class MemberController extends Controller
{
    public function index()
    {
        return view('members.list');
    }

    public function getTeams(Request $request)
    {
        $teams = Team::with([
            'department:id,name',
            'users' => function ($query) {
                $query->select('id', 'name', 'profile_picture', 'team_id')
                    ->with([
                        'logs' => function ($logQuery) {
                            $logQuery->latest()->limit(1);
                        },
                        'tasks' => function ($taskQuery) {
                            $taskQuery->select('id', 'user_id', 'task_id');
                        },
                    ]);
            },
        ])->get();

        // Format the response to exclude logs
        $formattedTeams = $teams->map(function ($team) {
            return [
                'id' => $team->id,
                'name' => $team->name,
                'department' => $team->department,
                'users' => $team->users->map(function ($user) {
                    return [
                        'name' => $user->name,
                        'avatar' => $user->profile_picture, 
                        'logs' => $user->logs->first() ? [ 
                            'id' => $user->logs->first()->id,
                            'status' => $user->logs->first()->status,
                        ] : null,
                        'tasks' => $user->tasks, 
                    ];
                }),
            ];
        });

        return response()->json($formattedTeams);
    }

    public function getMemberStats()
    {
        $userStatuses = UserLog::where('status', 'Online')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('user_logs')
                    ->groupBy('user_id');
            })
            ->get();

        $userStatuses = $userStatuses->map(function ($log) {
            return [
                'user_id' => $log->user_id,
                'status'  => $log->status,
                'time'    => Carbon::parse($log->created_at)->format('H:i:s'),
            ];
        });

        // Total users count from Users table
        $totalUsers = User::count();

        $activeCount = count($userStatuses);
        // Inactive users count (Total Users - Active Users)
        $inactiveCount = $totalUsers - $activeCount;

        $avatars = User::whereIn('id', collect($userStatuses)->pluck('user_id')->take(3))
            ->get(['id', 'profile_picture', 'name']);

        $avatarsArray = $avatars->map(function ($user) {
            return [
                'name'            => $user->name,
                'profile_picture' => $user->profile_picture,
            ];
        });

        return response()->json([
            'active'   => $activeCount,
            'inactive' => $inactiveCount,
            'avatars'  => $avatarsArray,
            'statuses' => $userStatuses,
        ]);
    }

    // public function getMemberStats()
    // {
    //     // Fetch the latest status for each user
    //     $userStatuses = UserLog::select('user_id', 'status')
    //         ->whereIn('id', function ($query) {
    //             $query->selectRaw('MAX(id)')->from('user_logs')->groupBy('user_id');
    //         })
    //         ->get();

    //     // Calculate active and inactive counts
    //     $activeCount = $userStatuses->where('status', 'Online')->count();
    //     $inactiveCount = $userStatuses->count() - $activeCount;

    //     // Fetch user profile pictures for the top 3 users
    //     $avatars = User::whereIn('id', $userStatuses->pluck('user_id')->take(3))
    //         ->pluck('profile_picture');

    //     return response()->json([
    //         'active' => $activeCount,
    //         'inactive' => $inactiveCount,
    //         'avatars' => $avatars,
    //         'statuses' => $userStatuses,
    //     ]);
    // }

}
