<nav class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            Momota<span>Hall</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav" id="sidebarNav">

            {{-- Main Dashboard Link --}}
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item {{ active_class(['dashboard']) }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="link-icon" data-lucide="home"></i>
                    <span class="link-title">Dashboard</span>
                </a>
            </li>

            {{-- Application Modules --}}
            <li class="nav-item nav-category">Management</li>

            {{-- Bookings Module --}}
            <li class="nav-item {{ active_class(['bookings', 'bookings/*']) }}">
                <a href="{{ route('bookings.index') }}" class="nav-link">
                    <i class="link-icon" data-lucide="calendar-check"></i>
                    <span class="link-title">Bookings</span>
                </a>
            </li>

            {{-- Income Module --}}
            <li class="nav-item {{ active_class(['incomes', 'incomes/*', 'income-categories', 'income-categories/*']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#income" role="button" aria-expanded="{{ is_active_route(['incomes', 'incomes/*', 'income-categories', 'income-categories/*']) }}" aria-controls="income">
                    <i class="link-icon" data-lucide="trending-up"></i>
                    <span class="link-title">Income</span>
                    <i class="link-arrow" data-lucide="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['incomes', 'incomes/*', 'income-categories', 'income-categories/*']) }}" data-bs-parent="#sidebarNav" id="income">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('incomes.index') }}" class="nav-link {{ active_class(['incomes', 'incomes/*']) }}">Other Income</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('income-categories.index') }}" class="nav-link {{ active_class(['income-categories', 'income-categories/*']) }}">Income Categories</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Expense Module --}}
            <li class="nav-item {{ active_class(['expenses', 'expenses/*', 'expense-categories', 'expense-categories/*']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#expenses" role="button" aria-expanded="{{ is_active_route(['expenses', 'expenses/*', 'expense-categories', 'expense-categories/*']) }}" aria-controls="expenses">
                    <i class="link-icon" data-lucide="trending-down"></i>
                    <span class="link-title">Expenses</span>
                    <i class="link-arrow" data-lucide="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['expenses', 'expenses/*', 'expense-categories', 'expense-categories/*']) }}" data-bs-parent="#sidebarNav" id="expenses">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('expenses.index') }}" class="nav-link {{ active_class(['expenses', 'expenses/*']) }}">All Expenses</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('expense-categories.index') }}" class="nav-link {{ active_class(['expense-categories', 'expense-categories/*']) }}">Expense Categories</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Salary Module --}}
            <li class="nav-item {{ active_class(['workers', 'workers/*', 'salaries', 'salaries/*']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#salaries" role="button" aria-expanded="{{ is_active_route(['workers', 'workers/*', 'salaries', 'salaries/*']) }}" aria-controls="salaries">
                    <i class="link-icon" data-lucide="users"></i>
                    <span class="link-title">HR & Salaries</span>
                    <i class="link-arrow" data-lucide="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['workers', 'workers/*', 'salaries', 'salaries/*']) }}" data-bs-parent="#sidebarNav" id="salaries">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('workers.index') }}" class="nav-link {{ active_class(['workers', 'workers/*']) }}">Manage Workers</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('salaries.index') }}" class="nav-link {{ active_class(['salaries', 'salaries/*']) }}">Manage Salaries</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Liability Module --}}
            <li class="nav-item {{ active_class(['borrowed-funds', 'borrowed-funds/*', 'lenders', 'lenders/*']) }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#liabilities" role="button" aria-expanded="{{ is_active_route(['borrowed-funds', 'borrowed-funds/*', 'lenders', 'lenders/*']) }}" aria-controls="liabilities">
                    <i class="link-icon" data-lucide="landmark"></i>
                    <span class="link-title">Liabilities</span>
                    <i class="link-arrow" data-lucide="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['borrowed-funds', 'borrowed-funds/*', 'lenders', 'lenders/*']) }}" data-bs-parent="#sidebarNav" id="liabilities">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('borrowed-funds.index') }}" class="nav-link {{ active_class(['borrowed-funds', 'borrowed-funds/*']) }}">Borrowed Funds</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('lenders.index') }}" class="nav-link {{ active_class(['lenders', 'lenders/*']) }}">Manage Lenders</a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- Financial Reports --}}
            <li class="nav-item nav-category">Reports</li>
            <li class="nav-item {{ active_class(['reports/profit-loss']) }}">
                <a href="{{ route('reports.profit_loss') }}" class="nav-link">
                    <i class="link-icon" data-lucide="bar-chart-2"></i>
                    <span class="link-title">Profit & Loss</span>
                </a>
            </li>
            <li class="nav-item {{ active_class(['transactions']) }}">
                <a href="{{ route('transactions.index') }}" class="nav-link">
                    {{-- 'repeat' is a great icon for recurring transactions/ledger --}}
                    <i class="link-icon" data-lucide="repeat"></i>
                    <span class="link-title">All Transactions (Ledger)</span>
                </a>
            </li>

            {{-- User Profile / Authentication --}}
            <li class="nav-item nav-category">Account</li>
            <li class="nav-item">
                {{-- This link should eventually go to a profile edit page --}}
                <a href="#" class="nav-link">
                    <i class="link-icon" data-lucide="user"></i>
                    <span class="link-title">My Profile</span>
                </a>
            </li>
            <li class="nav-item">
                {{-- Logout Form --}}
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                </form>
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="link-icon" data-lucide="log-out"></i>
                    <span class="link-title">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
