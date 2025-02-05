import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/filme.css',
                'resources/css/home.css',
                'resources/js/app.js',
                'resources/js/filme.js'

            ],
            refresh: true,
        }),
    ],

    build: {
        minify: false,
        rollupOptions: {
            output: {
              entryFileNames: 'assets/[name].js',
              chunkFileNames: 'assets/[name].js',
              assetFileNames: 'assets/[name].[ext]'
            }
        }
    }
});
