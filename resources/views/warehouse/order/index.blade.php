@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Warehouse') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Contact Number</th>
                                <th>Manager</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($warehouses as $warehouse)
                                <tr>
                                    <td>{{ $warehouse->name }}</td>
                                    <td>{{ $warehouse->location }}</td>
                                    <td>{{ $warehouse->contact_number }}</td>
                                    <td>{{ $warehouse->manager->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('warehouse.warehouses.show', $warehouse->id) }}" class="btn btn-info btn-sm">Show</a>
                                        <a href="{{ route('warehouse.warehouses.edit', $warehouse->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

