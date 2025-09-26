@extends('layout.master')
@section('title', 'Manage Lenders')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h4>Add New Lender</h4></div>
                <div class="card-body">
                    <form action="{{ route('lenders.store') }}" method="POST">
                        @include('lenders._form', ['buttonText' => 'Add Lender'])
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>All Lenders</h4></div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead><tr><th>Name</th><th>Contact</th><th>Actions</th></tr></thead>
                        <tbody>
                        @forelse ($lenders as $lender)
                            <tr>
                                <td><strong>{{ $lender->name }}</strong><br><small class="text-muted-light">{{ $lender->notes }}</small></td>
                                <td>{{ $lender->contact_person }}<br><small class="text-muted-light">{{ $lender->phone }}</small></td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('lenders.edit', $lender->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>
                                        <form action="{{ route('lenders.destroy', $lender->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center">No lenders added yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
