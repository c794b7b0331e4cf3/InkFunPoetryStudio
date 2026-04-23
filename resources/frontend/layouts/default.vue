<script lang="ts" setup>
    import WrapperLayout from "@/layouts/wrapper.vue";
    import HeaderMenu from "@/components/HeaderMenu.vue";
</script>

<template>
    <WrapperLayout>
        <n-layout :native-scrollbar="false" class="min-h-screen">
            <n-layout-header>
                <HeaderMenu />
            </n-layout-header>

            <n-layout-content>
                <slot />
            </n-layout-content>
        </n-layout>
    </WrapperLayout>
</template>