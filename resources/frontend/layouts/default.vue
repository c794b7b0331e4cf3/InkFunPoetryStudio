<script lang="ts" setup>
    import WrapperLayout from "@/layouts/wrapper.vue";
    import HeaderMenu from "@/components/HeaderMenu.vue";
</script>

<template>
    <WrapperLayout>
        <n-layout position="absolute">
            <n-layout-header>
                <HeaderMenu />
            </n-layout-header>

            <n-layout-content>
                <slot />
            </n-layout-content>
        </n-layout>
    </WrapperLayout>
</template>