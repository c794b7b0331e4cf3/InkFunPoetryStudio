<script lang="ts" setup>
    import { router, useHttp, usePage } from "@inertiajs/vue3";
    import type { Paginated, PoemResource } from "@/types/backend";
    import { h, onMounted, shallowRef, watch } from "vue";
    import { isNonNullish, isNullish } from "remeda";
    import { type DataTableColumn, NButton, NFlex, NIcon, NPopconfirm } from "naive-ui";
    import Poem from "@/components/Poem.vue";
    import poems, { edit, render } from "@/_generated/routes/poems";
    import { DeleteOutlined, EditOutlined } from "@vicons/antd";

    const page = usePage<{
        readonly poems?: Paginated<PoemResource[]>;
    }>();

    const currentPage = shallowRef(1);

    const columns: DataTableColumn<PoemResource>[] = [
        {
            title: "ID",
            key: "id",
        },
        {
            title: "诗词",
            key: "poem",
            render: (item) => {
                if (isNullish(item)) {
                    return "-";
                }

                return h(Poem, {
                    poem: item,
                    onClick: () => {
                        router.visit(
                            render({
                                id: item.id,
                            }),
                        );
                    },
                });
            },
        },
        {
            title: "来源",
            key: "source_type.label",
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
                            NButton,
                            {
                                secondary: true,
                                size: "small",
                                onClick: () => {
                                    router.visit(
                                        edit.url({
                                            id: item.id,
                                        }),
                                    );
                                },
                            },
                            {
                                icon: () =>
                                    h(NIcon, {
                                        component: EditOutlined,
                                    }),
                                default: () => "编辑",
                            },
                        ),
                        h(
                            NPopconfirm,
                            {
                                onPositiveClick: () => {
                                    useHttp().post(
                                        poems.delete({
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
                poems_page: newPage,
            },
            only: ["poems"],
        });
    };

    onMounted(() => {
        handleLoad(currentPage.value);
    });
</script>

<template>
    <n-card size="small">
        <template v-if="isNonNullish(page.props.poems)">
            <n-flex size="small" vertical>
                <n-data-table :columns="columns" :data="page.props.poems.data" />

                <template v-if="page.props.poems.meta.last_page > 1">
                    <n-pagination
                        v-model:value="currentPage"
                        :page-count="page.props.poems.meta.last_page"
                    />
                </template>
            </n-flex>
        </template>

        <template v-else>
            <n-empty />
        </template>
    </n-card>
</template>