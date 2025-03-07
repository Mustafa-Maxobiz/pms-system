<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\UserStatus;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserStatusController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View UserStatus', only: ['index']),
            new Middleware('permission:Edit UserStatus', only: ['edit']),
            new Middleware('permission:Add New UserStatus', only: ['create']),
            new Middleware('permission:Delete UserStatus', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filteredQuery = UserStatus::select([
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

        $recordsTotal = UserStatus::count();
        $recordsFiltered = $filteredQuery->count();

        $columns = ['id', 'title', 'description', 'order_by', 'author', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder = $request->input('order.0.dir', 'desc');

        $UserStatusQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        $UserStatus = $UserStatusQuery->get();

        // Map UserStatus to include action column for DataTable
        $UserStatus->transform(function ($UserStatus) {
            $viewAction = Auth::user()->can('View UserStatus')
                ? '<a href="#" class="btn btn-info btn-sm py-2" title="View" data-bs-toggle="modal" data-bs-target="#viewUserStatusModal"
                data-id="' . $UserStatus->id . '">
                <i class="fa fa-eye" aria-hidden="true"></i>
            </a>' : '';

            $editAction = Auth::user()->can('Edit UserStatus')
                ? '<a href="' . route('user-status.edit', $UserStatus->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
                        <i class="fa fa-edit"></i>
                   </a>' : '';

            $deleteAction = Auth::user()->can('Delete UserStatus')
                ? '<button class="btn btn-warning btn-sm py-2 delete-user-status-btn" title="Delete" data-id="' . $UserStatus->id . '">
                           <i class="fa fa-trash"></i>
                      </button>'
                : '';

            $iconHtml = $UserStatus->icon ? '<i class="' . $UserStatus->icon . '"></i>' : '';

            $UserStatus->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            $UserStatus->icon_html = $iconHtml;

            return $UserStatus;
        });

        if ($request->ajax()) {
            return response()->json([
                'data' => $UserStatus,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('userStatus.list', compact('UserStatus'));
    }

    public function show($id)
    {
        $UserStatus = UserStatus::with('author')->findOrFail($id);
        return response()->json($UserStatus);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('userStatus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the icon field
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'description' => 'nullable|string',
            'order_by' => 'nullable|integer',
            'icon' => 'nullable|string', // Make sure icon is a string (Font Awesome class)
        ]);

        if ($validator->passes()) {
            UserStatus::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'order_by' => $request->input('order_by'),
                'icon' => $request->input('icon'), // Store the Font Awesome icon class
                'author' => Auth::user()->id,
            ]);
            return redirect()->route('user-status.index')->with('success', 'User Status added successfully.');
        } else {
            return redirect()->route('user-status.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $userStatus = UserStatus::findOrFail($id);
        return view('userStatus.edit', compact('userStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the icon field
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3',
            'description' => 'nullable|string',
            'order_by' => 'nullable|integer',
            'icon' => 'nullable|string',
        ]);

        if ($validator->passes()) {
            $UserStatus = UserStatus::findOrFail($id);

            $UserStatus->title = $request->title;
            $UserStatus->description = $request->description;
            $UserStatus->order_by = $request->order_by;
            $UserStatus->icon = $request->icon;

            $UserStatus->save();

            return redirect()->route('user-status.index')->with('success', 'User Status updated successfully.');
        } else {
            return redirect()->route('user-status.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $userStatus = UserStatus::findOrFail($id);
            $userStatus->delete();

            return response()->json([
                'message' => 'User Status deleted successfully.',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete user status: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
