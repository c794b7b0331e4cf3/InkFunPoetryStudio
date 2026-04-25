<script lang="ts" setup>
    import { Head, router, useHttp, usePage } from "@inertiajs/vue3";
    import type { Paginated, PoemImageResource, PoemResource, UserResource } from "@/types/backend";
    import { isNonNullish } from "remeda";
    import { h, onMounted, shallowRef, watch } from "vue";
    import {
        type DataTableColumn,
        type GlobalThemeOverrides,
        lightTheme,
        NText,
        useMessage,
    } from "naive-ui";
    import users from "@/_generated/routes/users";
    import suggestion from "@/_generated/routes/suggestion";
    import PoemImage from "@/components/PoemImage.vue";

    defineOptions({
        name: "探索",
    });

    const page = usePage<{
        readonly poems?: Paginated<PoemResource[]>;
        readonly poemImages?: Paginated<PoemImageResource[]>;
        readonly leaderboard?: Paginated<UserResource[]>;
    }>();

    const poemsPage = shallowRef(1);
    const poemImagesPage = shallowRef(1);
    const leaderboardPage = shallowRef(1);

    const themeOverrides: GlobalThemeOverrides = {
        Card: {
            paddingSmall: ".3em .6em",
        },
    };

    watch(
        [poemsPage, poemImagesPage, leaderboardPage],
        ([newPoemsPage, newPoemImagesPage, newLeaderboardPage]) => {
            handleLoad(newPoemsPage, newPoemImagesPage, newLeaderboardPage);
        },
    );

    const handleLoad = (
        newPoemsPage: number,
        newPoemImagesPage: number,
        newLeaderboardPage: number,
    ) => {
        router.reload({
            data: {
                poems_page: newPoemsPage,
                poem_images_page: newPoemImagesPage,
                leaderboard_page: newLeaderboardPage,
            },
            only: ["poems", "poemImages", "leaderboard"],
        });
    };

    const leaderboardColumns: DataTableColumn<UserResource>[] = [
        {
            title: "排名",
            key: "top",
            render: (_data, i) => {
                return i + 1;
            },
        },
        {
            title: "用户名",
            key: "name",
            render: (item) => {
                return h(
                    NText,
                    {
                        type: "info",
                        class: "fw-bold hover:cursor-pointer",
                        onClick: () => {
                            router.visit(
                                users.render({
                                    id: item.id,
                                }),
                            );
                        },
                    },
                    () => item.name,
                );
            },
        },
        {
            title: "积分",
            key: "poem_images_count",
        },
    ];

    const suggest = useHttp({
        content: "",
    });

    const message = useMessage();

    const submitSuggest = () => {
        suggest.post(suggestion.submit().url, {
            onSuccess: (_response, request) => {
                if (request.status === 201) {
                    message.success("提交成功 !");
                }
            },
        });
    };

    onMounted(() => {
        handleLoad(poemsPage.value, poemImagesPage.value, leaderboardPage.value);
    });
</script>

<template>
    <Head :title="$options.name" />

    <n-config-provider :theme="lightTheme" :theme-overrides="themeOverrides" abstract>
        <n-element class="absolute top-0 left-0 size-full">
            <n-image
                :img-props="{ class: 'size-full' }"
                class="size-full opacity-95"
                object-fit="fill"
                preview-disabled
                src="/explore_background.webp"
            />
        </n-element>

        <n-element class="container mx-auto p-2 relative z-1">
            <n-grid :cols="5" :x-gap="10" :y-gap="10">
                <n-grid-item :span="3">
                    <n-flex :size="0" vertical>
                        <template v-if="isNonNullish(page.props.poems)">
                            <n-flex class="mt-4" size="small" vertical>
                                <n-text class="text-(10 !black) mb-4 fw-bold">最新诗词</n-text>

                                <n-flex size="small" vertical>
                                    <template v-for="poem in page.props.poems.data" :key="poem.id">
                                        <n-card size="small">
                                            <n-carousel autoplay draggable show-arrow>
                                                <template
                                                    v-for="image in poem.images"
                                                    :key="image.id"
                                                >
                                                    <PoemImage
                                                        :item="image"
                                                        class="min-h-120"
                                                        same-compare-text="查看详情"
                                                        title-only
                                                    />
                                                </template>
                                            </n-carousel>
                                        </n-card>
                                    </template>
                                </n-flex>

                                <template v-if="page.props.poems.meta.last_page > 1">
                                    <n-card size="small">
                                        <n-pagination
                                            v-model:page="poemsPage"
                                            :page-count="page.props.poems.meta.last_page"
                                        />
                                    </n-card>
                                </template>
                            </n-flex>
                        </template>

                        <template v-if="isNonNullish(page.props.poemImages)">
                            <n-flex class="mt-4" size="small" vertical>
                                <n-text class="text-(10 !black) mb-4 fw-bold">最新图片</n-text>

                                <n-flex size="small" vertical>
                                    <template
                                        v-for="image in page.props.poemImages.data"
                                        :key="image.id"
                                    >
                                        <n-card size="small">
                                            <PoemImage
                                                :item="image"
                                                class="min-h-120"
                                                same-compare-text="查看详情"
                                                title-only
                                            />
                                        </n-card>
                                    </template>
                                </n-flex>

                                <template v-if="page.props.poemImages.meta.last_page > 1">
                                    <n-card size="small">
                                        <n-pagination
                                            v-model:page="poemImagesPage"
                                            :page-count="page.props.poemImages.meta.last_page"
                                        />
                                    </n-card>
                                </template>
                            </n-flex>
                        </template>
                    </n-flex>
                </n-grid-item>

                <n-grid-item :span="2">
                    <n-flex vertical size="small">
                        <template v-if="isNonNullish(page.props.leaderboard)">
                            <n-flex class="mt-4" size="small" vertical>
                                <n-text class="text-(10 !black) mb-4 fw-bold">排行榜</n-text>

                                <n-data-table
                                    :columns="leaderboardColumns"
                                    :data="page.props.leaderboard.data"
                                />

                                <template v-if="page.props.leaderboard.meta.last_page > 1">
                                    <n-card size="small">
                                        <n-pagination
                                            v-model:page="leaderboardPage"
                                            :page-count="page.props.leaderboard.meta.last_page"
                                        />
                                    </n-card>
                                </template>
                            </n-flex>
                        </template>

                        <n-card size="small" title="建议我们">
                            <n-flex vertical size="small">
                                <n-input v-model:value="suggest.content" type="textarea" />

                                <n-button @click="submitSuggest" secondary block>提交</n-button>
                            </n-flex>
                        </n-card>
                    </n-flex>
                </n-grid-item>
            </n-grid>
        </n-element>
    </n-config-provider>
</template>