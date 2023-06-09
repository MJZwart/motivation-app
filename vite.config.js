import vue from '@vitejs/plugin-vue';
import Markdown from 'vite-plugin-md';

import {defineConfig} from 'vite';

// Needed to allow env variables to be accessed
import envVars from 'dotenv';
envVars.config()

// eslint-disable-next-line max-lines-per-function
export default defineConfig(({command}) => {
    const production = command !== 'serve';

    return {
        root: 'resources',
        base: production ? '/js/' : '',
        build: {
            target: 'es2022',
            assetsInclude: [],
            manifest: true,
            outDir: '../public/js',
            emptyOutDir: true,
            rollupOptions: {
                input: 'resources/js/app.js',
            },
        },
        plugins: [
            vue({
                include: [/\.vue$/, /\.md$/],
            }),
            Markdown(),
        ],
        server: {
            port: 3000,
        },
        define: {
            __VUE_I18N_FULL_INSTALL__: true,
            __VUE_I18N_LEGACY_API__: false,
            __INTLIFY_PROD_DEVTOOLS__: false,
        },
        css: {
            preprocessorOptions: {
                scss: {
                    quietDeps: true,
                },
            },
        },
        optimizeDeps: {
            include: [
                'vue',
                'axios',
                'vue-i18n',
                'vue-router',
                'pinia',
                'luxon',
                '@vueform/multiselect',
                'platform',
            ],
        },

        // Vite bugs if you build out dir is inside the public dir so we change that to something else
        publicDir: 'random_non_existent_folder',
    };
});