@extends('layout.master')
@section('title', 'Add New User')

@section('content')
    <div class="card">
        <div class="card-header"><h4>Add New User</h4></div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @include('users._form', ['buttonText' => 'Create User'])
            </form>
        </div>
    </div>
@endsection
