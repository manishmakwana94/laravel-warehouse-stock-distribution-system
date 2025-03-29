@extends('layouts.app')

@section('content')
<div class="mt-4">
    <h2>Warehouse List</h2>
    <a href="{{ route('warehouse.warehouses.create') }}" class="btn btn-primary mb-3">Add New Warehouse</a>
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
                        <form action="{{ route('warehouse.warehouses.destroy', $warehouse->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
