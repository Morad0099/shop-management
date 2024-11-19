@extends('layout.app')

@section('title', 'Add Sale')

@section('content')
    <div class="container-fluid pt-4 px-4">
        @if ($errors->has('products'))
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->get('products') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="bg-light rounded p-4">
                    <h6>Add Sale</h6>
                    <form action="{{ route('sales.store') }}" method="POST">
                        @csrf
                        <div id="product-list">
                            <!-- Initial Product Item -->
                            <div class="product-item mb-3">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label for="product_id" class="form-label">Product</label>
                                        <div class="custom-select">
                                            <input type="text" class="custom-search" placeholder="Search for a Product">
                                            <ul class="custom-options">
                                                @foreach ($products as $product)
                                                    <li data-value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                        {{ $product->name }} (GHS {{ $product->price }})
                                                        @if ($product->size)
                                                            (Size: {{ $product->size }})
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <input type="hidden" name="products[0][product_id]" class="product-id" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" name="products[0][quantity]" class="form-control quantity-input" min="1" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="total_price" class="form-label">Total Price</label>
                                        <input type="text" class="form-control total-price" readonly>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger remove-item">-</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-success" id="add-item">Add Item</button>
                        </div>
                        <div class="mb-3">
                            <label for="sale_date" class="form-label">Sale Date</label>
                            <input type="date" name="sale_date" id="sale_date" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let productIndex = 1;

            // Function to initialize custom-select for search
            function initializeCustomSelect(selector) {
                $(selector).each(function () {
                    const $wrapper = $(this);
                    const $searchInput = $wrapper.find('.custom-search');
                    const $options = $wrapper.find('.custom-options li');
                    const $hiddenInput = $wrapper.find('.product-id');

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
                    $options.off('click').on('click', function () {
                        const value = $(this).data('value');
                        const text = $(this).text();
                        const price = $(this).data('price');

                        $hiddenInput.val(value);
                        $searchInput.val(text);
                        $wrapper.find('.custom-options').hide();

                        // Update the total price based on the selected product
                        const $row = $wrapper.closest('.product-item');
                        const quantity = parseInt($row.find('.quantity-input').val()) || 1;
                        const totalPrice = price * quantity;
                        $row.find('.total-price').val(totalPrice.toFixed(2));
                    });

                    // Hide options when clicking outside
                    $(document).on('click', function (e) {
                        if (!$.contains($wrapper[0], e.target)) {
                            $wrapper.find('.custom-options').hide();
                        }
                    });
                });
            }

            // Initialize the first custom-select
            initializeCustomSelect('.custom-select');

            // Add new product item dynamically
            $('#add-item').click(function () {
                let newItem = `
                    <div class="product-item mb-3">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="product_id" class="form-label">Product</label>
                                <div class="custom-select">
                                    <input type="text" class="custom-search" placeholder="Search for a Product">
                                    <ul class="custom-options">
                                        @foreach ($products as $product)
                                            <li data-value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                {{ $product->name }} (GHS {{ $product->price }})
                                                @if ($product->size)
                                                    (Size: {{ $product->size }})
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                    <input type="hidden" name="products[${productIndex}][product_id]" class="product-id" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="products[${productIndex}][quantity]" class="form-control quantity-input" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <label for="total_price" class="form-label">Total Price</label>
                                <input type="text" class="form-control total-price" readonly>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-item">-</button>
                            </div>
                        </div>
                    </div>`;

                $('#product-list').append(newItem);

                // Initialize the custom-select for the new item
                initializeCustomSelect(`#product-list .product-item:last .custom-select`);

                productIndex++;
            });

            // Remove product item
            $(document).on('click', '.remove-item', function () {
                $(this).closest('.product-item').remove();
            });

            // Update total price when quantity changes
            $(document).on('input', '.quantity-input', function () {
                const $row = $(this).closest('.product-item');
                const price = parseFloat($row.find('.custom-options li[data-value="' + $row.find('.product-id').val() + '"]').data('price')) || 0;
                const quantity = parseInt($(this).val()) || 0;
                const totalPrice = price * quantity;
                $row.find('.total-price').val(totalPrice.toFixed(2));
            });
        });
    </script>
@endsection
