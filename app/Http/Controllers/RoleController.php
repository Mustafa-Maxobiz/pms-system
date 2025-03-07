<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Roles', only: ['index']),
            new Middleware('permission:Edit Role', only: ['edit']),
            new Middleware('permission:Add New Role', only: ['create']),
            new Middleware('permission:Delete Role', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filteredQuery = Role::select([
            'id',  
            'name', 
        ])->with([
            'permissions' => function ($q) {
                $q->select('id', 'name'); 
            }
        ]);

        // Apply search filter across requested fields
        if ($request->has('search') && is_string($request->search) && $request->search !== '') {
            $searchTerm = $request->search;
            $filteredQuery->where(function ($query) use ($searchTerm) {
                $query->orWhere('id', 'like', '%' . $searchTerm . '%')        
                    ->orWhere('name', 'like', '%' . $searchTerm . '%')       
                    ->orWhereHas('permissions', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }
        $recordsTotal = Role::count();
        $recordsFiltered = $filteredQuery->count();
        $columns = ['id', 'name', 'permissions'];

        // Get the column to sort by
        $sortColumnIndex = $request->input('order.0.column', 0); 
        $sortColumn = $columns[$sortColumnIndex] ?? 'id';       
        $sortOrder = $request->input('order.0.dir', 'desc');   

        // Handle sorting by permissions count
        if ($sortColumn === 'permissions') {
            $rolesQuery = $filteredQuery->leftJoin('role_has_permissions', 'roles.id', '=', 'role_has_permissions.role_id')
                ->select('roles.id', 'roles.name', \DB::raw('COUNT(role_has_permissions.permission_id) as permissions_count'))
                ->groupBy('roles.id', 'roles.name')
                ->orderBy('permissions_count', $sortOrder);
        } else {
            $rolesQuery = $filteredQuery->orderBy($sortColumn, $sortOrder);
        }

        // Apply pagination
        $roles = $rolesQuery->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get();

        // Transform roles to include permissions and action column
        $roles->transform(function ($role) {
            $viewAction = Auth::user()->can('View Roles')
                ? '<a href="#" class="btn btn-info btn-sm py-2 view-user-btn" title="View" data-id="' . $role->id . '" data-bs-toggle="modal" data-bs-target="#viewRoleModal"><i class="fa fa-eye" aria-hidden="true"></i></a>'
                : '';
            $editAction = Auth::user()->can('Edit Role')
                ? '<a href="' . route('roles.edit', $role->id) . '" class="btn btn-success btn-sm py-2" title="Edit"><i class="fa fa-edit"></i></a>'
                : '';
            $deleteAction = Auth::user()->can('Delete Role')
                ? '<button class="btn btn-warning btn-sm py-2 delete-role-btn" data-id="' . $role->id . '" title="Delete"><i class="fa fa-trash"></i></button>'
                : '';

            $role->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $role;
        });

        // Return JSON response for DataTables
        if ($request->ajax()) {
            return response()->json([
                'data' => $roles,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        // Return the view for non-AJAX requests
        return view('roles.list', compact('roles'));
    }
    public function show($roleId)
    {
        // Fetch the role and its permissions
        $role = Role::with('permissions')->findOrFail($roleId);

        // Return the role data as a JSON response
        return response()->json($role);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::orderBy('id', 'ASC')->get();
        return view('roles.create', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3'
        ]);

        if ($validator->passes()) {
            $role = Role::create(['name' => $request->name]);

            if (!empty($request->permission)) {
                foreach ($request->permission as $name) {
                    $role->givePermissionTo($name);
                }
            }

            return redirect()->route('roles.index')->with('success', 'Role added successfully.');
        } else {
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::orderBy('id', 'ASC')->get();
        return view('roles.edit', compact('role', 'hasPermissions', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id . '|min:3'
        ]);

        if ($validator->passes()) {
            $role = Role::findOrFail($id);
            $role->name = $request->name;
            $role->save();

            if (!empty($request->permission)) {
                $role->syncPermissions($request->permission);
            } else {
                $role->syncPermissions([]);
            }

            return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
        } else {
            return redirect()->route('roles.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Find the role by ID or fail (similar to how permission was found)
            $role = Role::findOrFail($id);

            // Perform the delete
            $role->delete();

            // Return a JSON response for AJAX success
            return response()->json([
                'message' => 'Role deleted successfully.',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            // Handle errors and return a JSON response for AJAX error
            return response()->json([
                'message' => 'Failed to delete role: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
