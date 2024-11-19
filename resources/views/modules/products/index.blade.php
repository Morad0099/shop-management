@extends('layout.app')

@section('title', 'Products')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h6>Product List</h6>
                <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">Add Product</a>
            </div>

            <!-- Filter Form -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control" placeholder="Search by Name"
                            value="{{ request('name') }}">
                    </div>
                    <div class="col-md-4">
                        <!-- Custom Select for Category -->
                        <div class="custom-select">
                            <input type="text" class="custom-search" placeholder="Search for a Category">
                            <ul class="custom-options">
                                <li data-value="">All Categories</li>
                                @foreach ($categories as $category)
                                    <li data-value="{{ $category }}" 
                                        class="{{ request('category') == $category ? 'selected' : '' }}">
                                        {{ $category }}
                                    </li>
                                @endforeach
                            </ul>
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Size</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    @if ($product->image_url)
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" style="width: 100px; height: auto;">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category }}</td>
                                <td>GHS{{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->size ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('products.edit', $product->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <a href="{{ route('products.stock-history', $product->id) }}"
                                        class="btn btn-sm btn-info">View Stock History</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3 d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            function initializeCustomSelect() {
                $('.custom-select').each(function () {
                    const $wrapper = $(this);
                    const $searchInput = $wrapper.find('.custom-search');
                    const $options = $wrapper.find('.custom-options li');
                    const $hiddenInput = $wrapper.find('input[type="hidden"]');

                    // Show options on focus
                    $searchInput.on('focus', function () {
                        $wrapper.find('.custom-options').show();
                    });

                    // Filter options on input
                    $searchInput.on('input', function () {
                        const query = $(this).val().toLowerCase();
                        $options.each(function () {
                            const text = $(this).text().toLowerCase();
                            $(this).toggle(text.includes(query));
                        });
                    });

                    // Handle option selection
                    $options.on('click', function () {
                        const value = $(this).data('value');
                        const text = $(this).text();

                        $hiddenInput.val(value);
                        $searchInput.val(text);
                        $wrapper.find('.custom-options').hide();
                    });

                    // Hide options when clicking outside
                    $(document).on('click', function (e) {
                        if (!$.contains($wrapper[0], e.target)) {
                            $wrapper.find('.custom-options').hide();
                        }
                    });
                });
            }

            // Initialize the custom-select
            initializeCustomSelect();
        });
    </script>
@endsection
