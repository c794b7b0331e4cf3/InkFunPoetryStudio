<script lang="ts" setup>
    import { Head, usePage } from "@inertiajs/vue3";
    import type { PoemImageResource } from "@/types/backend";
    import PoemImage from "@/components/PoemImage.vue";
    import { onMounted, useTemplateRef } from "vue";

    defineOptions({
        name: "首页",
    });

    const container = useTemplateRef("container");

    const page = usePage<{
        readonly poem_images: {
            readonly data: PoemImageResource[];
        };
    }>();

    onMounted(() => {
        container.value?.scrollIntoView({
            behavior: "smooth",
        });
    });
</script>

<template>
    <Head :title="$options.name" />

    <div ref="container">
        <n-carousel autoplay class="h-screen" show-arrow>
            <template v-for="item in page.props.poem_images.data" :key="item.id">
                <PoemImage :item="item" poem-class="text-6" />
            </template>
        </n-carousel>
    </div>
</template>