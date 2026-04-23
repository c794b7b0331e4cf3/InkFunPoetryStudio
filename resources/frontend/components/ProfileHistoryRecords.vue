<script lang="ts" setup>
    import { router, useHttp, usePage } from "@inertiajs/vue3";
    import type { Paginated, PoemResource } from "@/types/backend";
    import { h, onMounted, shallowRef, watch } from "vue";
    import { isNonNullish, isNullish } from "remeda";
    import { type DataTableColumn, NButton, NFlex, NIcon, NPopconfirm } from "naive-ui";
    import Poem from "@/components/Poem.vue";
    import { render } from "@/_generated/routes/poems";
    import poem_history_records from "@/_generated/routes/poem_history_records";
    import { DeleteOutlined } from "@vicons/antd";

    interface Item extends Paginated<{
        readonly id: number;
        readonly poem: PoemResource;
        readonly created_at: string;
    }> {
        readonly last_page: number;
    }

    const page = usePage<{
        readonly histories?: Item;
    }>();

    const currentPage = shallowRef(1);

    const columns: DataTableColumn<Item["data"]>[] = [
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
            title: "浏览时间",
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
                                        poem_history_records.delete({
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
                histories_page: newPage,
            },
            only: ["histories"],
        });
    };

    const handleClearAll = () => {
        useHttp().post(poem_history_records.clear_all().url, {
            onSuccess: (_response, httpResponse) => {
                if (httpResponse.status === 204) {
                    handleLoad(currentPage.value);
                }

                router.reload({
                    only: ["messages"],
                });
            },
        });
    };

    onMounted(() => {
        handleLoad(currentPage.value);
    });
</script>

<template>
    <n-card size="small">
        <template v-if="isNonNullish(page.props.histories)">
            <n-flex size="small" vertical>
                <n-popconfirm @positive-click="handleClearAll">
                    <template #trigger>
                        <n-button secondary type="error">清空</n-button>
                    </template>

                    确认清空吗
                </n-popconfirm>

                <n-data-table :columns="columns" :data="page.props.histories.data" />

                <template v-if="page.props.histories.last_page > 1">
                    <n-pagination
                        v-model:value="currentPage"
                        :page-count="page.props.histories.last_page"
                    />
                </template>
            </n-flex>
        </template>

        <template v-else>
            <n-empty />
        </template>
    </n-card>
</template>
