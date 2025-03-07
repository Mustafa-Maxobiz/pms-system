<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Models\Source;

class ClientController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Clients', only: ['index']),
            new Middleware('permission:Edit Client', only: ['edit']),
            new Middleware('permission:Add New Client', only: ['create']),
            new Middleware('permission:Delete Client', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Initialize query for counting filtered records
        $filteredQuery = Client::with(['author:id,name', 'source'])
            ->select([
                'id',
                'client_name',
                'client_username',
                'source_id',
                'client_phone',
                'client_mobile',
                'client_email',
                'client_country',
                'author',
            ]);
        // Apply search filter if provided
        if ($request->has('search') && is_string($request->search) && $request->search !== '') {
            $filteredQuery->where(function ($query) use ($request) {
                $query->where('client_name', 'like', '%' . $request->search . '%')
                    ->orWhere('client_username', 'like', '%' . $request->search . '%')
                    ->orWhere('source_id', 'like', '%' . $request->search . '%')
                    ->orWhere('client_phone', 'like', '%' . $request->search . '%')
                    ->orWhere('client_mobile', 'like', '%' . $request->search . '%')
                    ->orWhere('client_email', 'like', '%' . $request->search . '%')
                    ->orWhere('client_country', 'like', '%' . $request->search . '%');
            });
            // Add search filter for 'author' name (or any other field on the author model)
            $filteredQuery->orWhereHas('author', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Total records count (without filtering)
        $recordsTotal = Client::count();

        // Filtered records count (with search applied)
        $recordsFiltered = $filteredQuery->count();

        // Apply sorting and pagination to the main query
        $columns = [
            'id',
            'client_name',
            'client_username',
            'source',
            'client_phone',
            'client_mobile',
            'client_email',
            'client_country',
            'created_at'
        ];
        $sortColumnIndex = $request->input('order.0.column', 0); // Default to the first column
        $sortColumn = $columns[$sortColumnIndex] ?? 'id'; // Ensure valid column name

        // Get the direction to sort
        $sortOrder = $request->input('order.0.dir', 'desc'); // Default to 'desc'

        // Apply sorting and pagination to the query
        $clientsQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        // Retrieve data for the table
        $clients = $clientsQuery->get();

        // Map Clients to include action column for DataTable
        $clients->transform(function ($client) {
            // Generate view action if permission is granted
            $viewAction = Auth::user()->can('View Clients')
                ? '<a href="#" class="btn btn-info btn-sm py-2" title="View" data-bs-toggle="modal" data-bs-target="#viewClientModal"
                data-id="' . $client->id . '">
                <i class="fa fa-eye" aria-hidden="true"></i>
            </a>'
                : '';
            // Generate edit action if permission is granted
            $editAction = Auth::user()->can('Edit Client')
                ? '<a href="' . route('clients.edit', $client->id) . '" class="btn btn-success btn-sm py-2" title="Edit">
                        <i class="fa fa-edit" aria-hidden="true"></i>
                   </a>'
                : '';
            // Generate delete action if permission is granted
            $deleteAction = Auth::user()->can('Delete Client')
                ? '<button class="btn btn-warning btn-sm py-2 delete-client-btn" title="Delete" data-id="' . $client->id . '">
                    <i class="fa fa-trash" aria-hidden="true"></i>
               </button>'
                : '';

            // Combine actions
            $client->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $client;
        });

        // Return JSON response for DataTables
        if ($request->ajax()) {
            return response()->json([
                'data' => $clients,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        // Otherwise, return the full view
        return view('clients.list', compact('clients'));
    }
    public function show($id)
    {
        $client = Client::with('source', 'author')->findOrFail($id);
        return response()->json($client);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sources = Source::all();
        return view('clients.create', compact('sources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|string|min:3',
            'client_username' => 'required|string|unique:clients,client_username',
            'source_id' => 'nullable',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|digits_between:7,15',
            'client_mobile' => 'nullable|digits_between:7,15',
            'address' => 'nullable|string|max:255',
            'meeting' => 'nullable|string|max:255',
            'client_country' => 'nullable|string|max:255',
        ]);

        if ($validator->passes()) {
            Client::create([
                'client_name' => $request->client_name,
                'client_username' => $request->client_username,
                'source_id' => $request->source_id,
                'client_phone' => $request->client_phone,
                'client_mobile' => $request->client_mobile,
                'client_email' => $request->client_email,
                'meeting' => $request->meeting,
                'client_country' => $request->client_country,
                'address' => $request->address,
                'author' => Auth::user()->id,
            ]);

            return redirect()->route('clients.index')->with('success', 'Client added successfully.');
        } else {
            return redirect()->route('clients.create')->withInput()->withErrors($validator);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $client  = Client::findOrFail($id);
        $sources = Source::all();
        return view('clients.edit', compact('client', 'sources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'client_name' => 'required|min:3',
            'client_username' => 'required|unique:clients,client_username,' . $id,
            'source_id' => 'nullable',
            'client_email' => 'nullable|email',
            'client_phone' => 'nullable|digits_between:7,15',
            'client_mobile' => 'nullable|digits_between:7,15',
            'address' => 'nullable|string|max:255',
            'meeting' => 'nullable|string|max:255',
            'client_country' => 'nullable|string|max:255',
        ]);

        if ($validator->passes()) {
            $client = Client::findOrFail($id);

            // Update fields
            $client->client_name = $request->client_name;
            $client->client_username = $request->client_username;
            $client->source_id = $request->source_id;
            $client->client_phone = $request->client_phone;
            $client->client_mobile = $request->client_mobile;
            $client->client_email = $request->client_email;
            $client->meeting = $request->meeting;
            $client->client_country = $request->client_country;
            $client->address = $request->address;

            $client->save();

            return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
        } else {
            return redirect()->route('clients.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();

            return response()->json([
                'message' => 'Client deleted successfully.',
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete client: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
}
