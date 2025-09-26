@csrf
<div class="row">
    <div class="col-md-12 mb-3"> {{-- Changed to full width for better mobile view --}}
        <label for="expense_category_id" class="form-label">Category</label>
        <select class="form-select @error('expense_category_id') is-invalid @enderror" id="expense_category_id" name="expense_category_id" required>
            <option value="">Select a category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ (isset($expense) && $expense->expense_category_id == $category->id) || old('expense_category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('expense_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-12 mb-3"> {{-- Changed to full width --}}
        <label for="amount" class="form-label">Amount</label>
        <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $expense->amount ?? '') }}" required>
        @error('amount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-3"> {{-- Changed to full width --}}
        <label for="expense_date" class="form-label">Date</label>
        <input type="text" class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" name="expense_date" value="{{ old('expense_date', $expense->expense_date ?? '') }}" required>
        @error('expense_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Description (Optional)</label>
    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $expense->description ?? '') }}</textarea>
</div>
<div class="text-end">
    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $buttonText ?? 'Save Expense' }}</button>
</div>

@push('custom-scripts')
<script>
    // This script will only be pushed once even if the partial is used multiple times
    if (typeof flatpickr !== 'undefined') {
        flatpickr("#expense_date", {
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
            defaultDate: "{{ isset($expense) ? $expense->expense_date : now()->toDateString() }}"
        });
    }

    if (jQuery().select2) {
         $('#expense_category_id').select2({
            theme: "bootstrap-5",
            dropdownParent: $('#expense_category_id').parent() // Important for modals if used later
        });
    }
</script>
@endpush
