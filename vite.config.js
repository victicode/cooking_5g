import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx'
import vuetify from 'vite-plugin-vuetify'
import Components from 'unplugin-vue-components/vite'
import AutoImport from 'unplugin-auto-import/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vueJsx(),
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

          },
    },
    build: {
        chunkSizeWarningLimit: 5000,
    },
    base: '/',
    // optimizeDeps: {
    //     exclude: ['vuetify'],
    //     entries: [
    //         './resources/js/src/**/*.vue',
    //     ],
    // },
    
});
