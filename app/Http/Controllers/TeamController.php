<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Teams', only: ['index']),
            new Middleware('permission:Edit Team', only: ['edit']),
            new Middleware('permission:Add New Team', only: ['create']),
            new Middleware('permission:Delete Team', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams       = Team::all();
        $departments = Department::all();
        $members     = User::all();
        return view('teams.card', compact('departments', 'teams', 'members'));
    }

    public function getTeams(Request $request)
    {
        $department = $request->input('department');
        $team       = $request->input('team');
        $member     = $request->input('member');

        // Initialize the query for teams
        $teams = Team::select('id', 'name', 'department_id')
            ->with(['department' => function ($query) {
                $query->select('id', 'name');
            }, 'users' => function ($query) use ($member) {
                $query->select('id', 'name', 'team_id', 'profile_picture'); 
                if ($member) {
                    $query->where('id', $member);
                }
                $query->with(['roles' => function ($q) {
                    $q->select('id', 'name'); 
                }]);
            }]);

        if ($department) {
            $teams->where('department_id', $department);
        }
        if ($team) {
            $teams->where('id', $team);
        }
        return response()->json($teams->get());
    }
    /*
    public function index(Request $request)
    {
        $filteredQuery = Team::with('author');

        if ($request->has('search') && is_string($request->search) && $request->search !== '') {
            $filteredQuery->where('name', 'like', '%' . $request->search . '%');
        }

        $recordsTotal = Team::count();
        $recordsFiltered = $filteredQuery->count();

        $columns = ['id', 'name', 'description', 'author', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder = $request->input('order.0.dir', 'desc');

        $teamQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        $teams = $teamQuery->get();

        // Map Sources to include action column for DataTable
        $teams->transform(function ($team) {
            // Generate view action if permission is granted
            $viewAction = Auth::user()->can('View teams')
                ? '<a href="#" class="btn btn-info btn-sm py-2" title="View">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                   </a>'
                : '';
            // Generate edit action if permission is granted
            $editAction = Auth::user()->can('Edit Team')
                ? '<a href="' . route('teams.edit', $team->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
                        <i class="fa fa-edit"></i>
                   </a>'
                : '';
            // Generate delete action if permission is granted
            $deleteAction = Auth::user()->can('Delete Team')
                ? '<a href="#" class="btn btn-warning btn-sm py-2" title="Delete" onclick="confirmDelete(' . $team->id . ')">
                        <i class="fa fa-trash"></i>
                   </a>
                   <form id="delete-form-' . $team->id . '" action="' . route('teams.destroy', $team->id) . '" method="POST" style="display:none;">
                        ' . csrf_field() . method_field('DELETE') . '
                   </form>'
                : '';
            // Combine actions
            $team->action = '<div class="btn-group" role="group" aria-label="Btn Group">'. $viewAction .' '. $editAction . ' ' . $deleteAction .'</div>';
            return $team;
        });

        if ($request->ajax()) {
            return response()->json([
                'data' => $teams,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('teams.list', compact('teams'));
    }
    */

    public function create()
    {
        $departments = Department::all(); // Fetch all departments
        return view('teams.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|min:3',
            'department_id' => 'nullable|exists:departments,id',
            'description'   => 'required',
        ]);

        // Create the Source with validated data
        if ($validator->passes()) {
            Team::create([
                'name'          => $request->input('name'),
                'department_id' => $request->input('department_id'),
                'description'   => $request->input('description'),
                'author'        => Auth::user()->id,
            ]);
            return redirect()->route('teams.index')->with('success', 'Team added successfully.');
        } else {
            return redirect()->route('teams.create')->withInput()->withErrors($validator);
        }
    }

    public function edit($id)
    {
        $team        = Team::findOrFail($id);
        $departments = Department::all(); // Fetch all departments
        return view('teams.edit', compact('team', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|min:3',
            'department_id' => 'nullable|exists:departments,id',
            'description'   => 'required',
        ]);

        // If validation passes
        if ($validator->passes()) {
            // Find the existing Team
            $team = Team::findOrFail($id);

            // Update the fields from the request
            $team->name          = $request->name;
            $team->department_id = $request->department_id;
            $team->description   = $request->description;

            // Save the updated Team
            $team->save();

            // Redirect back with success message
            return redirect()->route('teams.index')->with('success', 'Team updated successfully.');
        } else {
            // Return to the edit form with validation errors
            return redirect()->route('teams.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }
    public function ajaxTeams(Request $request)
    {
        $departmentId = $request->get('department_id');
        $teams        = Team::where('department_id', $departmentId)->get();

        return response()->json(['teams' => $teams]);
    }
}
