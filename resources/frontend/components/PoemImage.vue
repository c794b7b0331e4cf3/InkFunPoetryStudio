<script lang="ts" setup>
    import { isEmptyish, isNonNullish } from "remeda";
    import ImageAnimation from "@/components/ImageAnimation.vue";
    import Poem from "@/components/Poem.vue";
    import type { PoemImageCommentResource, PoemImageResource } from "@/types/backend";
    import { CommentOutlined, HeartOutlined } from "@vicons/antd";
    import { router, useHttp } from "@inertiajs/vue3";
    import { comments, like } from "@/_generated/routes/poem_images";
    import poemImagesComments from "@/_generated/routes/poem_images/comments";
    import users from "@/_generated/routes/users";
    import poems from "@/_generated/routes/poems";
    import { shallowRef } from "vue";
    import { useMessage } from "naive-ui";
    import Comment from "@/components/Comment.vue";

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

    const currentComments = shallowRef<PoemImageCommentResource[]>([]);

    const commentLoader = useHttp<
        {
            id: number;
        },
        {
            readonly data: PoemImageCommentResource[];
        }
    >({
        id: -1,
    });

    const showComments = shallowRef(false);

    const handleComment = (id: number) => {
        commentLoader.id = id;

        commentLoader.get(
            comments.get({
                id,
            }).url,
            {
                onSuccess: (response) => {
                    currentComments.value = response.data;
                    showComments.value = true;
                },
            },
        );
    };

    const commentSender = useHttp({
        content: "",
    });

    const message = useMessage();

    const handleSendComment = () => {
        commentSender.post(
            poemImagesComments.send({
                id: commentLoader.id,
            }).url,
            {
                onSuccess: (_response, request) => {
                    if (request.status === 201) {
                        message.success("发送成功");
                    }

                    props.item.comments_count ??= 0;
                    props.item.comments_count++;

                    handleComment(commentLoader.id);
                },
            },
        );
    };

    const handleCommentDeleted = () => {
        props.item.comments_count ??= 1;
        props.item.comments_count--;

        handleComment(commentLoader.id);
    };
</script>

<template>
    <n-element class="size-full relative" v-bind="$attrs">
        <n-modal
            v-model:show="showComments"
            class="p-2 md:w-1/2"
            preset="card"
            size="small"
            title="评论"
        >
            <n-flex size="small" vertical>
                <template v-if="!isEmptyish(currentComments)">
                    <n-list bordered hoverable>
                        <template v-for="comment in currentComments">
                            <n-list-item>
                                <Comment @delete="handleCommentDeleted" :comment="comment" />
                            </n-list-item>
                        </template>
                    </n-list>
                </template>

                <n-input v-model:value="commentSender.content" autosize type="textarea" />
                <n-button block secondary @click="handleSendComment">发送</n-button>
            </n-flex>
        </n-modal>

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

                            <template v-if="isNonNullish(item.comments_count)">
                                <n-button
                                    :disabled="commentLoader.processing"
                                    :loading="commentLoader.processing"
                                    secondary
                                    size="small"
                                    @click="handleComment(item.id)"
                                >
                                    <template #icon>
                                        <n-icon :component="CommentOutlined" />
                                    </template>

                                    {{ item.comments_count }}
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
                    <Poem :poem="item.poem" :title-only="titleOnly" vertical />
                </n-card>
            </n-element>
        </template>
    </n-element>
</template>