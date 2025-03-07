import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            ///input: ['resources/css/app.css', 'resources/js/app.js'],
            input: [],
            refresh: true,
            valetTls: false, // Disable Valet-specific configuration
        }),
    ],
    server: {
        host: '127.0.0.1', // Ensures it runs locally on Windows
    },
});
