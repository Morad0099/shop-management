@extends('layout.app')

@section('title', 'Low Stock Management')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-light rounded p-4">
                <h6 class="mb-4 d-flex justify-content-between align-items-center">
                    Low Stock Products
                    <a href="{{ route('low-stock.export', ['type' => 'csv']) }}" class="btn btn-sm btn-success">Export CSV</a>
                    <a href="{{ route('low-stock.export', ['type' => 'pdf']) }}" class="btn btn-sm btn-primary">Export PDF</a>
                </h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Stock Level</th>
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lowStockProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>
                                        @if ($product->stock <= 3)
                                            <span class="badge bg-danger">Critical</span>
                                        @elseif ($product->stock <= 5)
                                            <span class="badge bg-warning">Low</span>
                                        @else
                                            <span class="badge bg-info">Moderate</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->updated_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-edit"></i> Update Stock
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No low-stock products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($lowStockProducts->isNotEmpty())
                    <div class="mt-3">
                        <span class="badge bg-danger">Critical:</span> Stock Less Than or Equal to 3 |
                        <span class="badge bg-warning">Low:</span> Stock Less Than or Equal to 5 |
                        <span class="badge bg-info">Moderate:</span> Stock Greater Than 5
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</div>
@endsection
