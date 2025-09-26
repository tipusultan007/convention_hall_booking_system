@extends('layout.master')
@section('title', 'Edit Fund Record')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><h4>Edit Borrowed Fund Record</h4></div>
                <div class="card-body">
                    <form action="{{ route('borrowed-funds.update', $borrowedFund->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="lender_id" class="form-label">Lender/Source</label>
                            <select id="lender_id" name="lender_id" class="form-select" required>
                                @foreach($lenders as $lender)
                                    <option value="{{ $lender->id }}" {{ $borrowedFund->lender_id == $lender->id ? 'selected' : '' }}>{{ $lender->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount_borrowed" class="form-label">Amount Borrowed</label>
                            <input type="number" step="0.01" id="amount_borrowed" name="amount_borrowed" class="form-control" value="{{ old('amount_borrowed', $borrowedFund->amount_borrowed) }}" required>
                            <small class="form-text text-muted">Cannot be less than the amount already repaid (৳{{ $borrowedFund->amount_repaid }}).</small>
                        </div>
                        <div class="mb-3">
                            <label for="date_borrowed" class="form-label">Date</label>
                            <input type="text" id="date_borrowed" name="date_borrowed" class="form-control" value="{{ old('date_borrowed', $borrowedFund->date_borrowed->format('Y-m-d')) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="purpose" class="form-label">Purpose</label>
                            <input type="text" id="purpose" name="purpose" class="form-control" value="{{ old('purpose', $borrowedFund->purpose) }}" required>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('borrowed-funds.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        $(document).ready(function() {
            $('#lender_id').select2({ theme: "bootstrap-5" });
            flatpickr("#date_borrowed", { altInput: true, altFormat: "F j, Y", dateFormat: "Y-m-d" });
        });
    </script>
@endpush
