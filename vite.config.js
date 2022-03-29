// import {createVuePlugin} from 'vite-plugin-vue2';
import ViteRequireContext from '@originjs/vite-plugin-require-context'
import vue from '@vitejs/plugin-vue'

import {defineConfig} from 'vite';

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
            outDir: 'public/js',
            rollupOptions: {
                input: 'resources/js/app.js',
            },
        },
        plugins: [
            ViteRequireContext(),
            vue({
                template: {
                    compilerOptions: {
                        compatConfig: {
                            MODE: 2,
                        },
                    },
                },
            }),
        ],
        server: {
            port: 3000,
        },
        resolve: {
            alias: {
                '@': '/js',
                'vue': '@vue/compat',
            },
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


// const resolve = {
//     alias: {
//         '@': '/js',
//         'vue': '@vue/compat',
//     },
// };
// const plugins = [
//     // createVuePlugin(),
//     ViteRequireContext(),
//     vue({
//         template: {
//             compilerOptions: {
//                 compatConfig: {
//                     MODE: 2,
//                 },
//             },
//         },
//     }),
// ];