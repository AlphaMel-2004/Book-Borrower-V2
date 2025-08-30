@props(['type' => 'success', 'position' => 'top'])

@php
switch ($type) {
    case 'success':
        $classes = 'bg-green-100 border-l-4 border-green-500 text-green-700';
        break;
    case 'error':
        $classes = 'bg-red-100 border-l-4 border-red-500 text-red-700';
        break;
    case 'warning':
        $classes = 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700';
        break;
    case 'info':
        $classes = 'bg-blue-100 border-l-4 border-blue-500 text-blue-700';
        break;
    default:
        $classes = 'bg-gray-100 border-l-4 border-gray-500 text-gray-700';
}

switch ($position) {
    case 'top':
        $positionClasses = 'fixed top-0 right-0 mt-4 mr-4';
        break;
    case 'bottom':
        $positionClasses = 'fixed bottom-0 right-0 mb-4 mr-4';
        break;
    default:
        $positionClasses = 'fixed top-0 right-0 mt-4 mr-4';
}
@endphp

<div {{ $attributes->merge(['class' => "{$classes} p-4 rounded-lg shadow-md {$positionClasses}"]) }} role="alert">
    {{ $slot }}
</div>
