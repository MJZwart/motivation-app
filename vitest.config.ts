import {defineConfig} from 'vite'
import Vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        Vue({
            include: [/\.vue$/, /\.md$/],
            template:{
                compilerOptions:{
                    isCustomElement:tag => ['Icon'].includes(tag),
                },
            },
        }),
    ],
    // @ts-ignore
    test: {
        root: 'resources',
        globals: true,
        environment: 'jsdom',
        include: ['../tests/vitest/**/*.spec.ts'],
        setupFiles: ['../tests/vitest/services/setup.ts'],
        coverage: {
            reportsDirectory: '../tests/vitest/coverage',
            reporter: ['lcov'],
            branches: 80,
            lines: 80,
            functions: 80,
            statements: 80,
        },
    },
})