<!-- Sidebar -->
<aside id="sidebar" class="fixed left-0 top-0 w-64 h-full bg-white/95 backdrop-blur-lg border-r border-green-100 shadow-xl z-50 transform lg:transform-none -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <!-- Logo/Brand -->
    <div class="p-6 border-b border-green-100">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                <i class="fas fa-book-open text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent brand-font">
                    BookBorrower
                </h1>
                <p class="text-xs text-gray-500">Library Management</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="p-4 space-y-2">
        <!-- Dashboard/Home -->
        <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>

        <!-- Books -->
        <a href="{{ route('books.index') }}" class="nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
            <i class="fas fa-book"></i>
            <span>Books</span>
            <span class="ml-auto bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">{{ \App\Models\Book::count() }}</span>
        </a>

        @auth
            <!-- My Borrowings -->
            <a href="{{ route('borrowings.index') }}" class="nav-item {{ request()->routeIs('borrowings.*') ? 'active' : '' }}">
                <i class="fas fa-hand-holding"></i>
                <span>{{ Auth::user()->isAdmin ? 'All Borrowings' : 'My Borrowings' }}</span>
                @if(Auth::user()->borrowings()->whereNull('returned_at')->count() > 0)
                    <span class="ml-auto bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full">
                        {{ Auth::user()->borrowings()->whereNull('returned_at')->count() }}
                    </span>
                @endif
            </a>

            <!-- Requests -->
            <a href="{{ route('requests.index') }}" class="nav-item {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                <i class="fas fa-paper-plane"></i>
                <span>{{ Auth::user()->isAdmin ? 'Book Requests' : 'My Requests' }}</span>
                @if(Auth::user()->isAdmin && \App\Models\Request::where('status', 'pending')->count() > 0)
                    <span class="ml-auto bg-orange-100 text-orange-600 text-xs px-2 py-1 rounded-full">
                        {{ \App\Models\Request::where('status', 'pending')->count() }}
                    </span>
                @endif
            </a>

            <!-- Fines -->
            <a href="{{ route('fines.index') }}" class="nav-item {{ request()->routeIs('fines.*') ? 'active' : '' }}">
                <i class="fas fa-exclamation-triangle"></i>
                <span>{{ Auth::user()->isAdmin ? 'All Fines' : 'My Fines' }}</span>
                @if(\App\Models\Fine::where('paid', false)->count() > 0)
                    <span class="ml-auto bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">
                        {{ \App\Models\Fine::where('paid', false)->count() }}
                    </span>
                @endif
            </a>

            <!-- Admin Section -->
            @if(Auth::user()->isAdmin)
                <div class="pt-4 mt-4 border-t border-green-100">
                    <div class="px-3 py-2 text-xs font-semibold text-green-600 uppercase tracking-wider">
                        Administration
                    </div>
                    
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Admin Dashboard</span>
                    </a>

                    <a href="{{ route('books.create') }}" class="nav-item">
                        <i class="fas fa-plus-circle"></i>
                        <span>Add New Book</span>
                    </a>
                </div>
            @endif
        @endauth
    </nav>

    <!-- User Info (Bottom) -->
    @auth
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-green-100 bg-gradient-to-r from-green-50 to-emerald-50">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center text-white font-semibold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
                @if(Auth::user()->isAdmin)
                    <span class="bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs px-2 py-1 rounded-full">Admin</span>
                @endif
            </div>
        </div>
    @endauth
</aside>

<style>
    .nav-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        border-radius: 0.75rem;
        color: #4b5563;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .nav-item:hover {
        color: #059669;
        background: linear-gradient(to right, #f0fdf4, #ecfdf5);
        transform: translateX(2px);
    }
    
    .nav-item.active {
        background: linear-gradient(to right, #dcfce7, #d1fae5);
        color: #047857;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #bbf7d0;
    }
    
    .nav-item i {
        width: 1.25rem;
        height: 1.25rem;
        text-align: center;
        transition: transform 0.2s ease;
    }
    
    .nav-item:hover i {
        transform: scale(1.1);
    }
    
    .nav-item.active i {
        color: #059669;
    }
</style>
