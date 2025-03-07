<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\TaskStatus;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TaskStatusController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View TaskStatus', only: ['index']),
            new Middleware('permission:Edit TaskStatus', only: ['edit']),
            new Middleware('permission:Add New TaskStatus', only: ['create']),
            new Middleware('permission:Delete TaskStatus', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filteredQuery = TaskStatus::select([
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

        $recordsTotal = TaskStatus::count();
        $recordsFiltered = $filteredQuery->count();

        $columns = ['id', 'title', 'description', 'order_by', 'author', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder = $request->input('order.0.dir', 'desc');

        $TaskStatusQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        $TaskStatus = $TaskStatusQuery->get();

        // Map TaskStatus to include action column for DataTable
        $TaskStatus->transform(function ($TaskStatus) {
            // Generate view action if permission is granted
            $viewAction = Auth::user()->can('View TaskStatus')
                ? '<a href="#" class="btn btn-info btn-sm py-2" title="View" data-bs-toggle="modal" data-bs-target="#viewTaskStatusModal"
                data-id="' . $TaskStatus->id . '">
                <i class="fa fa-eye" aria-hidden="true"></i>
            </a>'
                : '';
            // Generate edit action if permission is granted
            $editAction = Auth::user()->can('Edit TaskStatus')
                ? '<a href="' . route('task-status.edit', $TaskStatus->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
                        <i class="fa fa-edit"></i>
                   </a>'
                : '';
            // Generate delete action if permission is granted
            $deleteAction = Auth::user()->can('Delete TaskStatus')
                ? '<button class="btn btn-warning btn-sm py-2 delete-task-status-btn" title="Delete" data-id="' . $TaskStatus->id . '">
            <i class="fa fa-trash"></i>
       </button>'
                : '';
            // Combine actions
            $TaskStatus->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $TaskStatus;
        });
        if ($request->ajax()) {
            return response()->json([
                'data' => $TaskStatus,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('taskStatus.list', compact('TaskStatus'));
    }
    public function show($id)
    {
        $taskStatus = TaskStatus::with('author')->findOrFail($id);
        return response()->json($taskStatus);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('taskStatus.create');
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

        // Create the TaskStatus with validated data
        if ($validator->passes()) {
            TaskStatus::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'bg_color' => $request->input('bg_color'),
                'order_by' => $request->input('order_by'),
                'author' => Auth::user()->id,
            ]);
            return redirect()->route('task-status.index')->with('success', 'Task Status added successfully.');
        } else {
            return redirect()->route('task-status.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $taskStatus = TaskStatus::findOrFail($id);
        return view('taskStatus.edit', compact('taskStatus'));
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
            $TaskStatus = TaskStatus::findOrFail($id);

            // Update the fields from the request
            $TaskStatus->title = $request->title;
            $TaskStatus->description = $request->description;
            $TaskStatus->bg_color = $request->bg_color;
            $TaskStatus->order_by = $request->order_by;

            // Save the updated Source
            $TaskStatus->save();

            // Redirect back with success message
            return redirect()->route('task-status.index')->with('success', 'Task Status updated successfully.');
        } else {
            // Return to the edit form with validation errors
            return redirect()->route('task-status.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $taskStatus = TaskStatus::findOrFail($id);
            $taskStatus->delete();

            return response()->json([
                'message' => 'Task Status deleted successfully.',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete task status: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
