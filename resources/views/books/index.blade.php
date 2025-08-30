<x-app-layout>
    <x-slot name="header">
        Library Collection
    </x-slot>

    <!-- Search and Filter Bar -->
    <div class="mb-8">
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex-1">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-green-500"></i>
                        <input type="text" placeholder="Search books, authors, or ISBN..." 
                               class="w-full pl-12 pr-4 py-3 border border-green-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white/80 backdrop-blur-sm transition-all duration-200">
                    </div>
                </div>
                
                @auth
                    @if(Auth::user()->isAdmin)
                        <a href="{{ route('books.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold rounded-xl shadow-lg hover:from-green-600 hover:to-emerald-600 hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Add New Book
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($books as $book)
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100 hover:shadow-xl hover:border-green-200 transition-all duration-300 group overflow-hidden">
                <!-- Book Cover Placeholder -->
                <div class="h-48 bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center relative">
                    <i class="fas fa-book-open text-white text-4xl opacity-80"></i>
                    
                    <!-- Availability Badge -->
                    <div class="absolute top-3 right-3">
                        @if($book->copies_available > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                                <i class="fas fa-check-circle mr-1"></i>
                                Available
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200">
                                <i class="fas fa-times-circle mr-1"></i>
                                Unavailable
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Book Info -->
                <div class="p-6">
                    <h3 class="font-bold text-lg text-gray-800 mb-2 group-hover:text-green-600 transition-colors duration-200">
                        {{ $book->title }}
                    </h3>
                    
                    <p class="text-green-600 font-medium mb-1">by {{ $book->author }}</p>
                    
                    @if($book->isbn)
                        <p class="text-sm text-gray-500 mb-3">ISBN: {{ $book->isbn }}</p>
                    @endif

                    <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                        {{ Str::limit($book->description, 120) }}
                    </p>

                    <!-- Copies Info -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-copy mr-1"></i>
                            {{ $book->copies_available }} of {{ $book->copies_total }} available
                        </span>
                        
                        <!-- Progress Bar -->
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-2 rounded-full" 
                                 style="width: {{ $book->copies_total > 0 ? ($book->copies_available / $book->copies_total) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('books.show', $book) }}" 
                           class="w-full text-center px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 font-medium rounded-lg shadow-sm hover:from-gray-200 hover:to-gray-300 hover:shadow-md transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-eye mr-2"></i>
                            View Details
                        </a>
                        
                        @auth
                            @if($book->copies_available > 0)
                                <form action="{{ route('books.borrow', $book) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-medium rounded-lg shadow hover:from-green-600 hover:to-emerald-600 hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                        <i class="fas fa-hand-holding mr-2"></i>
                                        Borrow Now
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('books.request', $book) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-medium rounded-lg shadow hover:from-yellow-600 hover:to-orange-600 hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Request Book
                                    </button>
                                </form>
                            @endif

                            @if(Auth::user()->isAdmin)
                                <div class="flex space-x-2 mt-2">
                                    <a href="{{ route('books.edit', $book) }}" 
                                       class="flex-1 text-center px-3 py-2 bg-gradient-to-r from-blue-500 to-indigo-500 text-white text-sm font-medium rounded-lg shadow hover:from-blue-600 hover:to-indigo-600 hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                    
                                    <form action="{{ route('books.destroy', $book) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this book?')"
                                                class="w-full px-3 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white text-sm font-medium rounded-lg shadow hover:from-red-600 hover:to-pink-600 hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                            <i class="fas fa-trash mr-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-full">
                    <div class="text-center py-16">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-book-open text-4xl text-green-500"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">No Books Available</h3>
                        <p class="text-gray-500 mb-6">There are currently no books in the library collection.</p>
                        
                        @auth
                            @if(Auth::user()->isAdmin)
                                <a href="{{ route('books.create') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold rounded-xl shadow-lg hover:from-green-600 hover:to-emerald-600 hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add First Book
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
            <div class="mt-8">
                <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100 p-6">
                    {{ $books->links() }}
                </div>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-book text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Books</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Book::count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Available</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Book::where('copies_available', '>', 0)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-green-100 p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-hand-holding text-white"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Currently Borrowed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Borrowing::whereNull('returned_at')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
