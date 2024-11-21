import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: 'localhost',
        port: 5173,
        // proxy: {
        //     '/resources': 'http://localhost:5173',
        //     '/': 'http://127.0.0.1:8000',
        // },
    },
    alias: {
        '@': '/resources/js',
    },
});
 