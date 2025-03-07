<?php

namespace App\Http\Controllers;

use App\Models\TaskType;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View TaskTypes', only: ['index']),
            new Middleware('permission:Edit TaskType', only: ['edit']),
            new Middleware('permission:Add New TaskType', only: ['create']),
            new Middleware('permission:Delete TaskType', only: ['destroy']),
        ];
    }

    public function search(Request $request)
    {
        $query = $request->input('query', '');

        $taskTypes = TaskType::where('title', 'LIKE', "%{$query}%")
            ->orderBy('title', 'asc')
            ->take(10)
            ->get(['id', 'title', 'evg_time']);

        return response()->json($taskTypes);
    }

    public function getAvgTime(Request $request)
    {
        $taskType = TaskType::find($request->type_id);

        if ($taskType) {
            return response()->json([
                'success' => true,
                'data'    => $taskType,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Record not found',
            ], 404);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filteredQuery = TaskType::select('id', 'title', 'evg_time', 'description', 'order_by');
        // Search functionality
        if ($request->has('search') && is_string($request->search) && $request->search !== '') {
            $searchTerm = $request->search;

            $filteredQuery->where(function ($query) use ($searchTerm) {
                $query->where('id', 'like', '%' . $searchTerm . '%')
                    ->orWhere('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('evg_time', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('order_by', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('author', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('email', 'like', '%' . $searchTerm . '%');
                    });
            });
        }


        $recordsTotal    = TaskType::count();
        $recordsFiltered = $filteredQuery->count();

        $columns         = ['id', 'title', 'description', 'order_by', 'author', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn      = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder       = $request->input('order.0.dir', 'desc');

        $taskTypesQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        $taskTypes = $taskTypesQuery->get();

        // Map TaskTypes to include action column for DataTable
        $taskTypes->transform(function ($taskType) {
            // Generate view action if permission is granted
            $viewAction = Auth::user()->can('View TaskTypes')
                ? '<a href="#" class="btn btn-info btn-sm py-2" title="View" data-bs-toggle="modal" data-bs-target="#viewTaskTypeModal"
                data-id="' . $taskType->id . '">
                <i class="fa fa-eye" aria-hidden="true"></i>
            </a>'
                : '';
            // Generate edit action if permission is granted
            $editAction = Auth::user()->can('Edit TaskType')
                ? '<a href="' . route('task-types.edit', $taskType->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
                        <i class="fa fa-edit"></i>
                   </a>'
                : '';
            // Generate delete action if permission is granted
            $deleteAction = Auth::user()->can('Delete TaskType')
                ? '<button
            class="btn btn-warning btn-sm py-2 delete-task-type-btn"
            data-id="' . $taskType->id . '"
            title="Delete">
            <i class="fa fa-trash"></i>
       </button>'
                : '';

            // Combine actions
            $taskType->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $taskType;
        });
        if ($request->ajax()) {
            return response()->json([
                'data'            => $taskTypes,
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('taskTypes.list', compact('taskTypes'));
    }
    public function show($id)
    {
        $taskType = TaskType::with('author')->findOrFail($id);
        return response()->json($taskType);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('taskTypes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|min:3',
            'evg_time'    => 'nullable|numeric',
            'description' => 'nullable|string',
            'order_by'    => 'nullable|integer',
        ]);

        // Create the taskType with validated data
        if ($validator->passes()) {
            TaskType::create([
                'title'       => $request->input('title'),
                'evg_time'    => $request->input('evg_time'),
                'description' => $request->input('description'),
                'order_by'    => $request->input('order_by'),
                'author'      => Auth::user()->id,
            ]);
            return redirect()->route('task-types.index')->with('success', 'Task Type added successfully.');
        } else {
            return redirect()->route('task-types.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $taskType = TaskType::findOrFail($id);
        return view('taskTypes.edit', compact('taskType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|min:3',
            'evg_time'    => 'nullable|numeric',
            'description' => 'nullable|string',
            'order_by'    => 'nullable|integer',
        ]);

        // If validation passes
        if ($validator->passes()) {
            // Find the existing Source
            $taskType = TaskType::findOrFail($id);

            // Update the fields from the request
            $taskType->title       = $request->title;
            $taskType->evg_time    = $request->evg_time;
            $taskType->description = $request->description;
            $taskType->order_by    = $request->order_by;

            // Save the updated Source
            $taskType->save();

            // Redirect back with success message
            return redirect()->route('task-types.index')->with('success', 'Task Type updated successfully.');
        } else {
            // Return to the edit form with validation errors
            return redirect()->route('task-types.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the task type by ID or fail
            $taskType = TaskType::findOrFail($id);

            // Perform the delete
            $taskType->delete();

            // Return a JSON response for AJAX success
            return response()->json([
                'message' => 'Task Type deleted successfully.',
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            // Handle errors and return a JSON response for AJAX error
            return response()->json([
                'message' => 'Failed to delete task type: ' . $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }
}
