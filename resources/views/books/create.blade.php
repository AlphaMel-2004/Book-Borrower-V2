<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Book') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form method="POST" action="{{ route('books.store') }}" class="p-6 space-y-6">
            @csrf
            @include('books.form')

            <div class="flex items-center justify-end">
                <x-button>
                    {{ __('Create Book') }}
                </x-button>
            </div>
        </form>
    </div>
</x-app-layout>
