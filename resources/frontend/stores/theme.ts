import { darkTheme, type GlobalThemeOverrides, lightTheme, useOsTheme } from "naive-ui";
import { computed, shallowRef } from "vue";
import { defineStore } from "pinia";

export const useThemeStore = defineStore(
    "theme",
    () => {
        const mode = shallowRef<"system" | "light" | "dark">("dark");
        const osTheme = useOsTheme();

        const actualMode = computed(() => {
            return mode.value === "system" ? osTheme.value : mode.value;
        });

        const preset = computed(() => {
            switch (actualMode.value) {
                case "light":
                    return lightTheme;
                case "dark":
                    return darkTheme;
            }

            return null;
        });

        const overrides = {
            Card: {
                paddingSmall: ".6em .9em",
            },
        } satisfies GlobalThemeOverrides;

        return {
            mode,
            preset,
            overrides,
        };
    },
    {
        persist: {
            pick: ["mode"],
        },
    },
);