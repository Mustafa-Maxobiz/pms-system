<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\KnowledgeBase;
use App\Models\Department;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class KnowledgeBaseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View KnowledgeBase', only: ['index']),
            new Middleware('permission:Edit KnowledgeBase', only: ['edit']),
            new Middleware('permission:Add New KnowledgeBase', only: ['create']),
            new Middleware('permission:Delete KnowledgeBase', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Initialize query with specific columns and required relationships
        $query = KnowledgeBase::select([
            'id',
            'title',
            'attachments',
            'department_id',
            'tags',
            'author'
        ])->with([
            'author' => function ($q) {
                $q->select('id', 'name');
            },
            'department' => function ($q) {
                $q->select('id', 'name');
            }
        ]);

        // Enhanced search filter across all requested fields
        if ($request->has('search') && is_string($request->search) && $request->search !== '') {
            $searchTerm = $request->search;

            $query->where(function ($q) use ($searchTerm) {
                $q->orWhere('id', 'like', '%' . $searchTerm . '%');
                $q->orWhere('title', 'like', '%' . $searchTerm . '%');
                $q->orWhere('attachments', 'like', '%' . $searchTerm . '%');
                $q->orWhere('tags', 'like', '%' . $searchTerm . '%');
                $q->orWhereHas('author', function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            });
        }

        // Count total and filtered records
        $recordsTotal = KnowledgeBase::count();
        $recordsFiltered = $query->count();

        // Sorting logic
        $columns = ['id', 'title', 'author', 'tags'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder = $request->input('order.0.dir', 'desc');

        // Fetch the data with pagination
        $knowledgeBases = $query->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get();

        // Transform the results to include action buttons
        $knowledgeBases->transform(function ($kb) {
            $viewAction = Auth::user()->can('View KnowledgeBase')
                ? '<a href="#" class="btn btn-info btn-sm py-2 view-knowledge-base-btn" data-id="' . $kb->id . '" title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>'
                : '';
            $editAction = Auth::user()->can('Edit KnowledgeBase')
                ? '<a href="' . route('knowledge-base.edit', $kb->id) . '" class="btn btn-success btn-sm py-2" title="Edit"><i class="fa fa-edit"></i></a>'
                : '';
            $deleteAction = Auth::user()->can('Delete KnowledgeBase')
                ? '<a href="#" class="btn btn-warning btn-sm py-2 delete-knowledge-base-btn" title="Delete" data-id="' . $kb->id . '"><i class="fa fa-trash"></i></a>'
                : '';

            $kb->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $kb;
        });

        if ($request->ajax()) {
            return response()->json([
                'data' => $knowledgeBases,
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }
        return view('knowledgeBases.list', compact('knowledgeBases'));
    }
    public function show($id)
    {
        $knowledgeBase = KnowledgeBase::with(['author', 'department'])->findOrFail($id);
        return response()->json($knowledgeBase);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all(); // Fetch all departments
        return view('knowledgeBases.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'tags' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:10240',  // Adjust file types and max size as needed
        ]);

        // If validation passes, proceed with storing data
        if ($validator->passes()) {
            // Handle file uploads
            $attachmentDetails = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $attachmentDetails[] = [
                        'path' => $file->store('knowledge-base-attachments', 'public'),
                        'original_name' => $file->getClientOriginalName(),
                    ];
                }
            }

            KnowledgeBase::create([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'department_id' => $request->input('department_id'),
                'tags' => explode(',', $request->input('tags')),
                'attachments' => json_encode($attachmentDetails),
                'author' => Auth::user()->id,
            ]);

            // Redirect with success message
            return redirect()->route('knowledge-base.index')->with('success', 'Knowledge Base entry added successfully.');
        } else {
            // Redirect with validation errors
            return redirect()->route('knowledge-base.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $knowledgeBase = KnowledgeBase::findOrFail($id);
        $departments = Department::all(); // Fetch all departments
        return view('knowledgeBases.edit', compact('knowledgeBase', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'tags' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,zip|max:10240',

        ]);

        if ($validator->passes()) {
            $knowledgeBase = KnowledgeBase::findOrFail($id);
            $existingAttachments = json_decode($knowledgeBase->attachments, true) ?? [];

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $existingAttachments[] = [
                        'path' => $file->store('knowledge-base-attachments', 'public'),
                        'original_name' => $file->getClientOriginalName(),
                    ];
                }
            }

            $knowledgeBase->update([
                'title' => $request->input('title'),
                'content' => $request->input('content'),
                'department_id' => $request->input('department_id'),
                'tags' => explode(',', $request->input('tags')),
                'attachments' => json_encode($existingAttachments),
            ]);
            return redirect()->route('knowledge-base.index')->with('success', 'Knowledge Base entry updated successfully.');
        } else {
            // Pass the $id when redirecting back to the edit route
            return redirect()->route('knowledge-base.edit', ['KnowledgeBase' => $id])->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $knowledgeBase = KnowledgeBase::findOrFail($id);
        $attachments = json_decode($knowledgeBase->attachments, true) ?? [];

        // Delete attachments
        foreach ($attachments as $attachment) {
            $filePath = $attachment['path'];
            if (\Storage::disk('public')->exists($filePath)) {
                \Storage::disk('public')->delete($filePath);
            }
        }

        if ($knowledgeBase->delete()) {
            return response()->json(['success' => true, 'message' => 'Knowledge Base entry deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to delete Knowledge Base entry.'], 500);
    }


    public function deleteAttachment(Request $request, $id)
    {
        $request->validate([
            'attachment_index' => 'required|integer',
        ]);

        $knowledgeBase = KnowledgeBase::findOrFail($id);
        $attachments = json_decode($knowledgeBase->attachments, true) ?? [];

        $attachmentIndex = $request->input('attachment_index');

        // Debug logs
        if (isset($attachments[$attachmentIndex])) {
            $filePath = $attachments[$attachmentIndex]['path'];

            // Delete file if it exists
            if (\Storage::disk('public')->exists($filePath)) {
                \Storage::disk('public')->delete($filePath);
            }

            unset($attachments[$attachmentIndex]);
            $knowledgeBase->attachments = json_encode(array_values($attachments));
            $knowledgeBase->save();

            return redirect()->back()->with('success', 'Attachment deleted successfully.');
        }

        return redirect()->back()->with('error', 'Attachment not found.');
    }
}
