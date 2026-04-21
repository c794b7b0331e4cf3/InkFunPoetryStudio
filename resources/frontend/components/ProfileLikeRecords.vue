<script lang="ts" setup>
    import { router, useHttp, usePage } from "@inertiajs/vue3";
    import type { Paginated, PoemImageResource } from "@/types/backend";
    import { h, onMounted, shallowRef, watch } from "vue";
    import { isNonNullish, isNullish } from "remeda";
    import { type DataTableColumn, NButton, NFlex, NIcon, NImage } from "naive-ui";
    import { like } from "@/_generated/routes/poem_images";
    import { DeleteOutlined } from "@vicons/antd";
    import Poem from "@/components/Poem.vue";
    import { render } from "@/_generated/routes/poems";

    const page = usePage<{
        readonly likes?: Paginated<PoemImageResource[]>;
    }>();

    const currentPage = shallowRef(1);

    const columns: DataTableColumn<PoemImageResource>[] = [
        {
            title: "ID",
            key: "id",
        },
        {
            title: "图片",
            key: "file",
            render: (item) => {
                if (isNullish(item.file)) {
                    return "-";
                }

                return h(NImage, {
                    class: "max-w-60",
                    src: item.file.download_url,
                });
            },
        },
        {
            title: "诗词",
            key: "poem",
            render: (item) => {
                if (isNullish(item.poem)) {
                    return "-";
                }

                return h(Poem, {
                    poem: item.poem,
                    onClick: () => {
                        router.visit(
                            render({
                                id: item.poem!.id,
                            }),
                        );
                    },
                });
            },
        },
        {
            title: "收藏时间",
            key: "created_at",
            render: (item) => {
                return new Date(item.created_at).toLocaleString();
            },
        },
        {
            title: "操作",
            key: "actions",
            render: (item) => {
                return h(
                    NFlex,
                    {
                        size: "small",
                    },
                    () => [
                        h(
                            NButton,
                            {
                                type: "error",
                                secondary: true,
                                size: "small",
                                onClick: () => {
                                    useHttp().post(
                                        like({
                                            id: item.id,
                                        }).url,
                                        {
                                            onSuccess: (_response, httpResponse) => {
                                                if (httpResponse.status === 204) {
                                                    handleLoad(currentPage.value);
                                                }

                                                router.reload({
                                                    only: ["messages"],
                                                });
                                            },
                                        },
                                    );
                                },
                            },
                            {
                                icon: () =>
                                    h(NIcon, {
                                        component: DeleteOutlined,
                                    }),
                                default: () => "取消收藏",
                            },
                        ),
                    ],
                );
            },
        },
    ];

    watch(currentPage, (newPage) => {
        handleLoad(newPage);
    });

    const handleLoad = (newPage: number) => {
        router.reload({
            data: {
                likes_page: newPage,
            },
            only: ["likes"],
        });
    };

    onMounted(() => {
        handleLoad(currentPage.value);
    });
</script>

<template>
    <n-card size="small">
        <template v-if="isNonNullish(page.props.likes)">
            <n-flex size="small" vertical>
                <n-data-table :columns="columns" :data="page.props.likes.data" />

                <n-pagination
                    v-model:value="currentPage"
                    :page-count="page.props.likes.meta.last_page"
                />
            </n-flex>
        </template>

        <template v-else>
            <n-empty />
        </template>
    </n-card>
</template>