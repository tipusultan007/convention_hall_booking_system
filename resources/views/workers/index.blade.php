@extends('layout.master')
@section('title', 'Manage Workers')

@section('content')
<div class="row">
    {{-- Column for the Create Worker Form --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Add New Worker</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('workers.store') }}" method="POST">
                    @include('workers._form', ['buttonText' => 'Add Worker'])
                </form>
            </div>
        </div>
    </div>

    {{-- Column for the Worker List --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>All Workers</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Designation</th>
                                <th class="text-end">Salary</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($workers as $worker)
                            <tr>
                                <td>
                                    <strong>{{ $worker->name }}</strong><br>
                                    <small class="text-muted">{{ $worker->phone }}</small>
                                </td>
                                <td>{{ $worker->designation }}</td>
                                <td class="text-end">৳{{ number_format($worker->monthly_salary, 2) }}</td>
                                <td>
                                    @if($worker->is_active)
                                    <span class="badge bg-success">Active</span>
                                    @else
                                    <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        {{-- ADD THIS NEW BUTTON --}}
                                        <a href="{{ route('workers.show', $worker->id) }}" class="btn btn-sm btn-info me-2">View</a>

                                        {{-- Existing buttons --}}
                                        <a href="{{ route('workers.edit', $worker->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>
                                        <form action="{{ route('workers.destroy', $worker->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this worker?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No workers have been added yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
