import "virtual:uno.css";
import "@/styles/global.scss";

import { createInertiaApp } from "@inertiajs/vue3";
import { isEmptyish, isNullish } from "remeda";
import { createPinia } from "pinia";
import { createPersistedState } from "pinia-plugin-persistedstate";
import DefaultLayout from "@/layouts/default.vue";
import AiLayout from "@/layouts/ai.vue";
import { createApp, h } from "vue";

(async () => {
    const appName = import.meta.env.VITE_APP_NAME;

    await createInertiaApp({
        title: (name) => {
            if (!isEmptyish(name)) {
                if (isEmptyish(appName)) {
                    return name;
                }

                return `${name} | ${appName}`;
            }

            return appName;
        },
        layout: (name) => {
            if (name.startsWith("ai/")) {
                return AiLayout;
            }

            return DefaultLayout;
        },
        setup({ el, App, props, plugin }) {
            if (isNullish(el)) {
                return;
            }

            const app = createApp({
                render: () => h(App, props),
            });

            const pinia = createPinia();
            const piniaPersistedState = createPersistedState();

            pinia.use(piniaPersistedState);

            app.use(pinia);
            app.use(plugin);

            app.mount(el);
        },
    });
})();