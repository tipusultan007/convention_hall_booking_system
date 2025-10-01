@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name ?? '') }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email ?? '') }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" @if(!isset($user)) required @endif>
        @if(isset($user))<small class="form-text text-muted">Leave blank to keep current password.</small>@endif
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Assign Roles</label>
    <div class="row">
        @foreach($roles as $id => $name)
            <div class="col-md-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $id }}" id="role_{{ $id }}"
                        {{-- Check if the role is assigned to the user --}}
                        {{ in_array($id, old('roles', $userRoles ?? [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="role_{{ $id }}">
                        {{ ucfirst($name) }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
    @error('roles') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>

<div class="d-flex justify-content-end mt-4">
    <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">Cancel</a>
    <button type="submit" class="btn btn-primary">{{ $buttonText ?? 'Save User' }}</button>
</div>
