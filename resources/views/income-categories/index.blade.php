@extends('layout.master')
@section('title', 'Income Categories')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h4>Add New Category</h4></div>
                <div class="card-body">
                    <form action="{{ route('income-categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Category</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>All Income Categories</h4></div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead><tr><th>Name</th><th>Actions</th></tr></thead>
                        <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('income-categories.edit', $category->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>
                                        <form action="{{ route('income-categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center">No categories found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
