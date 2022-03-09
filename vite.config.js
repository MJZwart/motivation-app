import {createVuePlugin as Vue2Plugin} from 'vite-plugin-vue2';
const {resolve} = require('path');
const Dotenv = require('dotenv');
 
Dotenv.config();
 
const ASSET_URL = process.env.ASSET_URL || '';
 
export default {
    plugins: [
        Vue2Plugin(),
    ],
 
    root: 'resources',
    base: `${ASSET_URL}/`,
 
    build: {
        outDir: resolve(__dirname, 'public/dist'),
        emptyOutDir: true,
        manifest: true,
        target: 'es2018',
        rollupOptions: {
            input: '/js/app.js',
        },
    },
 
    server: {
        strictPort: true,
        port: 3000,
    },
 
    resolve: {
        alias: {
            '@': '/js',
        },
    },
 
  optimizeDeps: {
    include: [
      'vue',
    //   'portal-vue',
    //   '@inertiajs/inertia',
    //   '@inertiajs/inertia-vue',
    //   '@inertiajs/progress',
      'axios'
    ]
  }
}