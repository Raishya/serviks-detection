import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import Inspect from 'vite-plugin-inspect';


export default defineConfig({
    plugins: [
        Inspect(),
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/css/pages/welcome.css',
                'resources/css/pages/arsip.css',
                'resources/css/app.css',
            ],
            refresh: true,
        }),
    ],
});
