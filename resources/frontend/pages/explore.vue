<script lang="ts" setup>
    import { Head, router, usePage } from "@inertiajs/vue3";
    import type { Paginated, PoemImageResource, PoemResource, UserResource } from "@/types/backend";
    import Poem from "@/components/Poem.vue";
    import { isEmptyish, isNonNullish, isNullish } from "remeda";
    import { h, onMounted, shallowRef, watch } from "vue";
    import { render } from "@/_generated/routes/poems";
    import { type DataTableColumn, NText } from "naive-ui";
    import users from "@/_generated/routes/users";

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
            title: "ID",
            key: "id",
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

    const handlePoemCardClick = (item: PoemResource) => {
        router.visit(
            render({
                id: item.id,
            }),
        );
    };

    const handlePoemImageCardClick = (item: PoemImageResource) => {
        if (isNullish(item.poem)) {
            return;
        }

        router.visit(
            render({
                id: item.poem.id,
            }),
        );
    };

    onMounted(() => {
        handleLoad(poemsPage.value, poemImagesPage.value, leaderboardPage.value);
    });
</script>

<template>
    <Head :title="$options.name" />

    <n-element class="container mx-auto p-2">
        <n-flex :size="0" vertical>
            <template v-if="isNonNullish(page.props.poems)">
                <n-flex size="small" vertical>
                    <n-text class="text-10 mb-4 fw-bold">最新诗词</n-text>

                    <n-scrollbar x-scrollable>
                        <n-flex :wrap="false" class="w-max">
                            <template v-for="poem in page.props.poems.data">
                                <n-card
                                    class="w-fit"
                                    size="small"
                                    @click="handlePoemCardClick(poem)"
                                >
                                    <template #header>
                                        <n-tag class="w-fit" size="tiny">
                                            {{ poem.source_type.label }}
                                        </n-tag>
                                    </template>

                                    <Poem :poem="poem" />

                                    <template v-if="!isEmptyish(poem.tags)" #footer>
                                        <n-flex align="center" size="small">
                                            <n-text :depth="3">标签</n-text>

                                            <template v-for="tag in poem.tags">
                                                <n-tag size="small">{{ tag.name }}</n-tag>
                                            </template>
                                        </n-flex>
                                    </template>

                                    <template v-if="!isEmptyish(poem.images)" #action>
                                        <n-flex align="center" size="small">
                                            <template v-for="image in poem.images">
                                                <template v-if="isNonNullish(image.file)">
                                                    <n-image
                                                        :src="image.file.download_url"
                                                        class="max-w-60"
                                                        @click.stop
                                                    />
                                                </template>
                                            </template>
                                        </n-flex>
                                    </template>
                                </n-card>
                            </template>
                        </n-flex>
                    </n-scrollbar>

                    <n-pagination
                        v-model:value="poemsPage"
                        :page-count="page.props.poems.meta.last_page"
                    />
                </n-flex>
            </template>

            <template v-if="isNonNullish(page.props.poemImages)">
                <n-flex class="mt-8" size="small" vertical>
                    <n-text class="text-10 mb-4 fw-bold">最新图片</n-text>

                    <n-scrollbar x-scrollable>
                        <n-flex :wrap="false" class="w-max">
                            <template v-for="image in page.props.poemImages.data">
                                <n-card
                                    class="w-fit max-w-150"
                                    size="small"
                                    @click="handlePoemImageCardClick(image)"
                                >
                                    <template v-if="isNonNullish(image.file)" #cover>
                                        <n-image
                                            :src="image.file.download_url"
                                            class="w-full"
                                            @click.stop
                                        />
                                    </template>

                                    <template v-if="isNonNullish(image.poem)" #header>
                                        <n-tag class="w-fit" size="tiny">
                                            {{ image.poem.source_type.label }}
                                        </n-tag>
                                    </template>

                                    <template v-if="isNonNullish(image.poem)">
                                        <Poem :poem="image.poem" />
                                    </template>

                                    <template
                                        v-if="
                                            isNonNullish(image.poem) && !isEmptyish(image.poem.tags)
                                        "
                                        #footer
                                    >
                                        <n-flex align="center" size="small">
                                            <n-text :depth="3">标签</n-text>

                                            <template v-for="tag in image.poem.tags">
                                                <n-tag size="small">{{ tag.name }}</n-tag>
                                            </template>
                                        </n-flex>
                                    </template>
                                </n-card>
                            </template>
                        </n-flex>
                    </n-scrollbar>

                    <n-pagination
                        v-model:value="poemsPage"
                        :page-count="page.props.poemImages.meta.last_page"
                    />
                </n-flex>
            </template>

            <template v-if="isNonNullish(page.props.leaderboard)">
                <n-flex class="mt-8" size="small" vertical>
                    <n-text class="text-10 mb-4 fw-bold">排行榜</n-text>

                    <n-data-table
                        :columns="leaderboardColumns"
                        :data="page.props.leaderboard.data"
                    />

                    <n-pagination
                        v-model:value="leaderboardPage"
                        :page-count="page.props.leaderboard.meta.last_page"
                    />
                </n-flex>
            </template>
        </n-flex>
    </n-element>
</template>
