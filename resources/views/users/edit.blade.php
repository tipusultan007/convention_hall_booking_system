@extends('layout.master')
@section('title', 'Edit User')

@section('content')
    <div class="card">
        <div class="card-header"><h4>Edit User: {{ $user->name }}</h4></div>
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @method('PUT')
                @include('users._form', ['buttonText' => 'Update User'])
            </form>
        </div>
    </div>
@endsection
