<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskStage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TaskStageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View TaskStages', only: ['index']),
            new Middleware('permission:Edit TaskStage', only: ['edit']),
            new Middleware('permission:Add New TaskStage', only: ['create']),
            new Middleware('permission:Delete TaskStage', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filteredQuery = TaskStage::select([
            'id',
            'title',
            'description',
            'order_by',
            'author'
        ])->with([
            'author' => function ($q) {
                $q->select('id', 'name');
            }
        ]);

        // Enhanced search filter across all requested fields
        if ($request->has('search') && is_string($request->search) && $request->search !== '') {
            $searchTerm = $request->search;
            $filteredQuery->where(function ($query) use ($searchTerm) {
                $query->orWhere('id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('order_by', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('author', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        $recordsTotal = TaskStage::count();
        $recordsFiltered = $filteredQuery->count();

        $columns = ['id', 'title', 'description', 'order_by', 'author', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder = $request->input('order.0.dir', 'desc');

        $TaskStageQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        $TaskStage = $TaskStageQuery->get();

        // Map TaskStage to include action column for DataTable
        $TaskStage->transform(function ($TaskStage) {
            // Generate view action if permission is granted
            $viewAction = Auth::user()->can('View TaskStage')
                ? '<a href="#" class="btn btn-info btn-sm py-2" title="View" data-bs-toggle="modal" data-bs-target="#viewTaskStageModal"
                data-id="' . $TaskStage->id . '">
                <i class="fa fa-eye" aria-hidden="true"></i>
            </a>'
                : '';
            // Generate edit action if permission is granted
            $editAction = Auth::user()->can('Edit TaskStage')
                ? '<a href="' . route('task-stages.edit', $TaskStage->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
                        <i class="fa fa-edit"></i>
                   </a>'
                : '';
            // Generate delete action if permission is granted
            $deleteAction = Auth::user()->can('Delete TaskStage')
                ? '<button class="btn btn-warning btn-sm py-2 delete-task-stage-btn" title="Delete" data-id="' . $TaskStage->id . '">
                    <i class="fa fa-trash"></i>
               </button>'
                : '';

            // Combine actions
            $TaskStage->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $TaskStage;
        });
        if ($request->ajax()) {
            return response()->json([
                'data' => $TaskStage,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('taskStages.list', compact('TaskStage'));
    }
    public function show($id)
    {
        $TaskStage = TaskStage::with('author')->findOrFail($id);
        return response()->json($TaskStage);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('taskStages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'description' => 'nullable|string',
            'order_by' => 'nullable|integer',
        ]);

        // Create the TaskStage with validated data
        if ($validator->passes()) {
            TaskStage::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'order_by' => $request->input('order_by'),
                'author' => Auth::user()->id,
            ]);
            return redirect()->route('task-stages.index')->with('success', 'Task Stage added successfully.');
        } else {
            return redirect()->route('task-stages.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $taskStage = TaskStage::findOrFail($id);
        return view('taskStages.edit', compact('taskStage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'description' => 'nullable|string',
            'order_by' => 'nullable|integer',
        ]);

        // If validation passes
        if ($validator->passes()) {
            // Find the existing Source
            $TaskStage = TaskStage::findOrFail($id);

            // Update the fields from the request
            $TaskStage->title = $request->title;
            $TaskStage->description = $request->description;
            $TaskStage->order_by = $request->order_by;

            // Save the updated Source
            $TaskStage->save();

            // Redirect back with success message
            return redirect()->route('task-stages.index')->with('success', 'Task Stage updated successfully.');
        } else {
            // Return to the edit form with validation errors
            return redirect()->route('task-stages.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $TaskStage = TaskStage::findOrFail($id);
            $TaskStage->delete();

            return response()->json([
                'message' => 'Task Stage deleted successfully.',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete task stage: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
