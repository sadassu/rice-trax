import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import vue from "@vitejs/plugin-vue";
import { resolve } from "path";

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],

    build: {
        chunkSizeWarningLimit: 1500, // ⚙️ Set limit to 1.5 MB
    },

    resolve: {
        alias: {
            "@img": resolve(__dirname, "resources/js/assets/img"),
        },
    },

    server: {
        cors: true,
        origin: "http://127.0.0.1:5173", // or http://localhost:5173
    },
    // server: {
    //     host: '0.0.0.0',
    //     port: 5173,
    //     strictPort: true,
    //     hmr: {
    //         host: '192.168.1.42',
    //     },
    // },
});
