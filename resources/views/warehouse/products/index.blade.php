@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Product List') }}</div>

                <div class="card-body">
                    <a href="{{ route('warehouse.products.create') }}" class="btn btn-primary mb-3">Add Product</a>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($products->isNotEmpty())
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Warehouse</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    @foreach($product->warehouseStocks as $stock)
                                        <tr>
                                            <td>{{ $product->sku }}</td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->description }}</td>
                                            <td>${{ number_format($product->price, 2) }}</td>
                                            <td>{{ optional($stock->warehouse)->name ?? 'N/A' }}</td>
                                            <td>{{ $stock->quantity }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center text-muted">No products found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
