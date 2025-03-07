<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:View Payments', only: ['index']),
            new Middleware('permission:Edit Payment', only: ['edit']),
            new Middleware('permission:Add New Payment', only: ['create']),
            new Middleware('permission:Delete Payment', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $project)
    {
        $filteredQuery = Payment::select([
            'id',   
            'title',      
            'description', 
            'author',   
            'project_id'   
        ])->with([
            'author' => function ($q) {
                $q->select('id', 'name'); 
            }
        ])->where('project_id', $project);

        if (!empty($request->search)) {
            $filteredQuery->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhereHas('author', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }


        $recordsTotal    = Payment::where('project_id', $project)->count();
        $recordsFiltered = $filteredQuery->count();

        $columns         = ['id', 'title', 'description', 'author', 'created_at'];
        $sortColumnIndex = $request->input('order.0.column', 0);
        $sortColumn      = $columns[$sortColumnIndex] ?? 'id';
        $sortOrder       = $request->input('order.0.dir', 'desc');

        $paymentQuery = $filteredQuery->orderBy($sortColumn, $sortOrder)
            ->skip($request->input('start', 0))
            ->take($request->input('length', 10));

        $payments = $paymentQuery->get();

        // Map Payments to include action column for DataTable
        $payments->transform(function ($payment) use ($project) {
            // Generate view action if permission is granted
            $viewAction = Auth::user()->can('View Payments')
                ? '<a href="#" class="btn btn-info btn-sm py-2 view-payment-btn" title="View" data-id="' . $payment->id . '" data-bs-toggle="modal" data-bs-target="#viewPaymentModal">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                </a>'
                : '';
            // Generate edit action if permission is granted
            $editAction = Auth::user()->can('Edit Payment')
                ? '<a href="' . route('projects.payments.edit', ['project' => $project, 'payment' => $payment->id]) . '" class="btn btn-success btn-sm py-2" title="Edit">
                    <i class="fa fa-edit"></i>
                </a>'
                : '';
            // Generate delete action if permission is granted
            $deleteAction = Auth::user()->can('Delete Payment')
                ? '<a href="#" class="btn btn-warning btn-sm py-2 delete-payment-btn" title="Delete" data-id="' . $payment->id . '">
                <i class="fa fa-trash"></i>
              </a>'
                : '';

            // Combine actions
            $payment->action = '<div class="btn-group" role="group" aria-label="Btn Group">' . $viewAction . ' ' . $editAction . ' ' . $deleteAction . '</div>';
            return $payment;
        });

        if ($request->ajax()) {
            return response()->json([
                'data'            => $payments,
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
            ]);
        }

        return view('payments.list', compact('payments'));
    }

    public function show($projectId, $paymentId)
    {
        $payment = Payment::where('project_id', $projectId)
            ->where('id', $paymentId)
            ->with('author')
            ->first();

        if ($payment) {
            return response()->json($payment);
        } else {
            return response()->json(['error' => 'Payment not found'], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        $tasks   = Task::with('loadSubtasks')->where('project_id', $projectId)->get();
        return view('projects.partials.payments.create', compact('project', 'tasks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $project)
    {
        $validator = Validator::make($request->all(), [
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string',
            'task_ids'            => 'required',
            'selected_task_value' => 'nullable|numeric',
            'discount'            => 'nullable|numeric',
            'gst'                 => 'nullable|numeric',
            'payed_amount'        => 'nullable|numeric',
            'remaining_payment'   => 'nullable|numeric',
        ]);

        // Create the Source with validated data
        if ($validator->passes()) {
            payment::create([
                'project_id'          => $request->input('project_id'),
                'title'               => $request->input('title'),
                'description'         => $request->input('description'),
                'task_ids'            => implode(',', $request->input('task_ids')),
                'selected_task_value' => $request->input('selected_task_value'),
                'discount'            => $request->input('discount'),
                'gst'                 => $request->input('gst'),
                'payed_amount'        => $request->input('payed_amount'),
                'remaining_payment'   => $request->input('remaining_payment'),
                'escrow_funded'       => $request->input('escrow_funded') ? $request->input('escrow_funded') : 0,
                'escrow_released'     => $request->input('escrow_released') ? $request->input('escrow_released') : 0,
                'payment_status'      => $request->input('payment_status') ? $request->input('payment_status') : 0,
                'author'              => Auth::user()->id,
            ]);
            return redirect()->to(route('projects.details', $project) . '#related-payments')->with('success', 'Payment added successfully.');
        } else {
            return redirect()->route('projects.payments.create', ['project' => $project])->withInput()->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($projectId, $paymentId)
    {
        $project = Project::findOrFail($projectId);
        $payment = Payment::findOrFail($paymentId);
        $tasks   = Task::with('loadSubtasks')->where('project_id', $projectId)->get();
        // Return the edit view with the necessary data
        return view('projects.partials.payments.edit', compact('project', 'payment', 'tasks'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $project, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string',
            'task_ids'            => 'nullable',
            'selected_task_value' => 'nullable|numeric',
            'discount'            => 'nullable|numeric',
            'gst'                 => 'nullable|numeric',
            'payed_amount'        => 'nullable|numeric',
            'remaining_payment'   => 'nullable|numeric',
        ]);

        // If validation passes
        if ($validator->passes()) {
            // Find the existing payment
            $payment = Payment::findOrFail($id);
            // Update the fields from the request
            $payment->title               = $request->title;
            $payment->description         = $request->description;
            $payment->task_ids            = implode(',', $request->task_ids);
            $payment->selected_task_value = $request->selected_task_value;
            $payment->discount            = $request->discount;
            $payment->gst                 = $request->gst;
            $payment->payed_amount        = $request->payed_amount;
            $payment->remaining_payment   = $request->remaining_payment;
            $payment->escrow_funded       = isset($request->escrow_funded) ? $request->escrow_funded : 0;
            $payment->escrow_released     = isset($request->escrow_released) ? $request->escrow_released : 0;
            $payment->payment_status      = isset($request->payment_status) ? $request->payment_status : 0;
            // Save the updated payment
            $payment->save();

            // Redirect back with success message
            return redirect()->to(route('projects.details', $project) . '#related-payments')->with('success', 'Payment updated successfully.');
        } else {
            // Return to the edit form with validation errors
            return redirect()->route('projects.payments.edit', ['project' => $project, 'id' => $id])->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($project, $id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->delete()) {
            return response()->json(['success' => true, 'message' => 'Payment deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to delete payment.'], 500);
    }
}
