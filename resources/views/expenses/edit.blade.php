@extends('layout.master')
@section('title', 'Edit Expense')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h4>Edit Expense</h4></div>
            <div class="card-body">
                <form action="{{ route('expenses.update', $expense->id) }}" method="POST">
                    @method('PUT')
                    @include('expenses._form', ['buttonText' => 'Update Expense'])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
