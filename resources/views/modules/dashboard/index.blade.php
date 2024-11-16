@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <!-- Overview Stats -->
        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Today's Sales</p>
                        <h6 class="mb-0">GHS{{ number_format($todaySales, 2) }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Revenue</p>
                        <h6 class="mb-0">GHS{{ number_format($totalRevenue, 2) }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-box fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Products Sold</p>
                        <h6 class="mb-0">{{ $totalProductsSold }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-dollar-sign fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Average Sale Value</p>
                        <h6 class="mb-0">GHS{{ number_format($averageSaleValue, 2) }}</h6>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Sales Overview Chart -->
        <div class="row g-4 mt-4">
            <div class="col-lg-8">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex justify-content-between mb-4">
                        <h6>Sales Overview</h6>
                        <a href="#">View More</a>
                    </div>
                    <canvas id="sales-chart"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex justify-content-between mb-4">
                        <h6>Revenue by Product</h6>
                        <a href="#">View More</a>
                    </div>
                    <canvas id="revenue-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="row g-4 mt-4">
            <div class="col-12">
                <div class="bg-light rounded p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6>Recent Sales</h6>
                        <a href="#">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentSales as $sale)
                                    <tr>
                                        <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $sale->product->name }}</td>
                                        <td>{{ $sale->quantity }}</td>
                                        <td>${{ number_format($sale->total_price, 2) }}</td>
                                        <td><a class="btn btn-sm btn-primary" href="#">Details</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No recent sales.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            
                        </table>
                    </div>
                </div>


            </div>
            <div class="bg-light text-center rounded p-4">
                <h6>Recent Stock Changes</h6>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Change</th>
                                <th>New Stock</th>
                                <th>Reason</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentStockChanges as $change)
                                <tr>
                                    <td>{{ $change->product->name }}</td>
                                    <td>{{ $change->quantity_changed }}</td>
                                    <td>{{ $change->new_stock }}</td>
                                    <td>{{ $change->reason }}</td>
                                    <td>{{ $change->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No recent changes.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
