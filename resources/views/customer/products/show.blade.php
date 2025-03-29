@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
    <h2>{{ $product->name }}</h2>
    <p>{{ $product->description }}</p>
    <p><strong>SKU:</strong> {{ $product->sku }}</p>
    <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>

    <h4>Available Stock</h4>
    <ul>
        @foreach($product->warehouseStocks as $stock)
            <li>{{ $stock->warehouse->name }}: {{ $stock->quantity }} in stock</li>
        @endforeach
    </ul>
</div>
</div>
</div>
</div>
</div>
@endsection