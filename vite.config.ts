import { defineConfig } from "vite";
import Vue from "@vitejs/plugin-vue";
import { NaiveUiResolver } from "unplugin-vue-components/resolvers";
import Components from "unplugin-vue-components/vite";
import Laravel from "laravel-vite-plugin";
import UnoCSS from "unocss/vite";
import { wayfinder as WayFinder } from "@laravel/vite-plugin-wayfinder";
import { resolve } from "node:path";
import Inertia from "@inertiajs/vite";

export default defineConfig({
    build: {
        assetsInlineLimit: 0,
        sourcemap: true,
    },
    resolve: {
        alias: {
            "@": resolve(__dirname, "resources/frontend"),
        },
    },
    plugins: [
        Laravel({
            input: ["resources/frontend/main.ts"],
            refresh: true,
        }),
        Inertia(),
        UnoCSS(),
        Vue(),
        Components({
            resolvers: [NaiveUiResolver()],
        }),
        WayFinder({
            path: resolve(__dirname, "resources/frontend/_generated"),
        }),
    ],
    server: {
        cors: true,
    },
});