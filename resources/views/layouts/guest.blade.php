<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @php
        $title = \App\Models\Setting::first();
    @endphp
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? $title->name : config('app.name', 'Maxobiz') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

        <link rel="stylesheet" href="./public/template/css/style.css" />
    </head>
    <body class="d-flex align-items-center">
        
        {{ $slot }}

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script>
            document
                .getElementById("togglePassword")
                .addEventListener("click", function () {
                    const passwordField = document.getElementById("password");
                    const eyeIcon = document.getElementById("eyeIcon");

                    // Check the current type of the password field
                    if (passwordField.type === "password") {
                    passwordField.type = "text"; // Show password
                    eyeIcon.classList.remove("fa-eye"); // Remove 'eye' icon
                    eyeIcon.classList.add("fa-eye-slash"); // Add 'eye-slash' icon
                    } else {
                    passwordField.type = "password"; // Hide password
                    eyeIcon.classList.remove("fa-eye-slash"); // Remove 'eye-slash' icon
                    eyeIcon.classList.add("fa-eye"); // Add 'eye' icon
                    }
                });
        </script>
    </body>
</html>
