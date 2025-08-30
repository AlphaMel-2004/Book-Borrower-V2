@props(['type' => 'submit', 'variant' => 'primary'])

@php
$classes = match($variant) {
    'primary' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-green-700 hover:to-emerald-700 focus:from-green-700 focus:to-emerald-700 active:from-green-900 active:to-emerald-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150',
    'secondary' => 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150',
    'danger' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-red-700 hover:to-pink-700 focus:from-red-700 focus:to-pink-700 active:from-red-900 active:to-pink-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150',
    default => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-green-700 hover:to-emerald-700 focus:from-green-700 focus:to-emerald-700 active:from-green-900 active:to-emerald-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150'
};
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
    {{ $slot }}
</button>
