@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4">Warehouse User Login</h2>
        <form method="POST" action="{{ route('warehouse.login.submit') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    name="password" 
                    id="password" 
                    required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <hr>
        <p>
            Don't have an account? <a href="{{ route('warehouse.register') }}">Register Here</a>
        </p>
    </div>
</div>
@endsection
