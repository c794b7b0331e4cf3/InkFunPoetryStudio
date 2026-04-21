<script lang="ts" setup>
    import { Head, usePage } from "@inertiajs/vue3";
    import { isEmptyish, isNonNullish } from "remeda";
    import Poem from "@/components/Poem.vue";
    import type { PoemResource } from "@/types/backend";

    defineOptions({
        name: "诗词",
    });

    const page = usePage<{
        readonly poem: {
            readonly data: PoemResource;
        };
    }>();
</script>

<template>
    <Head :title="$options.name" />

    <n-element class="container mx-auto p-2">
        <n-card size="small">
            <template #header>
                <n-tag class="w-fit" size="small">
                    {{ page.props.poem.data.source_type.label }}
                </n-tag>
            </template>

            <Poem :poem="page.props.poem.data" />

            <template v-if="!isEmptyish(page.props.poem.data.tags)" #footer>
                <n-flex align="center" size="small">
                    <n-text :depth="3">标签</n-text>

                    <template v-for="tag in page.props.poem.data.tags">
                        <n-tag size="small">{{ tag.name }}</n-tag>
                    </template>
                </n-flex>
            </template>
        </n-card>

        <n-flex align="center" size="small">
            <template v-for="image in page.props.poem.data.images">
                <template v-if="isNonNullish(image.file)">
                    <n-image :src="image.file.download_url" class="max-w-90" />
                </template>
            </template>
        </n-flex>
    </n-element>
</template>