@extends('layout.app')

@section('title', 'Reports')

@section('content')
<div class="container-fluid pt-4 px-4">
    <!-- Summary Cards -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-calendar-day fa-3x text-primary"></i>
                <div class="ms-3">
                    <h6>Daily Sales</h6>
                    <h3>GHS{{ number_format($dailySales, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-calendar-week fa-3x text-primary"></i>
                <div class="ms-3">
                    <h6>Weekly Sales</h6>
                    <h3>GHS{{ number_format($weeklySales, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                <i class="fa fa-calendar-alt fa-3x text-primary"></i>
                <div class="ms-3">
                    <h6>Monthly Sales</h6>
                    <h3>GHS{{ number_format($monthlySales, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="row g-4 mt-4">
        <div class="col-lg-8">
            <div class="bg-light rounded p-4">
                <div class="d-flex justify-content-between mb-4">
                    <h6>Sales Overview</h6>
                    <input type="text" class="form-control w-50" id="report-date-range" placeholder="Select Date Range">
                </div>
                <canvas id="sales-overview-chart"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="bg-light rounded p-4">
                <h6>Top Products</h6>
                <ul class="list-group">
                    @foreach ($topProducts as $product)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $product->product->name }}
                        <span class="badge bg-primary rounded-pill">{{ $product->total_quantity }} sold</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="bg-light rounded p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6>Detailed Product Performance</h6>
                    <div>
                        <a href="{{ route('reports.export', ['type' => 'pdf']) }}" class="btn btn-sm btn-primary">Export PDF</a>
                        <a href="{{ route('reports.export', ['type' => 'csv']) }}" class="btn btn-sm btn-success">Export CSV</a>
                    </div>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity Sold</th>
                            <th>Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detailedReports as $report)
                        <tr>
                            <td>{{ $report->product->name }}</td>
                            <td>{{ $report->total_quantity }}</td>
                            <td>GHS{{ number_format($report->total_revenue, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $detailedReports->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // Initialize Sales Overview Chart
    const salesOverviewCtx = document.getElementById('sales-overview-chart').getContext('2d');
    const salesOverviewChart = new Chart(salesOverviewCtx, {
        type: 'bar',
        data: {
            labels: @json($salesChartLabels),
            datasets: [{
                label: 'Sales (GHS)',
                data: @json($salesChartData),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
            },
            scales: {
                x: { beginAtZero: true },
                y: { beginAtZero: true }
            },
        }
    });

    // Date Range Picker Initialization
    $('#report-date-range').daterangepicker({
        opens: 'left'
    }, function (start, end) {
        // Reload data based on date range
        window.location.href = `/reports?start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`;
    });
</script>

@endsection