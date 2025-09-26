@csrf
<div class="mb-3">
    <label for="income_category_id" class="form-label">Category</label>
    <select class="form-select @error('income_category_id') is-invalid @enderror" id="income_category_id" name="income_category_id" required>
        <option value="">Select a category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{-- Pre-select the correct category in the edit form --}}
                {{ (isset($income) && $income->income_category_id == $category->id) || old('income_category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('income_category_id')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="amount" class="form-label">Amount</label>
    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $income->amount ?? '') }}" required>
    @error('amount')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="income_date" class="form-label">Date</label>
    <input type="text" class="form-control @error('income_date') is-invalid @enderror" id="income_date" name="income_date" value="{{ old('income_date', $income->income_date ?? '') }}" required>
    @error('income_date')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Description (Optional)</label>
    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $income->description ?? '') }}</textarea>
</div>

<div class="d-flex justify-content-end">
    <a href="{{ route('incomes.index') }}" class="btn btn-secondary me-2">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $buttonText ?? 'Save' }}</button>
</div>

@push('custom-scripts')
    {{-- This script will now be used on both index and edit pages --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (jQuery().select2) {
                $('#income_category_id').select2({
                    theme: "bootstrap-5",
                });
            }
            if (typeof flatpickr !== 'undefined') {
                flatpickr("#income_date", {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    // Set the default date for the edit form
                    defaultDate: "{{ isset($income) ? $income->income_date->format('Y-m-d') : 'today' }}"
                });
            }
        });
    </script>
@endpush
