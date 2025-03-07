<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DepartmentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Departments', only: ['index']),
            new Middleware('permission:Edit Department', only: ['edit']),
            new Middleware('permission:Add New Department', only: ['create']),
            new Middleware('permission:Delete Department', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filteredQuery = Department::select([
            'id',
            'name',
            'description',
            'author'
        ])->with([
            'author' => function ($q) {
                $q->select('id', 'name');
            }
        ]);

        // Search filter across requested fields
        if ($request->has('search') && is_string($request->search) && $request->search !== '') {
            $searchTerm = $request->search;
            $filteredQuery->where(function ($query) use ($searchTerm) {
                $query->orWhere('id', 'like', '%' . $searchTerm . '%')        
                    ->orWhere('name', 'like', '%' . $searchTerm . '%')      
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('author', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        $recordsTotal = Department::count();
        $recordsFiltered = $filteredQuery->count();

        $columns = ['id', 'name', 'description', 'author', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder = $request->input('order.0.dir', 'desc');

        $departmentQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        $departments = $departmentQuery->get();

        // Map Sources to include action column for DataTable
        $departments->transform(function ($department) {
            // Generate view action if permission is granted
            $viewAction = Auth::user()->can('View Departments')
                ? '<a href="#" class="btn btn-info btn-sm py-2 view-department-btn" title="View" data-id="' . $department->id . '" data-bs-toggle="modal" data-bs-target="#viewDepartmentModal">
            <i class="fa fa-eye" aria-hidden="true"></i>
       </a>'
                : '';
            // Generate edit action if permission is granted
            $editAction = Auth::user()->can('Edit Department')
                ? '<a href="' . route('departments.edit', $department->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
                        <i class="fa fa-edit"></i>
                   </a>'
                : '';
            // Generate delete action if permission is granted
            $deleteAction = Auth::user()->can('Delete Department')
                ? '<button class="btn btn-warning btn-sm py-2 delete-department-btn" title="Delete" data-id="' . $department->id . '">
                    <i class="fa fa-trash" aria-hidden="true"></i>
               </button>'
                : '';

            // Combine actions
            $department->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $department;
        });

        if ($request->ajax()) {
            return response()->json([
                'data' => $departments,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('departments.list', compact('departments'));
    }
    public function show($id)
    {
        $department = Department::with('author')->findOrFail($id);
        return response()->json($department);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'description' => 'required',
        ]);

        // Create the Source with validated data
        if ($validator->passes()) {
            Department::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'author' => Auth::user()->id,
            ]);
            return redirect()->route('departments.index')->with('success', 'Department added successfully.');
        } else {
            return redirect()->route('departments.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'description' => 'required',
        ]);

        // If validation passes
        if ($validator->passes()) {
            // Find the existing Department
            $department = Department::findOrFail($id);

            // Update the fields from the request
            $department->name = $request->name;
            $department->description  = $request->description;

            // Save the updated Department
            $department->save();

            // Redirect back with success message
            return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
        } else {
            // Return to the edit form with validation errors
            return redirect()->route('departments.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->delete();

            return response()->json([
                'message' => 'Department deleted successfully.',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete department: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
