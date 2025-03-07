<!-- All Payments Content -->
<div id="all-payments" class="my-0 split">
    <div class="row">
        <div class="col-sm-12">
            <div class="card-header p-3 table-heading">
                <h6>
                    All Payments
                    @can('Add New Payment')
                        <a href="{{ route('projects.payments.create', ['project' => $project->id]) }}"
                            class="btn-link btn btn-dark py-2 float-end">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    @endcan
                </h6>
            </div>
            <div class="p-0">
                <div class="table-responsive">
                    <table class="table table-hover" id="paymentsTable" width="100%" cellspacing="0">
                        <thead class="table-head">
                            <tr class="table-light">
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Author</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Payment Modal -->
<div class="modal fade" id="viewPaymentModal" tabindex="-1" aria-labelledby="viewPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPaymentModalLabel">Payment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body payment-modal">
                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <!-- Payment Details -->
                <div id="paymentDetails" style="display: none;">
                    <div class="row">
                        <div class="col-md-6 col-12 mb-3">
                            <label for="paymentTitle" class="form-label">Title:</label>
                            <p id="paymentTitle"></p>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <label for="paymentDescription" class="form-label">Description:</label>
                            <p id="paymentDescription"></p>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <label for="paymentAuthor" class="form-label">Author:</label>
                            <p id="paymentAuthor"></p>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <label for="paymentCreated" class="form-label">Created At:</label>
                            <p id="paymentCreated"></p>
                        </div>

                        <!-- Additional Payment Details -->
                        <div class="col-md-6 col-12 mb-3">
                            <label for="paymentSubTask" class="form-label">Sub Tasks:</label>
                            <p id="paymentSubTask"></p>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <label for="paymentTotalTaskValue" class="form-label">Total Task Value:</label>
                            <p id="paymentTotalTaskValue"></p>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <label for="paymentDiscount" class="form-label">Discount:</label>
                            <p id="paymentDiscount"></p>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <label for="paymentGST" class="form-label">GST:</label>
                            <p id="paymentGST"></p>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <label for="paymentPaidAmount" class="form-label">Paid Amount:</label>
                            <p id="paymentPaidAmount"></p>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <label for="paymentRemainingAmount" class="form-label">Remaining Amount:</label>
                            <p id="paymentRemainingAmount"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
