@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'text-success font-weight-medium']) }}>
        {{ $status }}
    </div>
@endif
