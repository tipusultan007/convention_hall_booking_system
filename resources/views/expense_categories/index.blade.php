@extends('layout.master')

@section('title', 'Expense Categories')

@section('content')
<div class="row">
    {{-- Form to Create New Category --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Add New Category</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('expense-categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Save Category</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Table of Existing Categories --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>All Expense Categories</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('expense-categories.edit', $category->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>
                                        <form action="{{ route('expense-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
