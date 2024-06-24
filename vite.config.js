import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/bootstrap.js',
                'resources/js/chat.js',
                'resources/js/emissions.js',
                'resources/js/favorites.js',
                'resources/js/map.js',

            ],
            refresh: true,
        }),
    ],
});
