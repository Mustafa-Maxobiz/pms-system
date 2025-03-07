<x-app-layout>
    <!-- All departments Content -->
    <div id="all-departments" class="my-3 split">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">

                    @if (Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    @if (Session::has('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ Session::get('error') }}
                        </div>
                    @endif

                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header p-3 table-heading">
                            <h6>
                                My Report
                            </h6>
                        </div>
                        <div class="p-0">

                            {{-- For Daily Reports --}}
                            <div class="row p-3">
                                <div class="col-md-4 mt-2">
                                    <select class="form-select" name="search_filter" id="search_filter">
                                        <option value="" disabled selected>Select Type</option>
                                        <option value="daily">Daily</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mt-2">
                                    <input type="date" name="from_date" class="form-control">
                                </div>
                                <div class="col-md-4 mt-2">
                                    <input type="date" name="to_date" class="form-control">
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover" id="myReportTable" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th>Task ID</th>
                                            <th>Task Name</th>
                                            <th>Task Time</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4 mt-4">
                        <div class="card-header p-3 table-heading">
                            <h6>
                                My Progress
                            </h6>
                        </div>
                        <div class="p-0">

                            <div class="table-responsive">
                                <table class="table table-hover" width="100%" cellspacing="0">
                                    <thead class="table-head">
                                        <tr class="table-light">
                                            <th></th>
                                            <th>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><span>Task Target :</span>&nbsp;<strong id="targetValue"></strong></td>
                                            <td>
                                                <canvas id="myChart" width="150" height="150"></canvas>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    @section('scripts')
        <script>
            $('#myReportTable').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                stateSave: true,
                ajax: {
                    url: "{{ route('reports.see.my.report') }}",
                    type: 'GET',
                    data: function(d) {
                        d.search = $('input[type="search"]').val(); // ✅ Search Query Include
                        d.from_date = $('input[name="from_date"]').val(); // From Date Filter
                        d.to_date = $('input[name="to_date"]').val(); // To Date Filter
                        d.search_filter = $('#search_filter').val() || 'daily'; // ✅ Default "Daily"
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            alert('Your session has expired. Redirecting to the login page...');
                            window.location.href = "{{ route('login') }}";
                        } else {
                            console.error('DataTables AJAX error:', xhr.responseText);
                            alert('An error occurred while loading the data. Please try again.');
                        }
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'task_name',
                        name: 'task_name'
                    },
                    {
                        data: 'total_time',
                        name: 'task_time'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        render: function(data) {
                            return moment(data).format('D MMM, YYYY');
                        }
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                        render: function(data) {
                            return moment(data).format('D MMM, YYYY');
                        }
                    },
                ],
                order: [
                    [0, 'desc']
                ],
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10,
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'copy',
                        className: 'btn btn-primary'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-success'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-info'
                    }
                ]
            });

            $('#search_filter, input[name="from_date"], input[name="to_date"]').on('change', function() {
                $('#myReportTable').DataTable().ajax.reload();
            });


            $(document).ready(function() {
                seeMyReport();
            });


            function seeMyReport() {
                $.ajax({
                    url: '{{ route('reports.my.progress') }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log("Show My Report Data:", response);

                        // Call function to render Google Chart
                        drawChart(response.totalTaskValue, response.totalTargetAmount, response.percentage);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                    }
                });
            }

            // Load Google Charts
            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(seeMyReport);

            function drawChart(totalTaskValue, totalTargetAmount, percentage) {
                var canvas = document.getElementById('myChart');
                var ctx = canvas?.getContext('2d');

                if (!ctx) {
                    console.error("Canvas context not found for myChart!");
                    return;
                }

                var adjustedPercentage = Math.min(percentage, 200); // Limit to max 200%
                var maxValue = Math.max(adjustedPercentage, 100); // Ensure dynamic scaling

                var achieved = adjustedPercentage;
                var remaining = Math.max(maxValue - adjustedPercentage, 0);

                // Set Target Amount in HTML
                document.getElementById('targetValue').innerHTML = totalTargetAmount;

                // Destroy previous chart if exists (prevents duplication)
                // if (window.myChart) {
                //     window.myChart.destroy();
                // }

                // Create new Chart.js Donut Chart
                window.myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Achieved', 'Remaining'],
                        datasets: [{
                            data: [achieved, remaining],
                            backgroundColor: ['#4caf50', '#f44336']
                        }]
                    },
                    options: {
                        responsive: false,
                        maintainAspectRatio: false,
                        rotation: 0, // Start from default position
                        circumference: 360, // Full Circle Chart
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        let value = tooltipItem.raw.toFixed(1) + '%';
                                        return `${value}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endsection
</x-app-layout>
