<script lang="ts" setup>
    import VueBitsLiquidEther from "@/components/vue-bits/LiquidEther.vue";
    import DefaultLayout from "@/layouts/default.vue";
</script>

<template>
    <DefaultLayout>
        <n-element class="relative h-screen bg-[#F7F3E8]">
            <VueBitsLiquidEther :colors="['#050505', '#1A1A1A', '#222222']" class="opacity-50" />

            <slot />
        </n-element>
    </DefaultLayout>
</template>