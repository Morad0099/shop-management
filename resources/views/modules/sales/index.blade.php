@extends('layout.app')

@section('title', 'Sales')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="bg-light rounded p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6>All Sales</h6>
                    <a href="{{ route('sales.create') }}" class="btn btn-primary">Add Sale</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Attendant</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $sale)
                            <tr>
                                <td>{{ $sale->sale_date }}</td>
                                <td>{{ $sale->product->name ?? 'N/A'}}</td>
                                <td>{{ $sale->quantity ?? 'N/A'}}</td>
                                <td>GHS{{ number_format($sale->total_price, 2) }}</td>
                                <td>{{ $sale->attendant ?? 'N/A' }}</td>
                                <td>
                                    {{-- <a href="#" class="btn btn-sm btn-primary">Details</a> --}}
                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No sales found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
