@extends('layout.master')
@section('title', 'Edit Income Record')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Income Record</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('incomes.update', $income->id) }}" method="POST">
                        @method('PUT')
                        {{-- Include the shared form --}}
                        @include('incomes._form', [
                            'income' => $income,
                            'categories' => $categories,
                            'buttonText' => 'Update Income'
                        ])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
