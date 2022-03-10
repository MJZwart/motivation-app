/* eslint-disable max-lines-per-function */
import {createVuePlugin} from 'vite-plugin-vue2';
import ViteRequireContext from '@originjs/vite-plugin-require-context'

import {defineConfig} from 'vite';

const resolve = {
    alias: {
        '@': '/js',
    },
};

export default defineConfig(({command}) => {
    const production = command !== 'serve';

    const plugins = [
        createVuePlugin(),
        ViteRequireContext(),
    ];

    return {
        root: 'resources',
        base: production ? '/js/' : '',
        build: {
            assetsInclude: [],
            manifest: true,
            outDir: 'public/js',
            rollupOptions: {
                input: 'resources/js/app.js',
            },
        },
        plugins,
        server: {
            port: 3000,
        },
        resolve,
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
                'portal-vue',
                'axios',
                'bootstrap-vue',
                'vue-i18n',
                'vuex',
                'vue-router',
                'bootstrap-vue-editable-table',
            ],
        },

        // Vite bugs if you build out dir is inside the public dir so we change that to something else
        publicDir: 'random_non_existent_folder',
    };
});
