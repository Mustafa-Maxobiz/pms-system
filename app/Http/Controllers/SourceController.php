<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Source;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SourceController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Sources', only: ['index']),
            new Middleware('permission:Edit Source', only: ['edit']),
            new Middleware('permission:Add New Source', only: ['create']),
            new Middleware('permission:Delete Source', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filteredQuery = Source::select([
            'id',
            'source_name',
            'source_url',
            'source_type',
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
                    ->orWhere('source_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('source_url', 'like', '%' . $searchTerm . '%')
                    ->orWhere('source_type', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('author', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        $recordsTotal = Source::count();
        $recordsFiltered = $filteredQuery->count();

        $columns = ['id', 'source_name', 'source_type', 'author', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder = $request->input('order.0.dir', 'desc');

        $sourcesQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        $sources = $sourcesQuery->get();

        // Map Sources to include action column for DataTable
        $sources->transform(function ($source) {
            // Generate view action if permission is granted
            $viewAction = Auth::user()->can('View Sources')
                ? '<a href="#" class="btn btn-info btn-sm py-2" title="View" data-bs-toggle="modal" data-bs-target="#viewSourceModal"
                data-id="' . $source->id . '">
                <i class="fa fa-eye" aria-hidden="true"></i>
            </a>'
                : '';
            // Generate edit action if permission is granted
            $editAction = Auth::user()->can('Edit Source')
                ? '<a href="' . route('sources.edit', $source->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
                        <i class="fa fa-edit"></i>
                   </a>'
                : '';
            // Generate delete action if permission is granted
            $deleteAction = Auth::user()->can('Delete Source')
                ? '<button class="btn btn-warning btn-sm py-2 delete-source-btn" title="Delete" data-id="' . $source->id . '">
            <i class="fa fa-trash"></i>
       </button>'
                : '';

            // Combine actions
            $source->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $source;
        });
        if ($request->ajax()) {
            return response()->json([
                'data' => $sources,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('sources.list', compact('sources'));
    }
    public function show($id)
    {
        $source = Source::with('author')->findOrFail($id);
        return response()->json($source);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sources.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_name' => 'required|min:3',
            'source_url'  => 'required|url',
            'source_type' => 'required',
        ]);

        // Create the Source with validated data
        if ($validator->passes()) {
            Source::create([
                'source_name' => $request->input('source_name'),
                'source_url' => $request->input('source_url'),
                'source_type' => $request->input('source_type'),
                'author' => Auth::user()->id,
            ]);
            return redirect()->route('sources.index')->with('success', 'Source added successfully.');
        } else {
            return redirect()->route('sources.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $source = Source::findOrFail($id);
        return view('sources.edit', compact('source'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'source_name' => 'required|min:3',
            'source_url'  => 'required|url',
            'source_type' => 'required',
        ]);

        // If validation passes
        if ($validator->passes()) {
            // Find the existing Source
            $Source = Source::findOrFail($id);

            // Update the fields from the request
            $Source->source_name = $request->source_name;
            $Source->source_url  = $request->source_url;
            $Source->source_type = $request->source_type;

            // Save the updated Source
            $Source->save();

            // Redirect back with success message
            return redirect()->route('sources.index')->with('success', 'Source updated successfully.');
        } else {
            // Return to the edit form with validation errors
            return redirect()->route('sources.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $source = Source::findOrFail($id);
            $source->delete();

            return response()->json([
                'message' => 'Source deleted successfully.',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete source: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
