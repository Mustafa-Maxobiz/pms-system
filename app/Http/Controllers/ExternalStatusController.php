<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\ExternalStatus;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ExternalStatusController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View ExternalStatus', only: ['index']),
            new Middleware('permission:Edit ExternalStatus', only: ['edit']),
            new Middleware('permission:Add New ExternalStatus', only: ['create']),
            new Middleware('permission:Delete ExternalStatus', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filteredQuery = externalStatus::select([
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

        $recordsTotal = externalStatus::count();
        $recordsFiltered = $filteredQuery->count();

        $columns = ['id', 'title', 'description', 'order_by', 'author', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder = $request->input('order.0.dir', 'desc');

        $externalStatusQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        $externalStatus = $externalStatusQuery->get();

        // Map externalStatus to include action column for DataTable
        $externalStatus->transform(function ($externalStatus) {
            // Generate view action if permission is granted
            $viewAction = Auth::user()->can('View ExternalStatus')
                ? '<a href="#" class="btn btn-info btn-sm py-2" title="View" data-bs-toggle="modal" data-bs-target="#viewexternalStatusModal"
                data-id="' . $externalStatus->id . '">
                <i class="fa fa-eye" aria-hidden="true"></i>
            </a>'
                : '';
            // Generate edit action if permission is granted
            $editAction = Auth::user()->can('Edit ExternalStatus')
                ? '<a href="' . route('external-status.edit', $externalStatus->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
                        <i class="fa fa-edit"></i>
                   </a>'
                : '';
            // Generate delete action if permission is granted
            $deleteAction = Auth::user()->can('Delete ExternalStatus')
                ? '<button class="btn btn-warning btn-sm py-2 delete-external-status-btn" title="Delete" data-id="' . $externalStatus->id . '">
                    <i class="fa fa-trash"></i>
               </button>'
                : '';

            // Combine actions
            $externalStatus->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';

            return $externalStatus;
        });
        if ($request->ajax()) {
            return response()->json([
                'data' => $externalStatus,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('externalStatus.list', compact('externalStatus'));
    }
    public function show($id)
    {
        $externalStatus = ExternalStatus::with('author')->findOrFail($id);
        return response()->json($externalStatus);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('externalStatus.create');
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

        // Create the externalStatus with validated data
        if ($validator->passes()) {
            ExternalStatus::create([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'order_by' => $request->input('order_by'),
                'author' => Auth::user()->id,
            ]);
            return redirect()->route('external-status.index')->with('success', 'External Status added successfully.');
        } else {
            return redirect()->route('external-status.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $externalStatus = ExternalStatus::findOrFail($id);
        return view('externalStatus.edit', compact('externalStatus'));
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
            $externalStatus = ExternalStatus::findOrFail($id);

            // Update the fields from the request
            $externalStatus->title = $request->title;
            $externalStatus->description = $request->description;
            $externalStatus->order_by = $request->order_by;

            // Save the updated Source
            $externalStatus->save();

            // Redirect back with success message
            return redirect()->route('external-status.index')->with('success', 'External Status updated successfully.');
        } else {
            // Return to the edit form with validation errors
            return redirect()->route('external-status.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $externalStatus = ExternalStatus::findOrFail($id);
            $externalStatus->delete();

            return response()->json([
                'message' => 'External Status deleted successfully.',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete external status: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
