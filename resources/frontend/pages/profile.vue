<script lang="ts" setup>
    import { Head, router, usePage } from "@inertiajs/vue3";
    import type { UserModel } from "@/types/backend";
    import { LogoutOutlined } from "@vicons/antd";
    import { logout } from "@/_generated/routes/auth";
    import ProfileGenerateRecords from "@/components/ProfileGenerateRecords.vue";
    import ProfileLikeRecords from "@/components/ProfileLikeRecords.vue";
    import summarize from "@/_generated/routes/profile/summarize";
    import ProfileHistoryRecords from "@/components/ProfileHistoryRecords.vue";
    import ProfilePoemsRecords from "@/components/ProfilePoemsRecords.vue";
    import Animate3D from "@/components/Animate3D.vue";

    defineOptions({
        name: "资料",
    });

    const page = usePage<{
        readonly user: UserModel;
        readonly badges: Record<
            string,
            {
                readonly image: string;
                readonly archived: boolean;
            }
        >;
    }>();

    const handleLogout = () => {
        router.visit(logout().url);
    };

    const handleSummarizeClick = () => {
        router.visit(summarize.render().url);
    };
</script>

<template>
    <Head :title="$options.name" />

    <n-element class="container mx-auto p-2">
        <n-flex size="small" vertical>
            <n-button block secondary type="error" @click="handleLogout">
                <template #icon>
                    <n-icon :component="LogoutOutlined" />
                </template>

                登出
            </n-button>

            <n-card :title="page.props.user.name" size="small">
                用户 ID: {{ page.props.user.id }} <br />
                注册时间: {{ new Date(page.props.user.created_at).toLocaleString() }}
            </n-card>

            <n-button secondary type="primary" @click="handleSummarizeClick">
                获取你的诗词总结
            </n-button>

            <n-tabs animated type="segment">
                <n-tab-pane display-directive="if" name="浏览记录">
                    <ProfileHistoryRecords />
                </n-tab-pane>

                <n-tab-pane display-directive="if" name="作诗记录">
                    <ProfilePoemsRecords />
                </n-tab-pane>

                <n-tab-pane display-directive="if" name="收藏记录">
                    <ProfileLikeRecords />
                </n-tab-pane>

                <n-tab-pane display-directive="if" name="生成记录">
                    <ProfileGenerateRecords />
                </n-tab-pane>
            </n-tabs>

            <n-divider class="!my-4">勋章墙</n-divider>

            <n-grid :cols="6" :x-gap="10" :y-gap="10">
                <template v-for="(badge, name) in page.props.badges">
                    <n-grid-item>
                        <Animate3D>
                            <n-flex
                                align="center"
                                class="h-full"
                                justify="space-between"
                                size="small"
                                vertical
                            >
                                <n-image
                                    :class="{ grayscale: !badge.archived }"
                                    :src="badge.image"
                                    class="size-fit"
                                    preview-disabled
                                />

                                <n-text class="text-6 fw-bold">{{ name }}</n-text>
                            </n-flex>
                        </Animate3D>
                    </n-grid-item>
                </template>
            </n-grid>
        </n-flex>
    </n-element>
</template>