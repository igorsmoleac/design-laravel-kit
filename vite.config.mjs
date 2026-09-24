import { defineConfig } from 'vite';
import { resolve } from 'path';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    build: {
        outDir: 'resources/dist',
        emptyOutDir: true,
        rollupOptions: {
            input: {
                'design-laravel-kit': resolve(__dirname, 'resources/css/app.css'),
            },
            output: {
                entryFileNames: 'js/[name].js',
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'css/design-laravel-kit[extname]';
                    }
                    return 'assets/[name][extname]';
                },
            },
        },
    },
    plugins: [
        viteStaticCopy({
            targets: [
                { src: 'node_modules/bootstrap-italia/dist/svg/sprites.svg', dest: 'svg' },
                {
                    src: 'node_modules/bootstrap-italia/dist/js/bootstrap-italia.bundle.min.js',
                    dest: 'js',
                    rename: 'design-laravel-kit.js',
                },
                { src: 'node_modules/bootstrap-italia/dist/fonts/*', dest: 'fonts' },
            ],
        }),
    ],
});
