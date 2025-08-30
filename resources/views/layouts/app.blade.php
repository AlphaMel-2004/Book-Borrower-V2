<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BookBorrower') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #d1fae5 100%);
        }
        .brand-font {
            font-family: 'Poppins', sans-serif;
        }
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            color: #6b7280;
            transition: all 0.2s ease;
            text-decoration: none;
            margin: 0.25rem 0;
        }
        .sidebar-item:hover {
            color: #059669;
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
            transform: translateX(4px);
        }
        .sidebar-item.active {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #047857;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .btn-gradient {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -3px rgba(16, 185, 129, 0.4);
        }
        .card-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @auth
            <aside class="fixed left-0 top-0 w-64 h-full card-glass shadow-xl z-50 transform lg:transform-none -translate-x-full lg:translate-x-0 transition-transform duration-300" id="sidebar">
                <!-- Logo/Brand -->
                <div class="p-6 border-b border-green-200/50">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center shadow-lg">
                            <i class="fas fa-book-open text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent brand-font">
                                BookBorrower
                            </h1>
                            <p class="text-xs text-gray-500">Digital Library</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="p-4 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('home') }}" class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="fas fa-home w-5"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- Books -->
                    <a href="{{ route('books.index') }}" class="sidebar-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
                        <i class="fas fa-book w-5"></i>
                        <span>Books</span>
                        <span class="ml-auto bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">
                            {{ \App\Models\Book::count() }}
                        </span>
                    </a>

                    <!-- My Borrowings -->
                    <a href="{{ route('borrowings.index') }}" class="sidebar-item {{ request()->routeIs('borrowings.*') ? 'active' : '' }}">
                        <i class="fas fa-hand-holding w-5"></i>
                        <span>{{ Auth::user()->isAdmin ? 'All Borrowings' : 'My Borrowings' }}</span>
                        @if(auth()->user()->isAdmin)
                            @php $activeBorrowings = \App\Models\Borrowing::whereNull('returned_at')->count(); @endphp
                        @else
                            @php $activeBorrowings = auth()->user()->borrowings()->whereNull('returned_at')->count(); @endphp
                        @endif
                        @if($activeBorrowings > 0)
                            <span class="ml-auto bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded-full">
                                {{ $activeBorrowings }}
                            </span>
                        @endif
                    </a>

                    <!-- Requests -->
                    <a href="{{ route('requests.index') }}" class="sidebar-item {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                        <i class="fas fa-paper-plane w-5"></i>
                        <span>{{ Auth::user()->isAdmin ? 'Book Requests' : 'My Requests' }}</span>
                        @if(Auth::user()->isAdmin)
                            @php $pendingRequests = \App\Models\Request::where('status', 'pending')->count(); @endphp
                            @if($pendingRequests > 0)
                                <span class="ml-auto bg-orange-100 text-orange-600 text-xs px-2 py-1 rounded-full">
                                    {{ $pendingRequests }}
                                </span>
                            @endif
                        @endif
                    </a>

                    <!-- Fines -->
                    <a href="{{ route('fines.index') }}" class="sidebar-item {{ request()->routeIs('fines.*') ? 'active' : '' }}">
                        <i class="fas fa-exclamation-triangle w-5"></i>
                        <span>{{ Auth::user()->isAdmin ? 'All Fines' : 'My Fines' }}</span>
                        @if(auth()->user()->isAdmin)
                            @php $unpaidFines = \App\Models\Fine::where('paid', false)->count(); @endphp
                        @else
                            @php $unpaidFines = auth()->user()->fines()->where('paid', false)->count(); @endphp
                        @endif
                        @if($unpaidFines > 0)
                            <span class="ml-auto bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">
                                {{ $unpaidFines }}
                            </span>
                        @endif
                    </a>

                    <!-- Admin Section -->
                    @if(Auth::user()->isAdmin)
                        <div class="pt-4 mt-4 border-t border-green-200/50">
                            <div class="px-3 py-2 text-xs font-semibold text-green-600 uppercase tracking-wider">
                                Administration
                            </div>
                            
                            <a href="{{ route('admin.dashboard') }}" class="sidebar-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                                <i class="fas fa-chart-line w-5"></i>
                                <span>Admin Dashboard</span>
                            </a>

                            <a href="{{ route('books.create') }}" class="sidebar-item">
                                <i class="fas fa-plus-circle w-5"></i>
                                <span>Add New Book</span>
                            </a>
                        </div>
                    @endif
                </nav>

                <!-- User Info (Bottom) -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-green-200/50 bg-gradient-to-r from-green-50/80 to-emerald-50/80">
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
            </aside>
        @endauth

        <!-- Main Content Area -->
        <div class="flex-1 {{ auth()->check() ? 'ml-64' : '' }} transition-all duration-300">
            <!-- Top Header -->
            <header class="card-glass border-b border-green-200/50 sticky top-0 z-40">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <!-- Mobile Menu Button -->
                        @auth
                            <button id="mobile-menu-button" class="lg:hidden btn-gradient">
                                <i class="fas fa-bars"></i>
                            </button>
                        @endauth

                        <!-- Page Title -->
                        @if (isset($header))
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold bg-gradient-to-r from-green-700 to-emerald-700 bg-clip-text text-transparent brand-font">
                                    {{ $header }}
                                </h1>
                            </div>
                        @endif

                        <!-- User Menu -->
                        @auth
                            <div class="flex items-center space-x-4">
                                <!-- Notifications -->
                                <button class="p-2 rounded-xl bg-gradient-to-r from-green-100 to-emerald-100 hover:from-green-200 hover:to-emerald-200 transition-all duration-200">
                                    <i class="fas fa-bell text-green-600"></i>
                                </button>

                                <!-- User Profile Dropdown -->
                                <div class="relative group">
                                    <button class="flex items-center space-x-3 p-2 rounded-xl hover:bg-green-50 transition-all duration-200">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center text-white font-semibold">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                        <span class="hidden md:block text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div class="absolute right-0 mt-2 w-48 card-glass opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                        <div class="p-2">
                                            <div class="px-3 py-2 border-b border-green-100">
                                                <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                            </div>
                                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                                    Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Guest Navigation -->
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('login') }}" class="px-6 py-2 text-green-600 font-medium hover:text-green-700 transition-colors duration-200">
                                    Login
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn-gradient">
                                        Get Started
                                    </a>
                                @endif
                            </div>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="mb-6 p-4 bg-gradient-to-r from-green-100 to-emerald-100 border border-green-200 text-green-800 rounded-xl shadow-sm flex items-center card-glass">
                        <i class="fas fa-check-circle mr-3 text-green-600"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-gradient-to-r from-red-100 to-pink-100 border border-red-200 text-red-800 rounded-xl shadow-sm flex items-center card-glass">
                        <i class="fas fa-exclamation-circle mr-3 text-red-600"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Main Content -->
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

    <script>
        // Mobile menu functionality
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                mobileOverlay.classList.toggle('hidden');
            });
        }

        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                mobileOverlay.classList.add('hidden');
            });
        }
    </script>
</body>
</html>
