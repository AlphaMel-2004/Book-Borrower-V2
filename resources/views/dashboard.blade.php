<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-home text-green-600 text-xl"></i>
                <span>Dashboard</span>
            </div>
            <div class="text-sm text-gray-600">
                <i class="fas fa-calendar mr-1"></i>
                {{ now()->format('F j, Y') }}
            </div>
        </div>
    </x-slot>

    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="card-glass p-8 bg-gradient-to-r from-green-500 to-emerald-500 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2 brand-font">
                        Welcome back, {{ Auth::user()->name }}! 👋
                    </h1>
                    <p class="text-green-100 text-lg">
                        @php $hour = now()->hour; @endphp
                        @if($hour < 12)
                            Good morning! Ready to explore some books?
                        @elseif($hour < 17)
                            Good afternoon! Time for some reading?
                        @else
                            Good evening! Perfect time to dive into a good book.
                        @endif
                    </p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-book-reader text-4xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
            $userBorrowings = Auth::user()->borrowings();
            $activeBorrowings = $userBorrowings->whereNull('returned_at')->count();
            $totalBorrowings = $userBorrowings->count();
            $overdueBorrowings = $userBorrowings->where('due_at', '<', now())->whereNull('returned_at')->count();
            $userFines = Auth::user()->fines()->where('paid', false)->sum('amount');
        @endphp

        <!-- Active Borrowings -->
        <div class="card-glass p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-books text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Currently Borrowed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeBorrowings }}</p>
                </div>
            </div>
        </div>

        <!-- Total Books Read -->
        <div class="card-glass p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book-open text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Books Read</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalBorrowings }}</p>
                </div>
            </div>
        </div>

        <!-- Overdue Books -->
        <div class="card-glass p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Overdue Books</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $overdueBorrowings }}</p>
                </div>
            </div>
        </div>

        <!-- Outstanding Fines -->
        <div class="card-glass p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Outstanding Fines</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($userFines, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Recent Borrowings -->
        <div class="card-glass p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-history text-green-600 mr-2"></i>
                    Recent Borrowings
                </h3>
                <a href="{{ route('borrowings.index') }}" class="text-green-600 hover:text-green-700 transition-colors duration-200 text-sm font-medium">
                    View All
                </a>
            </div>

            @php
                $recentBorrowings = Auth::user()->borrowings()->with('book')->latest()->take(3)->get();
            @endphp

            @if($recentBorrowings->count() > 0)
                <div class="space-y-4">
                    @foreach($recentBorrowings as $borrowing)
                        <div class="flex items-center space-x-3 p-3 bg-green-50/50 rounded-lg">
                            <div class="w-10 h-12 bg-gradient-to-br from-green-100 to-emerald-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-green-600"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $borrowing->book->title }}</p>
                                <p class="text-xs text-gray-500">Borrowed {{ $borrowing->borrowed_at->diffForHumans() }}</p>
                            </div>
                            @if(!$borrowing->returned_at)
                                @if($borrowing->due_at->isPast())
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-100 text-red-600">
                                        Overdue
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-600">
                                        Active
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-600">
                                    Returned
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-book-open text-gray-300 text-3xl mb-3"></i>
                    <p class="text-gray-500">No borrowings yet</p>
                    <p class="text-sm text-gray-400">Start by browsing our book collection</p>
                </div>
            @endif
        </div>

        <!-- Quick Links -->
        <div class="card-glass p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-bolt text-green-600 mr-2"></i>
                Quick Actions
            </h3>

            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('books.index') }}" class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all duration-200 group">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-search text-white"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-800">Browse Books</p>
                    </div>
                </a>

                <a href="{{ route('borrowings.index') }}" class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 group">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-list text-white"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-800">My Borrowings</p>
                    </div>
                </a>

                <a href="{{ route('requests.index') }}" class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-xl hover:from-purple-100 hover:to-pink-100 transition-all duration-200 group">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-paper-plane text-white"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-800">My Requests</p>
                    </div>
                </a>

                <a href="{{ route('fines.index') }}" class="p-4 bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl hover:from-yellow-100 hover:to-orange-100 transition-all duration-200 group">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <i class="fas fa-receipt text-white"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-800">My Fines</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Reading Progress (if user has active borrowings) -->
    @if($activeBorrowings > 0)
        <div class="card-glass p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-chart-line text-green-600 mr-2"></i>
                Reading Progress
            </h3>

            @php
                $activeBorrowingsList = Auth::user()->borrowings()->whereNull('returned_at')->with('book')->get();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($activeBorrowingsList as $borrowing)
                    <div class="p-4 border border-gray-200 rounded-xl hover:border-green-300 transition-colors duration-200">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-8 h-10 bg-gradient-to-br from-green-100 to-emerald-100 rounded flex items-center justify-center">
                                <i class="fas fa-book text-green-600 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $borrowing->book->title }}</p>
                                <p class="text-xs text-gray-500">by {{ $borrowing->book->author }}</p>
                            </div>
                        </div>
                        
                        @php
                            $daysTotal = $borrowing->borrowed_at->diffInDays($borrowing->due_at);
                            $daysUsed = $borrowing->borrowed_at->diffInDays(now());
                            $progress = $daysTotal > 0 ? min(100, ($daysUsed / $daysTotal) * 100) : 0;
                            $daysLeft = $borrowing->due_at->diffInDays(now(), false);
                        @endphp
                        
                        <div class="mb-2">
                            <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                <span>Reading Progress</span>
                                <span>{{ $daysLeft >= 0 ? $daysLeft . ' days left' : abs($daysLeft) . ' days overdue' }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full {{ $daysLeft >= 0 ? 'bg-gradient-to-r from-green-400 to-emerald-500' : 'bg-gradient-to-r from-red-400 to-pink-500' }}" 
                                     style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                        
                        <p class="text-xs {{ $daysLeft >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            Due: {{ $borrowing->due_at->format('M d, Y') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-app-layout>
