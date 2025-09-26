<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Momota Booking System')</title>

    {{-- CSS Assets (Now Local) --}}

    {{-- 1. Load the compiled Bootstrap theme from Vite --}}
    @vite(['resources/scss/app.scss'])

    {{-- 2. Load other libraries locally --}}
    <link rel="stylesheet" href="{{ asset('libs/flatpickr/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" /> {{-- This one is tricky to host locally, so we can keep it as a CDN for simplicity --}}
    <link rel="stylesheet" href="{{ asset('libs/fontawesome/css/all.min.css') }}">

</head>
<body class="bg-light">
    {{-- Your Navbar and Main Content Area (No changes needed here) --}}
    {{-- This is the full navbar code to be placed in your resources/views/layouts/app.blade.php file --}}

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ auth()->check() ? route('dashboard') : route('login') }}">
                Momota Community Center
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                {{-- Left side of Navbar (Main App Links) --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    {{-- These links will only be visible to logged-in users --}}
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bookings.index') }}">Bookings</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="expenseDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Expenses
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="expenseDropdown">
                                <li><a class="dropdown-item" href="{{ route('expenses.index') }}">All Expenses</a></li>
                                <li><a class="dropdown-item" href="{{ route('expense-categories.index') }}">Expense Categories</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="incomeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Income
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="incomeDropdown">
                                <li><a class="dropdown-item" href="{{ route('incomes.index') }}">Other Income</a></li>
                                <li><a class="dropdown-item" href="{{ route('income-categories.index') }}">Income Categories</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="salaryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Salaries
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="salaryDropdown">
                                <li><a class="dropdown-item" href="{{ route('workers.index') }}">Manage Workers</a></li>
                                <li><a class="dropdown-item" href="{{ route('salaries.index') }}">Manage Salaries</a></li>
                            </ul>
                        </li>
                        {{-- ***** NEW LIABILITIES DROPDOWN ***** --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="liabilityDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Liabilities
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="liabilityDropdown">
                                <li><a class="dropdown-item" href="{{ route('borrowed-funds.index') }}">Borrowed Funds</a></li>
                                <li><a class="dropdown-item" href="{{ route('lenders.index') }}">Manage Lenders</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reports.profit_loss') }}">Reports</a>
                        </li>
                    @endauth
                </ul>

                {{-- Right side of Navbar (Authentication Links) --}}
                <ul class="navbar-nav ms-auto">
                    {{-- Show these links if the user is a guest (not logged in) --}}
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @endif
                    @endguest

                    {{-- Show this dropdown if the user is logged in --}}
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#">Profile</a></li> {{-- Placeholder for future profile page --}}
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    {{-- Logout Form (Required by Breeze for security) --}}
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); this.closest('form').submit();">
                                            Logout
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <main class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
        @endif
        @yield('content')
    </main>

    {{-- JavaScript Assets (Now Local) --}}
    <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('libs/flatpickr/js/flatpickr.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('libs/fullcalendar/dist/index.global.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
