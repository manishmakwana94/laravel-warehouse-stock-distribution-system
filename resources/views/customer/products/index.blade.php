@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Available Products</h2>

    @forelse($products->chunk(3) as $productChunk)
        <div class="row">
            @foreach($productChunk as $product)
                @php 
                    $totalStock = $product->warehouseStocks->sum('quantity');
                @endphp
                @if($totalStock > 0)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ $product->description }}</p>
                                <p><strong>SKU:</strong> {{ $product->sku }}</p>
                                <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                                <p><strong>Stock:</strong> {{ $totalStock }}</p>
                                
                                <!-- Buy Now Form -->
                                <form action="{{ route('customer.orders.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="customer_id" value="{{ auth()->id() }}">
                                    <input type="hidden" name="items[0][product_id]" value="{{ $product->id }}">
                                    <input type="number" name="items[0][quantity]" min="1" max="{{ $totalStock }}" value="1" required class="form-control mb-2">
                                    <a href="{{ route('customer.products.show', $product->id) }}" class="btn btn-primary">View</a>
                                    <button type="submit" class="btn btn-success">Buy Now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @empty
        <p class="text-center">No products available.</p>
    @endforelse
</div>
@endsection
