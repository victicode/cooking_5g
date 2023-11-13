import { defineConfig } from 'vite';
import path from 'path'
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx'
import { fileURLToPath } from 'node:url'
import vuetify from 'vite-plugin-vuetify'
import Components from 'unplugin-vue-components/vite'
import AutoImport from 'unplugin-auto-import/vite'


console.log(`${path.resolve(__dirname, 'resources/js/src/')}`)
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
        vueJsx(),

        // Docs: https://github.com/vuetifyjs/vuetify-loader/tree/master/packages/vite-plugin
        vuetify({
        styles: {
            configFile: 'resources/js/src/styles/variables/_vuetify.scss',
        },
        }),
        Components({
        dirs: ['resources/css/src/@core/components'],
        dts: true,
        }),
        AutoImport({
            eslintrc: {
              enabled: true,
              filepath: './.eslintrc-auto-import.json',
            },
            imports: ['vue', 'vue-router', '@vueuse/core', '@vueuse/math', 'pinia'],
            vueTemplate: true,
        }),
    ],
    define: { 'process.env': {} },
    resolve: {
        // alias: {
        //     '@/': __dirname + '/resources/js/src/',
        // }
        alias: {
            '@/': __dirname + '/resources/js/src/',
            '@core':  __dirname + '/resources/js/src/@core',
            '@layouts':  __dirname + '/resources/js/src/@layouts',
            '@images':  __dirname + '/resources/js/src/assets/images/',
            '@styles': __dirname + '/resources/js/src/styles',
            '@configured-variables': __dirname + '/resources/js/src/styles/variables/_template.scss', 
            '@axios':__dirname + '/resources/js/src/plugins/axios',

            // '@': fileURLToPath(new URL('./resources/js/src', import.meta.url)),
            // '@core': fileURLToPath(new URL('./resources/js/src/@core', import.meta.url)),
            // '@layouts': fileURLToPath(new URL('./resources/js/src/@layouts', import.meta.url)),
            // '@images': fileURLToPath(new URL('./resources/js/src/assets/images/', import.meta.url)),
            // '@styles': fileURLToPath(new URL('./resources/js/src/styles/', import.meta.url)),
            // '@configured-variables': fileURLToPath(new URL('./resources/js/src/styles/variables/_template.scss', import.meta.url)),
            // '@axios': fileURLToPath(new URL('./resources/js/src/plugins/axios', import.meta.url)),
          },
    },
    build: {
        chunkSizeWarningLimit: 5000,
    },
    base: '/',
    optimizeDeps: {
        exclude: ['vuetify'],
        entries: [
            './resources/js/src/**/*.vue',
        ],
    },
    
});
