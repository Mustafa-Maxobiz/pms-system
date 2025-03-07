<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Permissions', only: ['index', 'view']),
            new Middleware('permission:Edit Permission', only: ['edit']),
            new Middleware('permission:Add New Permission', only: ['create']),
            new Middleware('permission:Delete Permission', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $filteredQuery = Permission::query();

        // Apply search filter if provided
        if ($request->has('search') && ! empty($request->search)) {
            $filteredQuery->where('name', 'like', '%' . $request->search . '%');
        }

                                                                           // Fetch paginated results (10 per page)
        $permissions = $filteredQuery->orderBy('id', 'desc')->paginate(30); // Change limit if needed

        $permissions->transform(function ($permission) {
            $viewAction = Auth::user()->can('View Permissions')
            ? '<a href="#" class="btn btn-info btn-sm py-2 view-task-btn" title="View" data-id="' . $permission->id . '" data-bs-toggle="modal" data-bs-target="#viewPermissionModal">
                     <i class="fa fa-eye"></i></a>' : '';

            $editAction = Auth::user()->can('Edit Permission')
            ? '<a href="' . route('permissions.edit', $permission->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
                     <i class="fa fa-edit"></i></a>' : '';

            $deleteAction = Auth::user()->can('Delete Permission')
            ? '<button class="btn btn-warning btn-sm py-2 delete-permission-btn"
                     data-id="' . $permission->id . '" title="Delete">
                     <i class="fa fa-trash"></i></button>' : '';

            $permission->action = $viewAction . ' ' . $editAction . ' ' . $deleteAction;
            return $permission;
        });

        if ($request->ajax()) {
            return response()->json([
                'data'       => $permissions->items(), // Get only current page data
                'pagination' => [
                    'total'         => $permissions->total(),
                    'per_page'      => $permissions->perPage(),
                    'current_page'  => $permissions->currentPage(),
                    'last_page'     => $permissions->lastPage(),
                    'next_page_url' => $permissions->nextPageUrl(),
                    'prev_page_url' => $permissions->previousPageUrl(),
                ],
            ]);
        }

        return view('permissions.list', compact('permissions'));
    }

    // public function index(Request $request)
    // {
    //     // Initialize query for counting filtered records
    //     $filteredQuery = Permission::query();

    //     // Apply search filter if provided
    //     if ($request->has('search') && is_string($request->search) && $request->search !== '') {
    //         $filteredQuery->where('name', 'like', '%' . $request->search . '%');
    //     }

    //     // Total records count (without filtering)
    //     $recordsTotal = Permission::count();

    //     // Filtered records count (with search applied)
    //     $recordsFiltered = $filteredQuery->count();

    //     // Apply sorting and pagination to the main query
    //     $columns = [
    //         'id', 'name', 'created_at', // Define valid columns for sorting
    //     ];

    //                                                              // Get the column to sort by
    //     $sortColumnIndex = $request->input('order.0.column', 0); // Default to the first column
    //     $sortColumn      = $columns[$sortColumnIndex];           // Column name for sorting

    //                                                          // Get the direction to sort
    //     $sortOrder = $request->input('order.0.dir', 'desc'); // Default to 'desc'

    //     // Apply sorting and pagination to the query
    //     $permissionsQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
    //         ->skip($request->input('start', 0))
    //         ->take($request->input('length', 10));

    //     // Retrieve data for the table
    //     $permissions = $permissionsQuery->get();

    //     // Map permissions to include action column for DataTable
    //     $permissions->transform(function ($permission) {
    //         // Generate view action if permission is granted
    //         $viewAction = Auth::user()->can('View Permissions')
    //         ? '<a href="#" class="btn btn-info btn-sm py-2 view-task-btn" title="View" data-id="' . $permission->id . '" data-bs-toggle="modal" data-bs-target="#viewPermissionModal">
    //                     <i class="fa fa-eye" aria-hidden="true"></i>
    //             </a>'
    //         : '';
    //         // Generate edit action if permission is granted
    //         $editAction = Auth::user()->can('Edit Permission')
    //         ? '<a href="' . route('permissions.edit', $permission->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
    //                     <i class="fa fa-edit"></i>
    //                </a>'
    //         : '';
    //         // Generate delete action if permission is granted
    //         $deleteAction = Auth::user()->can('Delete Permission')
    //         ? '<button
    //         class="btn btn-warning btn-sm py-2 delete-permission-btn"
    //         data-id="' . $permission->id . '"
    //         title="Delete">
    //         <i class="fa fa-trash"></i>
    //    </button>'
    //         : '';

    //     // Combine actions
    //         $permission->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
    //         return $permission;

    //     });

    //     // Return JSON response for DataTables
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'data'            => $permissions,
    //             'draw'            => intval($request->draw),
    //             'recordsTotal'    => $recordsTotal,
    //             'recordsFiltered' => $recordsFiltered,
    //         ]);
    //     }

    //     // Otherwise, return the full view
    //     return view('permissions.list', compact('permissions'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3',
        ]);

        if ($validator->passes()) {
            Permission::create(['name' => $request->name]);
            return redirect()->route('permissions.index')->with('success', 'Permission added successfully.');
        } else {
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name,' . $id . '|min:3',
        ]);

        if ($validator->passes()) {
            $permission       = Permission::findOrFail($id);
            $permission->name = $request->name;
            $permission->save();

            return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
        } else {
            return redirect()->route('permissions.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the permission by ID or fail
            $permission = Permission::findOrFail($id);

            // Perform the delete
            $permission->delete();

            // Return a JSON response for AJAX success
            return response()->json([
                'message' => 'Permission deleted successfully.',
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            // Handle errors and return a JSON response for AJAX error
            return response()->json([
                'message' => 'Failed to delete permission: ' . $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    /**
     * Fetch permission details for view modal (AJAX)
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);

        // Return the permission data in JSON format
        return response()->json([
            'id'         => $permission->id,
            'name'       => $permission->name,
            'created_at' => $permission->created_at->toDateString(),
        ]);
    }
}
