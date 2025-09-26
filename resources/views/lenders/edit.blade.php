@extends('layout.master')
@section('title', 'Edit Lender')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h4>Edit Lender</h4></div>
                <div class="card-body">
                    <form action="{{ route('lenders.update', $lender->id) }}" method="POST">
                        @method('PUT')
                        @include('lenders._form', ['lender' => $lender, 'buttonText' => 'Update Lender'])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
