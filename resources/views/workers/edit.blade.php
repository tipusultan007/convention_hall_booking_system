@extends('layout.master')
@section('title', 'Edit Worker')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4>Edit Worker Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('workers.update', $worker->id) }}" method="POST">
                    @method('PUT')
                    @include('workers._form', ['worker' => $worker, 'buttonText' => 'Update Worker'])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
