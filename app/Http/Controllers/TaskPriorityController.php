<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskPriority;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TaskPriorityController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View TaskPriorities', only: ['index']),
            new Middleware('permission:Edit TaskPriority', only: ['edit']),
            new Middleware('permission:Add New TaskPriority', only: ['create']),
            new Middleware('permission:Delete TaskPriority', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filteredQuery = TaskPriority::select([
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

        $recordsTotal = TaskPriority::count();
        $recordsFiltered = $filteredQuery->count();

        $columns = ['id', 'title', 'description', 'order_by', 'author', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder = $request->input('order.0.dir', 'desc');

        $TaskPriorityQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        $TaskPriority = $TaskPriorityQuery->get();

        // Map TaskPriority to include action column for DataTable
        $TaskPriority->transform(function ($TaskPriority) {
            // Generate view action if permission is granted
            $viewAction = Auth::user()->can('View TaskPriorities')
                ? '<a href="#" class="btn btn-info btn-sm py-2" title="View" data-bs-toggle="modal" data-bs-target="#viewTaskPriorityModal"
                data-id="' . $TaskPriority->id . '">
                <i class="fa fa-eye" aria-hidden="true"></i>
            </a>'
                : '';
            // Generate edit action if permission is granted
            $editAction = Auth::user()->can('Edit TaskPriority')
                ? '<a href="' . route('task-priorities.edit', $TaskPriority->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
                        <i class="fa fa-edit"></i>
                   </a>'
                : '';
            // Generate delete action if permission is granted
            $deleteAction = Auth::user()->can('Delete TaskPriority')
                ? '<button class="btn btn-warning btn-sm py-2 delete-task-priority-btn" title="Delete" data-id="' . $TaskPriority->id . '">
                <i class="fa fa-trash"></i>
               </button>'
                : '';

            // Combine actions
            $TaskPriority->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $TaskPriority;
        });
        if ($request->ajax()) {
            return response()->json([
                'data' => $TaskPriority,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('taskPriorities.list', compact('TaskPriority'));
    }
    public function show($id)
    {
        $TaskPriority = TaskPriority::with('author')->findOrFail($id);
        return response()->json($TaskPriority);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('taskPriorities.create');
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

        // Create the TaskPriority with validated data
        if ($validator->passes()) {
            TaskPriority::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'order_by' => $request->input('order_by'),
                'author' => Auth::user()->id,
            ]);
            return redirect()->route('task-priorities.index')->with('success', 'Task Priority added successfully.');
        } else {
            return redirect()->route('task-priorities.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $taskPriority = TaskPriority::findOrFail($id);
        return view('taskPriorities.edit', compact('taskPriority'));
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
            $TaskPriority = TaskPriority::findOrFail($id);

            // Update the fields from the request
            $TaskPriority->title = $request->title;
            $TaskPriority->description = $request->description;
            $TaskPriority->order_by = $request->order_by;

            // Save the updated Source
            $TaskPriority->save();

            // Redirect back with success message
            return redirect()->route('task-priorities.index')->with('success', 'Task Priority updated successfully.');
        } else {
            // Return to the edit form with validation errors
            return redirect()->route('task-priorities.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $taskPriority = TaskPriority::findOrFail($id);
            $taskPriority->delete();

            return response()->json([
                'message' => 'Task Priority deleted successfully.',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete task priority: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
