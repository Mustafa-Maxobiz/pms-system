<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-danger btn-sm font-weight-semibold text-uppercase w-100']) }}>
    {{ $slot }}
</button>
