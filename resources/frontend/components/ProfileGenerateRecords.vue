<script lang="ts" setup>
    import { router, useHttp, usePage } from "@inertiajs/vue3";
    import type { Paginated, PoemImageResource } from "@/types/backend";
    import { h, onMounted, shallowRef, watch } from "vue";
    import { isNonNullish, isNullish } from "remeda";
    import { type DataTableColumn, NButton, NFlex, NIcon, NImage, NPopconfirm } from "naive-ui";
    import Poem from "@/components/Poem.vue";
    import { render } from "@/_generated/routes/poems";
    import poem_images from "@/_generated/routes/poem_images";
    import { DeleteOutlined } from "@vicons/antd";

    const page = usePage<{
        readonly generated?: Paginated<PoemImageResource[]>;
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
            title: "来源",
            key: "poem.source_type.label",
        },
        {
            title: "收藏量",
            key: "likes_count",
        },
        {
            title: "创作时间",
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
                            NPopconfirm,
                            {
                                onPositiveClick: () => {
                                    useHttp().post(
                                        poem_images.delete({
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
                                trigger: () =>
                                    h(
                                        NButton,
                                        {
                                            type: "error",
                                            secondary: true,
                                            size: "small",
                                        },
                                        {
                                            icon: () =>
                                                h(NIcon, {
                                                    component: DeleteOutlined,
                                                }),
                                            default: () => "删除",
                                        },
                                    ),
                                default: () => "确认删除吗",
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
                generated_page: newPage,
            },
            only: ["generated"],
        });
    };

    onMounted(() => {
        handleLoad(currentPage.value);
    });
</script>

<template>
    <n-card size="small">
        <template v-if="isNonNullish(page.props.generated)">
            <n-flex size="small" vertical>
                <n-data-table :columns="columns" :data="page.props.generated.data" />

                <template v-if="page.props.generated.meta.last_page > 1">
                    <n-pagination
                        v-model:page="currentPage"
                        :page-count="page.props.generated.meta.last_page"
                    />
                </template>
            </n-flex>
        </template>

        <template v-else>
            <n-empty />
        </template>
    </n-card>
</template>