@extends('layout.app')

@section('title', 'Reports')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="bg-light rounded p-4">
                <h6>Daily Sales</h6>
                <h3>${{ number_format($dailySales, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-light rounded p-4">
                <h6>Weekly Sales</h6>
                <h3>${{ number_format($weeklySales, 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="bg-light rounded p-4">
                <h6>Monthly Sales</h6>
                <h3>${{ number_format($monthlySales, 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-12">
            <div class="bg-light rounded p-4">
                <h6>Top Products</h6>
                <ul>
                    @foreach ($topProducts as $product)
                    <li>{{ $product->product->name }}: {{ $product->total_quantity }} sold</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
