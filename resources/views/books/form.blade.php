<!-- Title -->
<div>
    <x-label for="title" :value="__('Title')" />
    <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $book->title ?? '')" required autofocus />
    @error('title')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Author -->
<div>
    <x-label for="author" :value="__('Author')" />
    <x-input id="author" class="block mt-1 w-full" type="text" name="author" :value="old('author', $book->author ?? '')" required />
    @error('author')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- ISBN -->
<div>
    <x-label for="isbn" :value="__('ISBN')" />
    <x-input id="isbn" class="block mt-1 w-full" type="text" name="isbn" :value="old('isbn', $book->isbn ?? '')" />
    <p class="text-gray-600 text-xs mt-1">Optional. Format: ISBN-13</p>
    @error('isbn')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Description -->
<div>
    <x-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="4" 
        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $book->description ?? '') }}</textarea>
    @error('description')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Published At -->
<div>
    <x-label for="published_at" :value="__('Publication Date')" />
    <x-input id="published_at" class="block mt-1 w-full" type="date" name="published_at" :value="old('published_at', isset($book) ? $book->published_at?->format('Y-m-d') : '')" />
    @error('published_at')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Copies Total -->
<div>
    <x-label for="copies_total" :value="__('Total Copies')" />
    <x-input id="copies_total" class="block mt-1 w-full" type="number" name="copies_total" :value="old('copies_total', $book->copies_total ?? 1)" required min="1" />
    @error('copies_total')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
