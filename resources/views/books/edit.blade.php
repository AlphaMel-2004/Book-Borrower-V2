<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Book') }}: {{ $book->title }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form method="POST" action="{{ route('books.update', $book) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            @include('books.form')

            <div class="flex items-center justify-end">
                <x-button>
                    {{ __('Update Book') }}
                </x-button>
            </div>
        </form>
    </div>
</x-app-layout>
