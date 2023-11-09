import { defineConfig } from 'vite';
import path from 'path'
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
console.log(`${path.resolve(__dirname, 'resources/js/src/')}`)
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
                '@/': __dirname + '/resources/js/src/',
        }
    }
    
});
