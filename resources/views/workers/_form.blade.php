@csrf
<div class="mb-3">
    <label for="name" class="form-label">Full Name</label>
    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $worker->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="phone" class="form-label">Phone Number (Optional)</label>
    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $worker->phone ?? '') }}">
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label for="designation" class="form-label">Designation (e.g., Manager, Guard)</label>
    <input type="text" id="designation" name="designation" class="form-control" value="{{ old('designation', $worker->designation ?? '') }}">
</div>
<div class="mb-3">
    <label for="monthly_salary" class="form-label">Monthly Salary (৳)</label>
    <input type="number" step="0.01" id="monthly_salary" name="monthly_salary" class="form-control @error('monthly_salary') is-invalid @enderror" value="{{ old('monthly_salary', $worker->monthly_salary ?? '') }}" required>
    @error('monthly_salary')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- This section only appears on the Edit form --}}
@if (isset($worker))
<div class="mb-3">
    <label for="is_active" class="form-label">Status</label>
    <select id="is_active" name="is_active" class="form-select" required>
        <option value="1" {{ old('is_active', $worker->is_active) == 1 ? 'selected' : '' }}>Active</option>
        <option value="0" {{ old('is_active', $worker->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
    </select>
    <small class="form-text text-muted">Inactive workers will not be included in new salary generations.</small>
</div>
@endif

<div class="d-flex justify-content-end">
    <a href="{{ route('workers.index') }}" class="btn btn-secondary me-2">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $buttonText ?? 'Save' }}</button>
</div>