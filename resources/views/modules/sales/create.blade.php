@extends('layout.app')

@section('title', 'Add Sale')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row">
        <div class="col-12">
            <div class="bg-light rounded p-4">
                <h6>Add Sale</h6>
                <form action="{{ route('sales.store') }}" method="POST">
                    @csrf
                    <div id="product-list">
                        <div class="product-item mb-3">
                            <div class="row g-3">
                                <div class="col-md-5">
                                    <label for="product_id" class="form-label">Product</label>
                                    <select name="products[0][product_id]" class="form-select product-select" required>
                                        <option value="">Select a Product</option>
                                        @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                            {{ $product->name }} (GHS{{ $product->price }})
                                        </option>
                                        @endforeach
                                    </select>
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

        // Add new product item
        $('#add-item').click(function () {
            let newItem = `
                <div class="product-item mb-3">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="product_id" class="form-label">Product</label>
                            <select name="products[${productIndex}][product_id]" class="form-select product-select" required>
                                <option value="">Select a Product</option>
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                    {{ $product->name }} (GHS {{ $product->price }})
                                </option>
                                @endforeach
                            </select>
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
            productIndex++;
        });

        // Remove product item
        $(document).on('click', '.remove-item', function () {
            $(this).closest('.product-item').remove();
        });

        // Update total price based on product and quantity
        $(document).on('change', '.product-select, .quantity-input', function () {
            const row = $(this).closest('.product-item');
            const selectedOption = row.find('.product-select option:selected');
            const price = parseFloat(selectedOption.attr('data-price')) || 0;
            const quantity = parseInt(row.find('.quantity-input').val()) || 0;
            const totalPrice = price * quantity;
            row.find('.total-price').val(totalPrice.toFixed(2));
        });
    });
</script>

@endsection
