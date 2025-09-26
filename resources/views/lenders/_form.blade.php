@csrf
<div class="mb-3">
    <label for="name" class="form-label">Lender Name/Source</label>
    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $lender->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="contact_person" class="form-label">Contact Person (Optional)</label>
    <input type="text" id="contact_person" name="contact_person" class="form-control" value="{{ old('contact_person', $lender->contact_person ?? '') }}">
</div>
<div class="mb-3">
    <label for="phone" class="form-label">Phone (Optional)</label>
    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $lender->phone ?? '') }}">
</div>
<div class="mb-3">
    <label for="notes" class="form-label">Notes (Optional)</label>
    <textarea id="notes" name="notes" class="form-control" rows="3">{{ old('notes', $lender->notes ?? '') }}</textarea>
</div>
<div class="d-flex justify-content-end">
    <a href="{{ route('lenders.index') }}" class="btn btn-secondary me-2">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $buttonText ?? 'Save' }}</button>
</div>
