<script lang="ts" setup>
    import { isEmptyish, isNonNullish } from "remeda";
    import ImageAnimation from "@/components/ImageAnimation.vue";
    import Poem from "@/components/Poem.vue";
    import type { PoemImageResource } from "@/types/backend";
    import { HeartOutlined } from "@vicons/antd";
    import { router, useHttp } from "@inertiajs/vue3";
    import { like } from "@/_generated/routes/poem_images";
    import users from "@/_generated/routes/users";
    import poems from "@/_generated/routes/poems";

    const props = withDefaults(
        defineProps<{
            readonly item: PoemImageResource;

            readonly sameCompareText?: string;
            readonly poemClass?: string;

            readonly showSameCompare?: boolean;
            readonly titleOnly?: boolean;
        }>(),
        {
            showSameCompare: true,
            titleOnly: false,
        },
    );

    const http = useHttp();

    const handleLike = () => {
        http.post(
            like({
                id: props.item.id,
            }).url,
            {
                onSuccess: (_response, httpResponse) => {
                    if (httpResponse.status === 201) {
                        props.item.liked = true;
                        props.item.likes_count ??= 0;
                        props.item.likes_count++;
                    }

                    if (httpResponse.status === 204) {
                        props.item.liked = false;
                        props.item.likes_count ??= 0;
                        props.item.likes_count--;
                    }

                    router.reload({
                        only: ["messages"],
                    });
                },
            },
        );
    };

    const handleUserClick = () => {
        router.visit(
            users.render({
                id: props.item.poem!.user!.id,
            }),
        );
    };

    const handleSamePoemCompareClick = () => {
        router.visit(
            poems.render({
                id: props.item.poem!.id,
            }),
        );
    };
</script>

<template>
    <n-element class="size-full relative" v-bind="$attrs">
        <template v-if="isNonNullish(item.file)">
            <ImageAnimation
                :img-props="{ class: 'size-full' }"
                :src="item.file.download_url"
                class="absolute top-0 left-0 size-full"
                object-fit="cover"
                preview-disabled
            />
        </template>

        <n-element :class="poemClass" class="absolute top-2 left-2">
            <n-flex size="small" vertical>
                <n-card
                    class="w-fit opacity-80 transition-(opacity ease-in-out duration-500) hover:opacity-100"
                    size="small"
                >
                    <n-flex vertical>
                        <template v-if="isNonNullish(item.poem) && isNonNullish(item.poem.user)">
                            <n-flex :wrap="false" size="small">
                                <template v-if="isNonNullish(item.poem)">
                                    <n-tag class="w-fit" size="small">
                                        {{ item.poem.source_type.label }}
                                    </n-tag>
                                </template>

                                <n-flex align="center" size="small">
                                    <n-text>由用户</n-text>

                                    <n-text
                                        class="fw-bold hover:cursor-pointer"
                                        type="info"
                                        @click="handleUserClick"
                                        >{{ item.poem.user.name }}
                                    </n-text>

                                    <n-text>生成</n-text>
                                </n-flex>
                            </n-flex>
                        </template>

                        <n-flex>
                            <template v-if="showSameCompare">
                                <n-button
                                    secondary
                                    size="small"
                                    @click="handleSamePoemCompareClick"
                                >
                                    {{ sameCompareText ?? "同诗对比" }}
                                </n-button>
                            </template>

                            <template v-if="isNonNullish(item.likes_count)">
                                <n-button
                                    :type="item.liked ? 'error' : 'default'"
                                    secondary
                                    size="small"
                                    @click="handleLike"
                                >
                                    <template #icon>
                                        <n-icon :component="HeartOutlined" />
                                    </template>

                                    {{ item.likes_count }}
                                </n-button>
                            </template>
                        </n-flex>
                    </n-flex>
                </n-card>

                <template v-if="isNonNullish(item.poem) && !isEmptyish(item.poem.tags)">
                    <n-card
                        class="w-fit opacity-80 transition-(opacity ease-in-out duration-500) hover:opacity-100"
                        size="small"
                    >
                        <n-flex align="center" size="small">
                            <template v-for="tag in item.poem.tags">
                                <n-tag size="small">{{ tag.name }}</n-tag>
                            </template>
                        </n-flex>
                    </n-card>
                </template>
            </n-flex>
        </n-element>

        <template v-if="isNonNullish(item.poem)">
            <n-element :class="poemClass" class="absolute top-2 right-2">
                <n-card
                    class="w-fit opacity-80 transition-(opacity ease-in-out duration-500) hover:opacity-100"
                    size="small"
                >
                    <Poem :title-only="titleOnly" :poem="item.poem" vertical />
                </n-card>
            </n-element>
        </template>
    </n-element>
</template>