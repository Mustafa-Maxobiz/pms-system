<x-app-layout>
    <div id="error-page" class="my-4 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 table-heading">
                            <h6>Error {{ $code ?? '' }}</h6>
                        </div>
                        <p class="alert alert-danger m-5">
                            {{ $message ?? 'An unexpected error occurred.' }}
                            <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                <i class="fa fa-arrow-rotate-left"></i> Return to Home
                            </a>
                        </p>
                   </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
