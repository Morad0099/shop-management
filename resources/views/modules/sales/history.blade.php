@extends('layout.app')

@section('title', 'Sales History')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="bg-light rounded p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6>Sales History</h6>
                    <form method="GET" class="d-flex">
                        <input type="date" name="date" class="form-control me-2" value="{{ request('date') }}">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Attendant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $sale)
                                <tr>
                                    <td>{{ $sale->sale_date }}</td>
                                    <td>
                                        @if ($sale->product)
                                            {{ $sale->product->name }}
                                            @if ($sale->product->size)
                                                (Size: {{ $sale->product->size }})
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>                                    <td>{{ $sale->quantity ?? 'N/A' }}</td>
                                    <td>GHS{{ number_format($sale->total_price, 2) }}</td>
                                    <td>{{ $sale->attendant ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No sales found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex justify-content-center">
                    {{ $sales->links('pagination::bootstrap-4') }}
                </div>
                {{-- <div class="mt-3 d-flex justify-content-center">
                    {{ $sales->links() }}
                </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection
