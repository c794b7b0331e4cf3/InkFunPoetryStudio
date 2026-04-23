<script lang="ts" setup>
    import { Head, usePage } from "@inertiajs/vue3";
    import type { PoemResource } from "@/types/backend";
    import PoemImage from "@/components/PoemImage.vue";
    import { onMounted, useTemplateRef } from "vue";

    defineOptions({
        name: "诗词",
    });

    const page = usePage<{
        readonly poem: {
            readonly data: PoemResource;
        };
    }>();

    const container = useTemplateRef("container");

    onMounted(() => {
        container.value?.scrollIntoView({
            behavior: "smooth",
        });
    });
</script>

<template>
    <Head :title="$options.name" />

    <div ref="container">
        <n-carousel autoplay class="h-screen" direction="vertical" draggable show-arrow>
            <template v-for="item in page.props.poem.data.images" :key="item.id">
                <PoemImage :item="item" :show-same-compare="false" poem-class="text-6" />
            </template>
        </n-carousel>
    </div>
</template>