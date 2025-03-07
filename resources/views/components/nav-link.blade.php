@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link active text-indigo-700 border-b-2 border-indigo-400'
            : 'nav-link text-gray-500 hover:text-gray-700 hover:border-gray-300 border-b-2 border-transparent';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
