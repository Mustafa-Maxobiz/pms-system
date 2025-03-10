<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\SubDepartment;
use App\Models\Department;

class SubDepartmentController extends Controller
{
    // Middleware for permissions
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Sub-Departments', only: ['index', 'show']),
            new Middleware('permission:Edit Sub-Department', only: ['edit']),
            new Middleware('permission:Add New Sub-Department', only: ['create']),
            new Middleware('permission:Delete Sub-Department', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the sub-departments.
     */
    public function index(Request $request)
    {
        // Base query with department relationship
        $filteredQuery = SubDepartment::with('department');

        // Apply search filter if provided
        if ($request->has('search') && is_string($request->search) && $request->search !== '') {
            $filteredQuery->where('name', 'like', '%' . $request->search . '%');
        }

        // Get total records count
        $recordsTotal = SubDepartment::count();
        $recordsFiltered = $filteredQuery->count();

        // Define columns for sorting
        $columns = ['id', 'name', 'description', 'department.name', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder = $request->input('order.0.dir', 'desc');

        // Handle sorting for related columns (e.g., department.name)
        if ($sortColumn === 'department.name') {
            $filteredQuery->join('departments', 'sub_departments.department_id', '=', 'departments.id')
                ->orderBy('departments.name', $sortOrder);
        } else {
            $filteredQuery->orderBy($sortColumn, $sortOrder);
        }

        // Apply pagination
        $subDepartments = $filteredQuery->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get();

        // Map sub-departments to include action buttons
        $subDepartments->transform(function ($subDepartment) {
            // Generate view action if permission is granted
            $viewAction = Auth::user()->can('View Sub-Departments')
                ? '<a href="#" class="btn btn-info btn-sm py-2 view-sub-department-btn" title="View" data-id="' . $subDepartment->id . '" data-bs-toggle="modal" data-bs-target="#viewSubDepartmentModal">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                  </a>'
                : '';

            // Generate edit action if permission is granted
            $editAction = Auth::user()->can('Edit Sub-Department')
                ? '<a href="' . route('subdepartments.edit', $subDepartment->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
                    <i class="fa fa-edit"></i>
                  </a>'
                : '';

            // Generate delete action if permission is granted
            $deleteAction = Auth::user()->can('Delete Sub-Department')
                ? '<button class="btn btn-warning btn-sm py-2 delete-sub-department-btn" title="Delete" data-id="' . $subDepartment->id . '">
                    <i class="fa fa-trash" aria-hidden="true"></i>
               </button>'
                : '';

            // Combine actions
            $subDepartment->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $subDepartment;
        });

        // Return JSON response for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'data' => $subDepartments,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        // Return view for non-AJAX requests
        return view('subDepartments.list', compact('subDepartments'));
    }
    public function ajaxSubdepartments(Request $request)
    {
        $departmentId = $request->input('department_id');

        // Fetch sub-departments based on the department ID
        $subdepartments = SubDepartment::where('department_id', $departmentId)->get();

        return response()->json(['subdepartments' => $subdepartments]);
    }

    /**
     * Show the form for creating a new sub-department.
     */
    public function create()
    {
        $departments = Department::all(); // Get all departments
        return view('subDepartments.create', compact('departments'));
    }

    /**
     * Store a newly created sub-department in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'description' => 'required',
            'department_id' => 'required|exists:departments,id', // Ensure valid department id
        ]);

        if ($validator->passes()) {
            SubDepartment::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'department_id' => $request->input('department_id'), // Save department association
                'author' => Auth::user()->id,
            ]);
            return redirect()->route('subdepartments.list')->with('success', 'Sub-Department created successfully.');
        } else {
            return redirect()->route('subdepartments.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified sub-department.
     */
    public function edit($id)
    {
        $subDepartment = SubDepartment::findOrFail($id);
        $departments = Department::all(); // Get all departments for the dropdown
        return view('subDepartments.edit', compact('subDepartment', 'departments'));
    }

    /**
     * Update the specified sub-department in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'description' => 'required',
            'department_id' => 'required|exists:departments,id', // Ensure valid department id
        ]);

        if ($validator->passes()) {
            $subDepartment = SubDepartment::findOrFail($id);
            $subDepartment->name = $request->name;
            $subDepartment->description = $request->description;
            $subDepartment->department_id = $request->department_id;
            $subDepartment->save();

            return redirect()->route('subdepartments.list')->with('success', 'Sub-Department updated successfully.');
        } else {
            return redirect()->route('subdepartments.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified sub-department from storage.
     */
    public function destroy($id)
    {
        try {
            $subDepartment = SubDepartment::findOrFail($id);
            $subDepartment->delete();

            return response()->json([
                'message' => 'Sub-Department deleted successfully.',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete sub-department: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }


    /**
     * Show the details of the sub-department.
     */
    public function show($id)
    {
        $subDepartment = SubDepartment::with(['author', 'department'])->find($id);

        if (!$subDepartment) {
            return response()->json(['message' => 'Sub-department not found'], 404);
        }

        return response()->json($subDepartment);
    }
}
