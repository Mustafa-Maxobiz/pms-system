<x-app-layout>
    <div class="container-fluid my-3">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5>Log Details</h5>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="status" class="mb-1">All Status</label>
                        <select id="filter-status" class="form-control select2">
                            <option value="">All Statuses</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}"
                                    {{ request('status_id') == $status->id ? 'selected' : '' }}>
                                    {{ $status->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Start Date -->
                    <div class="col-md-4">
                        <label for="date" class="mb-1">Date From</label>
                        <input type="date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <!-- End Date -->
                    <div class="col-md-4">
                        <label for="date" class="mb-1">Date To</label>
                        <input type="date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>

                <!-- Logs Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Status</th>
                                <th>Logged At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allLogs as $userLog)
                                <tr>
                                    <td>{{ $userLog->user->name ?? 'N/A' }}</td>
                                    <td>{{ $userLog->userStatus->title ?? 'N/A' }}</td>
                                    <td>{{ $userLog->created_at->format('D, M d, Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            $(document).ready(function() {
                $('#filter-status').change(function() {
                    const status_id = $(this).val();
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('status_id', status_id);
                    window.location.href = currentUrl.toString();
                });

                $('#start_date, #end_date').change(function() {
                    const start_date = $('#start_date').val();
                    const end_date = $('#end_date').val();
                    const currentUrl = new URL(window.location.href);

                    if (start_date) {
                        currentUrl.searchParams.set('start_date', start_date);
                    } else {
                        currentUrl.searchParams.delete('start_date');
                    }

                    if (end_date) {
                        currentUrl.searchParams.set('end_date', end_date);
                    } else {
                        currentUrl.searchParams.delete('end_date');
                    }
                    window.location.href = currentUrl.toString();
                });
            });
        </script>
    @endsection
</x-app-layout>
