<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-hand-holding text-green-600 text-xl"></i>
                <span>{{ Auth::user()->isAdmin ? 'All Borrowings' : 'My Borrowings' }}</span>
            </div>
            <div class="text-sm text-gray-600">
                <i class="fas fa-info-circle mr-1"></i>
                {{ $borrowings->count() }} total borrowings
            </div>
        </div>
    </x-slot>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @php
            $activeBorrowings = $borrowings->whereNull('returned_at')->count();
            $overdueBorrowings = $borrowings->where('status', 'overdue')->count();
            $returnedBorrowings = $borrowings->whereNotNull('returned_at')->count();
        @endphp
        
        <div class="card-glass p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-books text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Active Borrowings</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeBorrowings }}</p>
                </div>
            </div>
        </div>

        <div class="card-glass p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Overdue Books</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $overdueBorrowings }}</p>
                </div>
            </div>
        </div>

        <div class="card-glass p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Returned Books</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $returnedBorrowings }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrowings Table -->
    <div class="card-glass overflow-hidden">
        @if($borrowings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-green-50 to-emerald-50">
                        <tr>
                            @if(Auth::user()->isAdmin)
                                <th class="px-6 py-4 text-left text-xs font-semibold text-green-700 uppercase tracking-wider">
                                    <i class="fas fa-user mr-2"></i>User
                                </th>
                            @endif
                            <th class="px-6 py-4 text-left text-xs font-semibold text-green-700 uppercase tracking-wider">
                                <i class="fas fa-book mr-2"></i>Book
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-green-700 uppercase tracking-wider">
                                <i class="fas fa-calendar mr-2"></i>Borrowed
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-green-700 uppercase tracking-wider">
                                <i class="fas fa-clock mr-2"></i>Due Date
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-green-700 uppercase tracking-wider">
                                <i class="fas fa-info-circle mr-2"></i>Status
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-green-700 uppercase tracking-wider">
                                <i class="fas fa-cogs mr-2"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($borrowings as $borrowing)
                            <tr class="hover:bg-green-50/50 transition-colors duration-200">
                                @if(Auth::user()->isAdmin)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center text-white font-semibold text-sm">
                                                {{ substr($borrowing->user->name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $borrowing->user->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $borrowing->user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-12 bg-gradient-to-br from-green-100 to-emerald-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-book text-green-600"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('books.show', $borrowing->book) }}" class="text-sm font-medium text-green-600 hover:text-green-700 transition-colors duration-200">
                                                {{ $borrowing->book->title }}
                                            </a>
                                            <p class="text-xs text-gray-500">by {{ $borrowing->book->author }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $borrowing->borrowed_at->format('M d, Y') }}</span>
                                        <span class="text-xs text-gray-400">{{ $borrowing->borrowed_at->format('g:i A') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex flex-col">
                                        <span class="font-medium {{ $borrowing->due_at->isPast() && !$borrowing->returned_at ? 'text-red-600' : 'text-gray-900' }}">
                                            {{ $borrowing->due_at->format('M d, Y') }}
                                        </span>
                                        <span class="text-xs {{ $borrowing->due_at->isPast() && !$borrowing->returned_at ? 'text-red-400' : 'text-gray-400' }}">
                                            {{ $borrowing->due_at->format('g:i A') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($borrowing->returned_at)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-700 border border-green-200">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Returned
                                        </span>
                                    @elseif($borrowing->due_at->isPast())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-red-100 to-pink-100 text-red-700 border border-red-200">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Overdue
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 border border-blue-200">
                                            <i class="fas fa-clock mr-1"></i>
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    @if(!$borrowing->returned_at)
                                        <form action="{{ route('borrowings.return', $borrowing) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs font-medium rounded-lg hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-sm">
                                                <i class="fas fa-undo mr-1"></i>
                                                Return
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 flex items-center">
                                            <i class="fas fa-calendar-check mr-1"></i>
                                            Returned {{ $borrowing->returned_at->format('M d, Y') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($borrowings->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    {{ $borrowings->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-20 h-20 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-hand-holding text-green-500 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-3">No Borrowings Found</h3>
                <p class="text-gray-500 mb-6">
                    {{ Auth::user()->isAdmin ? "No books have been borrowed yet." : "You haven't borrowed any books yet." }}
                </p>
                <a href="{{ route('books.index') }}" class="btn-gradient">
                    <i class="fas fa-book mr-2"></i>
                    Browse Books
                </a>
            </div>
        @endif
    </div>
</x-app-layout>
