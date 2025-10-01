@extends('layout.master')
@section('title', 'Manage Users')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>All Users</h4>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Add New User</a>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->getRoleNames() as $role)
                                <span class="badge bg-primary">{{ ucfirst($role) }}</span>
                            @endforeach
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning me-2">Edit</a>
                                @if(auth()->id() !== $user->id) {{-- Prevent delete button for self --}}
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No users found.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
