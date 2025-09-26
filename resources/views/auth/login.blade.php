@extends('layout.master2')

@section('content')
    <div class="row w-100 mx-0 auth-page">
        <div class="col-md-8 col-xl-6 mx-auto">
            <div class="card">
                <div class="row">
                    <div class="col-md-4 pe-md-0">
                        {{-- This side wrapper will use your local background image --}}
                        <div class="auth-side-wrapper" style="background-image: url({{ asset('images/auth-bg.jpg') }})">

                        </div>
                    </div>
                    <div class="col-md-8 ps-md-0">
                        <div class="auth-form-wrapper px-4 py-5">
                            <a href="{{ url('/') }}" class="nobleui-logo d-block mb-2">Momota<span>Hall</span></a>
                            <h5 class="text-secondary fw-normal mb-4">Welcome! Please sign in to continue.</h5>

                            {{-- This form is now fully integrated with Laravel Breeze --}}
                            <form class="forms-sample" method="POST" action="{{ route('login') }}">
                                @csrf

                                {{-- Displaying Login Error Messages --}}
                                @error('email')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                                @enderror

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" placeholder="Password" required>
                                </div>
                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                    <label class="form-check-label" for="remember_me">
                                        Remember me
                                    </label>
                                </div>
                                <div>
                                    {{-- The login button is now a proper submit button --}}
                                    <button type="submit" class="btn btn-primary w-100">Log In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
