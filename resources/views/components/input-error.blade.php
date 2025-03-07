@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-small text-danger list-unstyled p-2']) }}>
        @foreach ((array) $messages as $message)
            <li class="text-dark">{{ $message }}</li>
        @endforeach
    </ul>
@endif
