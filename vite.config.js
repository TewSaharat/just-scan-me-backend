import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build', // กำหนด output ไปที่ public/build
        emptyOutDir: true,      // ล้างไฟล์เก่าก่อน build ใหม่
    }
});
