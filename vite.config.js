import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/admin/app.js',
                'resources/js/admin/form_list.js',
                'resources/js/form/app.js',
                'resources/js/owner/app.js',
                'resources/js/owner/form/form_application.js',
                'resources/js/owner/form/form_list.js',
                'resources/js/owner/form/item_setting.js',
                'resources/js/owner/form/register_item_setting.js',
                'resources/js/owner/form/mail_setting.js',
                'resources/js/owner/form/message_setting.js',
            ],
            refresh: [
                'resources/views/**',
                'routes/**',
                'app/Http/Controllers/**',
            ],
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
            port: 5173,
        },
        watch: {
            usePolling: true,
        },
    },
    resolve: {
        alias: {
            '@': '/resources',
        },
    },
});
