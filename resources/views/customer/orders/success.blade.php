@extends('layouts.app')

@section('content')
<div class="container">
    <div class="alert alert-success">
        <h4>Order Placed Successfully!</h4>
        <p>Your order has been placed successfully. Order ID: <strong>{{ $order->id }}</strong></p>
        <a href="{{ route('customer.products.index') }}" class="btn btn-primary">Continue Shopping</a>
    </div>
</div>
@endsection
