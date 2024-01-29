import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
//   base: '/public/tonarum-front/browser/index.html',
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
