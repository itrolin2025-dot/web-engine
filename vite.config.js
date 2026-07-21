import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css-/app.css', 'resources/js/app.js', 'resources/css/vertical-layout-light/style.css',
            'resources/vendors/feather/feather.css',
            'resources/vendors/ti-icons/css/themify-icons.css',
            'resources/vendors/css/vendor.bundle.base.css',
            'resources/vendors/datatables.net-bs4/dataTables.bootstrap4.css',
            'resources/vendors/js/select.dataTables.min.css',
         ],
            refresh: true,
        }),
    ],
});
