@extends('layout.master')
@section('title', 'Log New Expense')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h4>Log New Expense</h4></div>
            <div class="card-body">
                <form action="{{ route('expenses.store') }}" method="POST">
                    @include('expenses._form', ['buttonText' => 'Log Expense'])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
