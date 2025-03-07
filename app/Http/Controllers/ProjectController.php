<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ExternalStatus;
use App\Models\Project;
use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Projects', only: ['index']),
            new Middleware('permission:Edit Project', only: ['edit']),
            new Middleware('permission:Add New Project', only: ['create']),
            new Middleware('permission:Delete Project', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Initialize query
        $filteredQuery = Project::query()
        ->select([
            'projects.id',
            'projects.mainid',
            'projects.project_name',
            'projects.client_id',
            'projects.url',
            'projects.source_id',
            'projects.start_date',
            'projects.completion_date AS end_date',
            'projects.external_status',
            'projects.total_amount',
            'projects.author',
        ])
        ->with([
            'author:id,name',
            'client:id,client_name',
            'externalStatus:id,title',
            'source:id,source_name',
        ])
        ->leftJoin('clients', 'projects.client_id', '=', 'clients.id')
        ->leftJoin('sources', 'projects.source_id', '=', 'sources.id')
        ->leftJoin('external_statuses', 'projects.external_status', '=', 'external_statuses.id')
        ->leftJoin('users', 'projects.author', '=', 'users.id');
    

        // Get the logged-in user's department ID
        $user             = Auth::user();
        $userDepartmentId = $user->department_id;
        $userDepartments  = $user->user_departments;
        $userRole         = Auth::user()->roles->pluck('name')->toArray(); // Get all roles

        // Initialize query for counting filtered records
        // $filteredQuery = Project::query()
        //     ->select('projects.*') // Ensure base project fields are selected
        //     ->with('author', 'source', 'client', 'externalStatus');

        // // Join related tables for sorting
        // $filteredQuery->leftJoin('clients', 'projects.client_id', '=', 'clients.id')
        //     ->leftJoin('sources', 'projects.source_id', '=', 'sources.id')
        //     ->leftJoin('external_statuses', 'projects.external_status', '=', 'external_statuses.id');

        // $filteredQuery = Project::query()
        //     ->select('projects.*')
        //     ->with('author', 'source', 'client', 'externalStatus')
        //         'author' => function ($query) {
        //             $query->select('id', 'name');
        //         }, 
        //         'client' => function ($query) {
        //             $query->select('id', 'client_name');
        //         },
        //         'source' => function ($query) {
        //             $query->select('id', 'source_name');
        //         },
        //         'externalStatus' => function ($query) {
        //             $query->select('id', 'title');
        //         },
        //     ])
        //     ->leftJoin('clients', 'projects.client_id', '=', 'clients.id')
        //     ->leftJoin('sources', 'projects.source_id', '=', 'sources.id')
        //     ->leftJoin('external_statuses', 'projects.external_status', '=', 'external_statuses.id')
        //     ->leftJoin('users', 'projects.author', '=', 'users.id');

        // Apply department-based filtering only for CSR role
        // if (in_array('CSRs', $userRole)) {
        //     $filteredQuery->where(function ($query) use ($userDepartmentId, $userDepartments) {
        //         $userDepartmentsArray = explode(',', $userDepartments); // Ensure it's an array
        //         $query->where('department_id', $userDepartmentId)
        //             ->orWhereIn('department_id', $userDepartmentsArray);
        //     });
        // }

        // Apply search filter if provided
        if ($request->has('search') && is_string($request->search) && $request->search !== '') {
            $searchTerm = '%' . $request->search . '%';
            $filteredQuery->where(function ($query) use ($request, $searchTerm) {
                $query->where('projects.id', '=', (int)$request->search)
                    ->orWhere('projects.mainid', 'like', $searchTerm)
                    ->orWhere('projects.project_name', 'like', $searchTerm)
                    ->orWhere('clients.client_name', 'like', $searchTerm)
                    ->orWhere('projects.url', 'like', $searchTerm)
                    ->orWhere('sources.source_name', 'like', $searchTerm)
                    ->orWhere('projects.start_date', 'like', $searchTerm)
                    ->orWhere('projects.completion_date', 'like', $searchTerm)
                    ->orWhere('external_statuses.title', 'like', $searchTerm)
                    ->orWhere('projects.total_amount', 'like', $searchTerm);
            });
        }

        // Total records count (filtered by department for CSRs)
        $recordsTotal = Project::whereHas('author', function ($query) use ($userDepartmentId) {
            $query->where('department_id', $userDepartmentId);
        })->count();

        // Filtered records count (with search applied)
        $recordsFiltered = $filteredQuery->count();

        // Define columns for sorting
        $columns = [
            'projects.id',
            'projects.mainid',
            'projects.project_name',
            'clients.client_name',
            'projects.url',
            'sources.source_name',
            'projects.start_date',
            'projects.completion_date',
            'external_statuses.title',
            'projects.total_amount',
            'projects.created_at', // Kept for sorting compatibility
        ];

        // Get the column to sort by
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $columns[$sortColumnIndex] ?? 'projects.id';
        $sortOrder = $request->input('order.0.dir', 'desc');

        // Apply sorting and pagination
        $projectsQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        // Retrieve data
         //dd($filteredQuery->toSql(), $filteredQuery->getBindings());
        $projects = $projectsQuery->get();

        // Map projects to include action column for DataTable
        $projects->transform(function ($project) {
            $viewAction = Auth::user()->can('View Projects')
                ? '<a href="' . route('projects.details', $project->id) . '" class="btn btn-info btn-sm py-2" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>'
                : '';
            $editAction = Auth::user()->can('Edit Project')
                ? '<a href="' . route('projects.edit', $project->id) . '" class="btn btn-success btn-sm py-2" title="Edit"><i class="fa fa-edit"></i></a>'
                : '';
            $deleteAction = Auth::user()->can('Delete Project')
                ? '<a href="#" class="btn btn-warning btn-sm py-2 delete-project-btn" title="Delete" data-id="' . $project->id . '"><i class="fa fa-trash"></i></a>'
                : '';
            $project->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $project;
        });

        // Return JSON response for DataTables
        if ($request->ajax()) {
            return response()->json([
                'data' => $projects,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        // Otherwise, return the full view
        return view('projects.list', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sources        = Source::all();
        $clients        = Client::all();
        $externalStatus = ExternalStatus::orderBy('order_by', 'ASC')->get();
        return view('projects.create', compact('sources', 'clients', 'externalStatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'project_id' => 'required|unique:projects|max:255',
            'project_name'    => 'required|min:3|max:255',
            'source_id'       => 'nullable',
            'client_id'       => 'nullable',
            'url'             => 'nullable|url|max:255',
            'external_status' => 'nullable|max:255',
            'total_amount'    => 'nullable|numeric|min:0',
            'start_date'      => 'nullable|date',
            'target_date'     => 'nullable|date|after_or_equal:start_date',
            'completion_date' => 'nullable|date|after_or_equal:start_date',
            'project_alerts'  => 'nullable|max:255',
            'final_feedback'  => 'nullable|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('projects.create')
                ->withInput()
                ->withErrors($validator);
        }

        // Create the project with validated data
        if ($validator->passes()) {
            $Projectid = Project::create([
                'project_name'    => $request->input('project_name'),
                'mainid'          => $request->input('mainid'),
                'source_id'       => $request->input('source_id'),
                'client_id'       => $request->input('client_id'),
                'url'             => $request->input('url'),
                'external_status' => $request->input('external_status'),
                'total_amount'    => $request->input('total_amount'),
                'start_date'      => $request->input('start_date'),
                'target_date'     => $request->input('target_date'),
                'completion_date' => $request->input('completion_date'),
                'project_alerts'  => $request->input('project_alerts'),
                'final_feedback'  => $request->input('final_feedback'),
                'author'          => Auth::user()->id,
            ]);
            //return redirect()->route('projects.index')->with('success', 'Project added successfully.');
            return redirect()->route('projects.details', ['project' => $Projectid->id])->with('success', 'Project added successfully.');
        } else {
            return redirect()->route('projects.create')->withInput()->withErrors($validator);
        }
    }

    public function show($id)
    {
        $project = Project::with(['author', 'client', 'source'])->findOrFail($id);
        return response()->json($project);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $project        = Project::findOrFail($id);
        $sources        = Source::all();
        $clients        = Client::all();
        $externalStatus = ExternalStatus::orderBy('order_by', 'ASC')->get();
        return view('projects.edit', compact('project', 'sources', 'clients', 'externalStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'project_name'    => 'required|min:3',
            'source_id'       => 'nullable',
            'client_id'       => 'nullable',
            'url'             => 'nullable|url',
            'external_status' => 'nullable|max:3',
            'total_amount'    => 'nullable|numeric',
            'start_date'      => 'nullable|date',
            'target_date'     => 'nullable|date',
            'completion_date' => 'nullable|date',
            'project_alerts'  => 'nullable|min:3',
            'final_feedback'  => 'nullable|min:3',
        ]);

        // If validation passes
        if ($validator->passes()) {
            // Find the existing project
            $Project = Project::findOrFail($id);

            // Update the fields from the request
            $Project->project_name    = $request->project_name;
            $Project->mainid          = $request->mainid;
            $Project->source_id       = $request->source_id;
            $Project->client_id       = $request->client_id;
            $Project->url             = $request->url;
            $Project->external_status = $request->external_status;
            $Project->total_amount    = $request->total_amount;
            $Project->start_date      = $request->start_date;
            $Project->target_date     = $request->target_date;
            $Project->completion_date = $request->completion_date;
            $Project->project_alerts  = $request->project_alerts;
            $Project->final_feedback  = $request->final_feedback;

            // Save the updated project
            $Project->save();

            // Redirect back with success message
            return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
        } else {
            // Return to the edit form with validation errors
            return redirect()->route('projects.edit', $id)->withInput()->withErrors($validator);
        }
    }

    public function details(Request $request, $id)
    {
        $project = Project::with('source', 'client')->findOrFail($id);
        return view('projects.details', compact('project'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $Project = Project::findOrFail($id);
            $Project->delete();

            return response()->json(['success' => true, 'message' => 'Project deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete project.']);
        }
    }
}
