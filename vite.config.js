import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/adminlte.css',
                'resources/js/adminlte.js',
                'resources/js/home-map.js',
                'resources/js/centros-map.js',
            ],
            refresh: true,
        }),
    ],
});
