import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        react({
            babel: {
                plugins: [],
            },
        }),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/admin/app.js',
                'resources/js/admin/form_list.js',
                'resources/js/form/app.js',
                'resources/js/form/type_a.js',
                'resources/js/form/type_b.js',
                'resources/js/form/type_c.js',
                'resources/js/form/type_d.js',
                'resources/js/user/app.js',
                'resources/js/user/form/analytics/analytics.jsx',
                'resources/js/user/form/form_application.js',
                'resources/js/user/form/form_list.js',
                'resources/js/user/form/item_setting_react/ItemSettingReact.jsx',
                'resources/js/user/form/mail_setting.js',
                'resources/js/user/form/message_setting.js',
                'resources/js/user/form/register_item_setting.js',
                'resources/js/user/form/sample_mail.js',
                'resources/scss/user/form/item_setting.scss',
                'resources/scss/welcome.scss',
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
