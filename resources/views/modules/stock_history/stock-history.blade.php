@extends('layout.app')

@section('title', 'Stock History')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        <h6>Stock History for {{ $product->name }}</h6>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Change</th>
                    <th>New Stock</th>
                    <th>Reason</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stockHistory as $history)
                    <tr>
                        <td>{{ $history->quantity_changed }}</td>
                        <td>{{ $history->new_stock }}</td>
                        <td>{{ $history->reason }}</td>
                        <td>{{ $history->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No stock history found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $stockHistory->links() }}
    </div>
</div>
@endsection
