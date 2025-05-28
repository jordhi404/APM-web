import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    server: {
        host: true, // Mengizinkan akses dari IP selain localhost
        port: 5173, // Optional: pastikan sesuai dengan yang kamu pakai
        cors: {
            origin: '*', // Mengizinkan semua origin (untuk pengujian lokal)
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/qr-payment.js', 'resources/js/card-payment.js', 'resources/js/card-cancel.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
