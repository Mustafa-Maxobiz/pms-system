<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary px-4 py-2 font-weight-bold text-uppercase hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 active:bg-blue-700 transition-all duration-200 ease-in-out']) }}>
    {{ $slot }}
</button>
