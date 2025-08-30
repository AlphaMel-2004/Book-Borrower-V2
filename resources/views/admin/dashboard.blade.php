<x-app-layout>
    <x-slot name="header">
        Admin Dashboard
    </x-slot>

    <!-- Welcome Banner -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl shadow-lg text-white p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-green-100">Here's what's happening in your library today</p>
                </div>
                <div class="hidden lg:block">
                    <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-chart-line text-4xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Books -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Books</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_books'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">
                        <span class="text-orange-600">{{ $stats['books_out'] }}</span> currently borrowed
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book text-white"></i>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    <p class="text-sm text-green-600 mt-1">Active members</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white"></i>
                </div>
            </div>
        </div>

        <!-- Active Borrowings -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Active Borrowings</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['active_borrowings'] }}</p>
                    <p class="text-sm text-red-600 mt-1">
                        <span class="font-medium">{{ $stats['overdue_borrowings'] }}</span> overdue
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-hand-holding text-white"></i>
                </div>
            </div>
        </div>

        <!-- Total Fines -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100 p-6 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Total Fines</p>
                    <p class="text-3xl font-bold text-gray-900">${{ number_format($stats['total_fines'], 2) }}</p>
                    <p class="text-sm text-red-600 mt-1">
                        <span class="font-medium">${{ number_format($stats['unpaid_fines'], 2) }}</span> unpaid
                    </p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-white"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('books.create') }}" 
                   class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Add New Book</p>
                        <p class="text-sm text-gray-600">Expand your collection</p>
                    </div>
                </a>

                <a href="{{ route('requests.index') }}" 
                   class="flex items-center p-4 bg-gradient-to-r from-orange-50 to-yellow-50 border border-orange-200 rounded-xl hover:from-orange-100 hover:to-yellow-100 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-yellow-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Pending Requests</p>
                        <p class="text-sm text-gray-600">{{ $stats['pending_requests'] }} waiting for approval</p>
                    </div>
                </a>

                <a href="{{ route('borrowings.index') }}" 
                   class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-list text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Manage Borrowings</p>
                        <p class="text-sm text-gray-600">Track returns and renewals</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Borrowings -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100">
            <div class="px-6 py-4 border-b border-green-100 bg-gradient-to-r from-green-50 to-emerald-50 rounded-t-2xl">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-history mr-2 text-green-600"></i>
                    Recent Borrowings
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentBorrowings as $borrowing)
                        <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-gray-50 to-green-50 rounded-xl border border-gray-200">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-book-open text-white text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $borrowing->book->title }}</p>
                                <p class="text-sm text-gray-600">by {{ $borrowing->user->name }}</p>
                                <p class="text-xs text-gray-500">Due {{ $borrowing->due_at->format('M j, Y') }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                @if($borrowing->status === 'overdue')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Overdue
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Active
                                    </span>
                                @endif
                            </div>
                        </div>
                        @empty
                            <p class="text-gray-500">No recent borrowings</p>
                        @endforelse
                    </div>
                </div>
            </div>

        <!-- Pending Requests -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100">
            <div class="px-6 py-4 border-b border-green-100 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-clock mr-2 text-orange-600"></i>
                        Pending Requests
                    </h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-orange-100 to-yellow-100 text-orange-800 border border-orange-200">
                        {{ $stats['pending_requests'] }} pending
                    </span>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($pendingRequests as $request)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-orange-50 rounded-xl border border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-yellow-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-paper-plane text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $request->book->title }}</p>
                                    <p class="text-sm text-gray-600">by {{ $request->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $request->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <form action="{{ route('requests.approve', $request) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-lg shadow hover:from-green-600 hover:to-emerald-600 hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                        <i class="fas fa-check mr-1"></i>
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('requests.reject', $request) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-3 py-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-sm font-medium rounded-lg shadow hover:from-red-600 hover:to-pink-600 hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                        <i class="fas fa-times mr-1"></i>
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-gray-100 to-orange-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500">No pending requests</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
