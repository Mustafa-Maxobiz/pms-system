@props(['value'])

<label {{ $attributes->merge(['class' => 'form-label font-weight-medium text-muted']) }}>
    {{ $value ?? $slot }}
</label>
