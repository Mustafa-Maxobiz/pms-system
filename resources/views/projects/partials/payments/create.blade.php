<x-app-layout>
    <div id="add-task" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Add New Payments</h6>
                        </div>
                        <form class="p-4" method="POST" action="{{ route('projects.payments.store', ['project' => $project->id]) }}">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="col-md-12 mb-3">
                                        <label for="project_id" class="form-label">Project ID</label>
                                        <input type="text" class="form-control @error('project_id') is-invalid @enderror" id="project_id" name="project_id" placeholder="Project ID:" value="{{ $project->id }}" readonly />
                                        @error('project_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Title:" value="{{ old('title') }}" />
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="summernote" id="description" name="description">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                <div class="col-md-12 mb-3">
                                    <label for="task_ids" class="form-label">Select Task/Subtask</label>
                                    <select class="form-control select2 form-select @error('task_ids') is-invalid @enderror"
                                            id="task_ids"
                                            name="task_ids[]"
                                            multiple>
                                        @foreach($tasks as $task)
                                            <option value="{{ $task->id }}"
                                                    {{ in_array($task->id, old('task_ids', [])) ? 'selected' : '' }}
                                                    data-task-value="{{ $task->task_value }}">
                                                {{ $task->task_name }}
                                            </option>
                                            @foreach($task->loadSubtasks as $subtask)
                                                <option value="{{ $subtask->id }}"
                                                        {{ in_array($subtask->id, old('task_ids', [])) ? 'selected' : '' }}
                                                        data-task-value="{{ $subtask->value }}">
                                                    -- {{ $subtask->name }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    @error('task_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>



                                    <div class="col-md-12 mb-3">
                                        <label for="selected_task_value" class="form-label">Total Task Value</label>
                                        <input type="text" class="form-control @error('selected_task_value') is-invalid @enderror" id="selected_task_value" name="selected_task_value" placeholder="Total Task Value" value="0" readonly />
                                        @error('selected_task_value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="discount" class="form-label">Discount</label>
                                        <input type="text" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" placeholder="Discount" value="0" />
                                        @error('discount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="gst" class="form-label">GST ({{ \App\Helpers\Helper::getSetting()->gst }}%)</label>
                                        <input type="text" class="form-control @error('gst') is-invalid @enderror" readonly id="gst" name="gst" placeholder="GST" value="0" />
                                        @error('gst')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="payed_amount" class="form-label">Paid Amount</label>
                                        <input type="text" class="form-control @error('payed_amount') is-invalid @enderror" id="payed_amount" name="payed_amount" placeholder="Paid Amount" value="0" />
                                        @error('payed_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="remaining_payment" class="form-label">Remaining Payment</label>
                                        <input type="text" class="form-control @error('remaining_payment') is-invalid @enderror" id="remaining_payment" name="remaining_payment" placeholder="Remaining Payment" value="0" readonly />
                                        @error('remaining_payment')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="d-flex">
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class=" @error('escrow_funded') is-invalid @enderror" id="escrow_funded" name="escrow_funded" value="1"
                                            {{ old('escrow_funded') ? 'checked' : '' }}>
                                            <label for="escrow_funded" class="form-check-label">Escrow Funded</label>
                                            @error('escrow_funded')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="@error('escrow_released') is-invalid @enderror" id="escrow_released" name="escrow_released" value="1"
                                            {{ old('escrow_released') ? 'checked' : '' }}>
                                            <label for="escrow_released" class="form-check-label">Escrow Released</label>
                                            @error('escrow_released')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="@error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" value="1"
                                            {{ old('payment_status') ? 'checked' : '' }}>
                                            <label for="payment_status" class="form-check-label">Payment Cleared</label>
                                            @error('payment_status')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-start">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                    <a href="{{ route('projects.details', $project->id) }}#related-payments" class="btn btn-warning">
                                        <i class="fa fa-arrow-rotate-left"></i> Back
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <link rel="stylesheet" href="{{ asset('public/richtexteditor/richtexteditor/rte_theme_default.css') }}" />
        <script type="text/javascript" src="{{ asset('public/richtexteditor/richtexteditor/rte.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/richtexteditor/richtexteditor/plugins/all_plugins.js') }}">
        </script>
    <script>
        $(document).ready(function () {
            var editor1 = new RichTextEditor("#description", {
                    editorResizeMode: "both",
                    width: "10%"
                });
            // Elements
            const selectedTaskValueInput = document.getElementById('selected_task_value');
            const discountInput = document.getElementById('discount');
            const gstInput = document.getElementById('gst');
            const payedAmountInput = document.getElementById('payed_amount');
            const remainingPaymentInput = document.getElementById('remaining_payment');

            // Recalculate on task selection
            $('#task_ids').on('change', function () {
                const selectedOptions = $(this).find(':selected');
                const totalValue = selectedOptions.toArray().reduce((sum, option) => {
                    const taskValue = parseFloat(option.getAttribute('data-task-value')) || 0;
                    return sum + taskValue;
                }, 0);

                selectedTaskValueInput.value = totalValue;

                // Trigger recalculations
                recalculate();
            });

            // Recalculate on discount or payed amount change
            $('#discount, #payed_amount').on('input', function () {
                recalculate();
            });

            // Recalculate all values
            function recalculate() {
                const taskValue = parseFloat(selectedTaskValueInput.value) || 0;
                const discount = parseFloat(discountInput.value) || 0;
                const payedAmount = parseFloat(payedAmountInput.value) || 0;
                const gstRate = parseFloat(@json(\App\Helpers\Helper::getSetting()->gst)) / 100;
                const gst = (taskValue - discount) * gstRate;
                gstInput.value = gst.toFixed(2);

                // Calculate Remaining Payment
                const totalAfterDiscountAndGST = taskValue - discount + gst;
                const remainingPayment = totalAfterDiscountAndGST - payedAmount;
                remainingPaymentInput.value = remainingPayment.toFixed(2);
            }
        });
    </script>
    @endsection
</x-app-layout>
