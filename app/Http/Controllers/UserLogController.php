<?php
namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Team;
use App\Models\User;
use App\Models\UserLog;
use App\Models\UserStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLogController extends Controller
{
    public function index(Request $request)
    {
        $users       = User::all();
        $statuses    = UserStatus::where('title', '!=', 'Custom Status')->get();
        $departments = Department::all();
        $teams       = Team::all();

        // Fetch only required fields and limit related data
        $filteredQuery = UserLog::select('id', 'user_id', 'user_status_id', 'status', 'status_time', 'created_at')
            ->with([
                'user:id,name',        // Only fetch user id & name
                'userStatus:id,title', // Fetch only status id & title
            ]);

        // Apply filters
        if ($request->filled('user_id')) {
            $filteredQuery->where('user_id', $request->user_id);
        }

        if ($request->filled('status_id')) {
            $filteredQuery->where('user_status_id', $request->status_id);
        }

        if ($request->filled('department_id')) {
            $filteredQuery->whereHas('user', function ($query) use ($request) {
                $query->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('team_id')) {
            $filteredQuery->whereHas('user', function ($query) use ($request) {
                $query->where('team_id', $request->team_id);
            });
        }

        // Updated Search Functionality
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';

            $filteredQuery->where(function ($query) use ($searchTerm) {
                $query->where('id', 'like', $searchTerm)
                    ->orWhere('user_id', 'like', $searchTerm)
                    ->orWhere('status', 'like', $searchTerm)
                    ->orWhere('status_time', 'like', $searchTerm)
                    ->orWhereHas('user', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('userStatus', function ($q) use ($searchTerm) {
                        $q->where('title', 'like', $searchTerm);
                    });
            });
        }

        // Apply date range filter
        if ($request->filled('start_date') || $request->filled('end_date')) {
            $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : null;
            $endDate   = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : null;

            if ($startDate && $endDate) {
                $filteredQuery->whereBetween('created_at', [$startDate, $endDate]);
            } elseif ($startDate) {
                $filteredQuery->where('created_at', '>=', $startDate);
            } elseif ($endDate) {
                $filteredQuery->where('created_at', '<=', $endDate);
            }
        }

        // Fetch data
        $userLogs = $filteredQuery->orderBy('created_at', 'desc')->get();

        // Split into two halves
        $halfCount = ceil($userLogs->count() / 2);
        $data1     = $userLogs->slice(0, $halfCount)->values();
        $data2     = $userLogs->slice($halfCount)->values();

        // Map UserLogs for DataTable actions
        $data1->transform(function ($userLog) {
            $viewAction = Auth::user()->can('View UserLog')
            ? '<a href="' . route('user-logs.show', $userLog->id) . '" class="btn btn-info btn-sm" title="View">
                    <i class="fa fa-eye"></i>
                </a>'
            : '';

            $deleteAction = Auth::user()->can('Delete UserLog')
            ? '<button class="btn btn-warning btn-sm delete-user-log-btn" title="Delete" data-id="' . $userLog->id . '">
                        <i class="fa fa-trash"></i>
                   </button>'
            : '';

            $userLog->action = '<div class="btn-group" role="group">' . $viewAction . ' ' . $deleteAction . '</div>';
            return $userLog;
        });

        $data2->transform(function ($userLog) {
            $viewAction = Auth::user()->can('View UserLog')
            ? '<a href="' . route('user-logs.show', $userLog->id) . '" class="btn btn-info btn-sm" title="View">
                    <i class="fa fa-eye"></i>
                </a>'
            : '';

            $deleteAction = Auth::user()->can('Delete UserLog')
            ? '<button class="btn btn-warning btn-sm delete-user-log-btn" title="Delete" data-id="' . $userLog->id . '">
                        <i class="fa fa-trash"></i>
                   </button>'
            : '';

            $userLog->action = '<div class="btn-group" role="group">' . $viewAction . ' ' . $deleteAction . '</div>';
            return $userLog;
        });

        if ($request->ajax()) {
            return response()->json([
                'data1'           => $data1,
                'data2'           => $data2,
                'draw'            => intval($request->draw),
                'recordsTotal'    => $userLogs->count(),
                'recordsFiltered' => $userLogs->count(),
            ]);
        }

        return view('userLogs.list', compact('userLogs', 'users', 'statuses', 'departments', 'teams'));
    }

    public function show($logId, Request $request)
    {
        $log      = UserLog::with(['user', 'userStatus'])->findOrFail($logId);
        $statuses = UserStatus::all();

        $status_id = $request->input('status_id');

        $allLogs = UserLog::where('user_id', $log->user_id)
            ->with(['userStatus'])
            ->orderBy('created_at', 'desc');

        if ($status_id) {
            $allLogs->where('user_status_id', $status_id);
        }

        $allLogs = $allLogs->get();

        return view('userLogs.details', compact('log', 'allLogs', 'statuses'));
    }

    public function details($id)
    {
        $userLog = UserLog::with(['user', 'userStatus'])->findOrFail($id);
        return view('user-logs.details', compact('userLog'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'user_status_id' => 'required|exists:user_statuses,id',
        ]);

        UserLog::create([
            'user_id'        => $validated['user_id'],
            'user_status_id' => $validated['user_status_id'],
            'logged_at'      => now(),
        ]);

        return redirect()->route('user-logs.index')->with('success', 'User Log Created Successfully!');
    }

    public function destroy($logId)
    {
        try {
            $log = UserLog::findOrFail($logId);
            $log->delete();

            return response()->json([
                'message' => 'User Log deleted successfully.',
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete user log: ' . $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }
}
