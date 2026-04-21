<script lang="ts" setup>
    import { shallowRef } from "vue";

    defineOptions({
        inheritAttrs: false,
    });

    const loaded = shallowRef(false);

    const handleLoad = () => {
        setTimeout(() => {
            loaded.value = true;
        }, 200);
    };
</script>

<template>
    <n-image
        :class="[loaded ? 'transform-(scale-y-100)' : 'transform-(scale-y-0)']"
        class="transition-(transform duration-500 ease-in-out)"
        v-bind="$attrs"
        @load="handleLoad"
    />
</template>