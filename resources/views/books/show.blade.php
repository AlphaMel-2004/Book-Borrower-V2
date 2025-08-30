<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $book->title }}
            </h2>
            <div class="space-x-2">
                @auth
                    @if(Auth::user()->isAdmin)
                        <a href="{{ route('books.edit', $book) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Edit Book
                        </a>
                    @endif
                @endauth
                <a href="{{ route('books.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Books
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold mb-4">Book Details</h3>
                    <dl class="grid grid-cols-1 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Author</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $book->author }}</dd>
                        </div>

                        @if($book->isbn)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ISBN</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $book->isbn }}</dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Published</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $book->published_at ? $book->published_at->format('F j, Y') : 'Not specified' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $book->description ?: 'No description available.' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Availability</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $book->copies_available > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $book->copies_available }} of {{ $book->copies_total }} copies available
                                </span>
                            </dd>
                        </div>
                    </dl>

                    @auth
                        <div class="mt-6 space-x-4">
                            @if($book->copies_available > 0)
                                <form action="{{ route('books.borrow', $book) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Borrow Book
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('books.request', $book) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                                        Request Book
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endauth
                </div>

                @if(Auth::check() && Auth::user()->isAdmin)
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Current Borrowers</h3>
                        <div class="space-y-4">
                            @forelse($book->borrowings->whereNull('returned_at') as $borrowing)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="font-medium">{{ $borrowing->user->name }}</p>
                                    <p class="text-sm text-gray-600">Borrowed: {{ $borrowing->borrowed_at->format('M j, Y') }}</p>
                                    <p class="text-sm {{ $borrowing->due_at->isPast() ? 'text-red-600' : 'text-gray-600' }}">
                                        Due: {{ $borrowing->due_at->format('M j, Y') }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-gray-500">No current borrowers</p>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
