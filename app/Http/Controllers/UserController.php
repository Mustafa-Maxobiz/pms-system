<?php

namespace App\Http\Controllers;

use App\Events\MemberStatus;
use App\Events\TimeProgress;
use App\Events\UserLogs;
use App\Models\Department;
use App\Models\Team;
use App\Models\User;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Users', only: ['index']),
            new Middleware('permission:Edit User', only: ['edit']),
            new Middleware('permission:Add New User', only: ['create']),
            new Middleware('permission:Delete User', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $departments = Department::orderBy('name')->get();
        $teams       = Team::orderBy('name')->get();
        $roles       = Role::orderBy('name')->get();
        $filteredQuery = User::query()
            ->with(['roles:id,name'])
            ->select('id','profile_picture', 'name', 'email', 'username', 'created_at');

        // Search functionality
        if ($request->has('search') && is_string($request->search) && $request->search !== '') {
            $searchTerm = $request->search;

            $filteredQuery->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%')
                    ->orWhere('id', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('roles', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Filtering by department
        if ($request->has('department') && $request->department != '') {
            $filteredQuery->where('department_id', $request->department);
        }

        // Filtering by team
        if ($request->has('team') && $request->team != '') {
            $filteredQuery->where('team_id', $request->team);
        }

        // Filtering by role
        if ($request->has('role') && $request->role != '') {
            $filteredQuery->whereHas('roles', function ($query) use ($request) {
                $query->where('id', $request->role);
            });
        }

        $recordsTotal    = User::count();
        $recordsFiltered = $filteredQuery->count();

        $columns         = ['id', 'name', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn      = $columns[$sortColumnIndex];
        $sortOrder       = $request->input('order.0.dir', 'desc');

        // Apply sorting and pagination
        $UsersQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        // Fetch users with only the required fields and roles
        $users = $UsersQuery->get();

        // Transform the users to add actions
        $users->transform(function ($user) {
            $isSuperAdmin = Auth::user()->roles->pluck('name')->contains('Super Admin');
            $viewAction   = Auth::user()->can('View Users')
                ? '<a href="#" class="btn btn-info btn-sm py-2 view-user-btn" title="View" data-id="' . $user->id . '" data-bs-toggle="modal-user" data-bs-target="#viewUserModal">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                  </a>'
                : '';
            $editAction = Auth::user()->can('Edit User')
                ? '<a href="' . route('users.edit', $user->id) . '" class="btn btn-success btn-sm py-2" title="Edit"><i class="fa fa-edit"></i></a>'
                : '';
            $deleteAction = '';
            if (Auth::user()->can('Delete User') && (!$isSuperAdmin || Auth::id() !== $user->id)) {
                $deleteAction = '<button
                                    class="btn btn-warning btn-sm py-2 delete-user-btn"
                                    data-id="' . $user->id . '"
                                    title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>';
            }
            $rolesAction = '';
            if ($user->roles->pluck('name')->contains('CSRs')) {
                $rolesAction = Auth::user()->can('View Users')
                    ? '<a href="#" class="btn btn-info btn-sm py-2 view-user-btn" title="Department Access" data-id="' . $user->id . '" data-bs-toggle="modal-department" data-bs-target="#viewDepartmentUserModal">
                            <i class="fa fa-cogs" aria-hidden="true"></i>
                      </a>'
                    : '';
            }

            $user->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $rolesAction . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $user;
        });

        if ($request->ajax()) {
            return response()->json([
                'data'            => $users,
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('users.list', compact('users', 'departments', 'teams', 'roles'));
    }

    public function show($id)
    {
        $user = User::with(['roles', 'team', 'department'])->findOrFail($id);

        // Get role names as a comma-separated string
        $roleNames        = $user->roles->pluck('name')->toArray();
        $user->role_names = implode(', ', $roleNames);

        return response()->json($user);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles          = Role::orderBy('name', 'ASC')->get();
        $teams          = Team::orderBy('name', 'ASC')->get();
        $departments    = Department::orderBy('name', 'ASC')->get();
        return view('users.create', compact('roles', 'teams', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name'                   => 'required|string|min:3',
            'last_name'              => 'nullable|string|min:3',
            'father_name'            => 'nullable|string|min:3',
            'gender'                 => 'nullable|in:Male,Female',
            'dob'                    => 'nullable|date',
            'nic'                    => 'nullable|string|unique:users,nic',
            'address'                => 'nullable|string',
            'city'                   => 'nullable|string',
            'country'                => 'nullable|string',
            'mobile'                 => 'nullable|string',
            'phone'                  => 'nullable|string',
            'team_id'                => 'nullable|exists:teams,id',
            'department_id'          => 'nullable|exists:departments,id',
            'email'                  => 'required|email|unique:users,email',
            'work_email'             => 'nullable|email|unique:users,work_email',
            'username'               => 'nullable|string|unique:users,username',
            'password'               => 'required|min:8|same:confirmed',
            'confirmed'              => 'required',
            'profile_picture'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'next_of_kin'            => 'nullable|array',
            'next_of_kin.*.name'     => 'nullable|string|max:255',
            'next_of_kin.*.relation' => 'nullable|string|max:255',
            'next_of_kin.*.contact'  => 'nullable|string|max:255',
        ]);

        if ($validator->passes()) {
            $user = User::create([
                'name'              => $request->name,
                'last_name'         => $request->last_name,
                'father_name'       => $request->father_name,
                'gender'            => $request->gender,
                'dob'               => $request->dob,
                'nic'               => $request->nic,
                'address'           => $request->address,
                'city'              => $request->city,
                'country'           => $request->country,
                'mobile'            => $request->mobile,
                'phone'             => $request->phone,
                'email'             => $request->email,
                'work_email'        => $request->work_email,
                'username'          => $request->username,
                'password'          => bcrypt($request->password),
                'team_id'           => $request->team_id,
                'status'           => $request->status,
                'is_visible'           => $request->is_visible,
                'department_id'     => $request->department_id,
            ]);
            $request->validate([
                'nic'    => ['required', 'regex:/^\d{5}-\d{7}-\d$/'],
                'mobile' => ['required', 'regex:/^03\d{9}$/', 'digits:11'],
                'phone'  => ['nullable', 'regex:/^03\d{9}$/', 'digits:11'],
            ], [
                'nic.regex'     => 'The NIC format must be like 00000-0000000-0.',
                'mobile.regex'  => 'The mobile number must start with 03 and be 11 digits long.',
                'phone.regex'   => 'The phone number must start with 03 and be 11 digits long.',
                'mobile.digits' => 'The mobile number must be exactly 11 digits.',
                'phone.digits'  => 'The phone number must be exactly 11 digits.',
            ]);
            if ($request->hasFile('profile_picture')) {
                // Store the file in 'storage/app/public/profile_pictures'
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');

                // Save the relative path to the database
                $user->profile_picture = 'storage/' . $path;
                $user->save();
            }

            $user->syncRoles($request->role);
            // Create next_of_kin if provided
            if ($request->has('next_of_kin')) {
                foreach ($request->input('next_of_kin') as $nextOfKin) {
                    if ($nextOfKin['name'] != "") {
                        $user->nextOfKins()->create([
                            'name'     => $nextOfKin['name'],
                            'relation' => $nextOfKin['relation'],
                            'contact'  => $nextOfKin['contact'],
                        ]);
                    }
                }
            }

            return redirect()->route('users.index')->with('success', 'User added successfully.');
        } else {
            return redirect()->route('users.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user           = User::with('nextOfKins')->findOrFail($id);
        $roles          = Role::orderBy('name', 'ASC')->get();
        $teams          = Team::orderBy('name', 'ASC')->get();
        $departments    = Department::orderBy('name', 'ASC')->get();
        $hasRoles       = $user->roles->pluck('id');
        return view('users.edit', compact('user', 'roles', 'teams', 'departments', 'hasRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'                   => 'required|string|min:3',
            'last_name'              => 'nullable|string|min:3',
            'father_name'            => 'nullable|string|min:3',
            'gender'                 => 'nullable|in:Male,Female',
            'dob'                    => 'nullable|date',
            'nic'                    => 'nullable|string|unique:users,nic,' . $id,
            'address'                => 'nullable|string',
            'city'                   => 'nullable|string',
            'country'                => 'nullable|string',
            'mobile'                 => 'nullable|string',
            'phone'                  => 'nullable|string',
            'team_id'                => 'nullable|exists:teams,id',
            'department_id'          => 'nullable|exists:departments,id',
            'email'                  => 'required|email|unique:users,email,' . $id,
            'work_email'             => 'nullable|email|unique:users,work_email,' . $id,
            'username'               => 'nullable|string|unique:users,username,' . $id,
            'password'               => 'nullable|min:8|same:confirmed',
            'profile_picture'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'next_of_kin'            => 'nullable|array',
            'next_of_kin.*.name'     => 'nullable|string|max:255',
            'next_of_kin.*.relation' => 'nullable|string|max:255',
            'next_of_kin.*.contact'  => 'nullable|string|max:255',
        ]);

        if ($validator->passes()) {
            $user                    = User::findOrFail($id);
            $user->name              = $request->name;
            $user->last_name         = $request->last_name;
            $user->father_name       = $request->father_name;
            $user->gender            = $request->gender;
            $user->dob               = $request->dob;
            $user->nic               = $request->nic;
            $user->address           = $request->address;
            $user->city              = $request->city;
            $user->country           = $request->country;
            $user->mobile            = $request->mobile;
            $user->phone             = $request->phone;
            $user->email             = $request->email;
            $user->work_email        = $request->work_email;
            $user->username          = $request->username;
            $user->team_id           = $request->team_id;
            $user->status     = $request->status;
            $user->is_visible     = $request->is_visible;
            $user->department_id     = $request->department_id;
            

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            if ($request->hasFile('profile_picture')) {
                // Delete old picture if exists
                if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
                    \Storage::disk('public')->delete($user->profile_picture);
                }

                $path                  = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->profile_picture = $path;
            }

            $user->save();
            $user->syncRoles($request->role);

            if ($request->has('next_of_kin')) {
                $user->nextOfKins()->delete();
                foreach ($request->input('next_of_kin') as $nextOfKin) {
                    $user->nextOfKins()->create([
                        'name'     => $nextOfKin['name'],
                        'relation' => $nextOfKin['relation'],
                        'contact'  => $nextOfKin['contact'],
                    ]);
                }
            }

            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } else {
            return redirect()->route('users.edit', $id)->withInput()->withErrors($validator);
        }
    }

    public function updateUserDepartments(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Validate input (ensure array of department IDs)
        $request->validate([
            'departments' => 'array',
            'departments.*' => 'integer|exists:departments,id' // Ensure valid IDs
        ]);

        // Convert array to comma-separated string and update the user
        $user->update([
            'user_departments' => implode(',', $request->departments)
        ]);

        return response()->json(['message' => 'Department access updated successfully']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Delete profile picture if exists
            if ($user->profile_picture && \Storage::disk('public')->exists($user->profile_picture)) {
                \Storage::disk('public')->delete($user->profile_picture);
            }

            // Perform a soft delete
            $user->delete();

            return response()->json([
                'message' => 'User soft deleted successfully',
                'success' => true,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete user: ' . $e->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    public function ajaxUsers(Request $request)
    {
        $teamId = $request->get('team_id');
        $users  = User::where('team_id', $teamId)->get();

        return response()->json(['users' => $users]);
    }

    public function updateStatus(Request $request)
    {
        $status      = $request->input('status');
        $statusId    = $request->input('status_id', 9); // Default to 9 for custom status
        $status_time = $request->input('status_time');

        // Log the status update
        $userLog = UserLog::create([
            'user_id'        => Auth::user()->id,
            'user_status_id' => $statusId,
            'status'         => $status,
            'status_time'    => $status_time,
        ]);

        broadcast(new UserLogs());
        broadcast(new TimeProgress());

        // Respond with a success message
        return response()->json([
            'message' => 'Status updated successfully',
        ]);
    }
}
