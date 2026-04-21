<script lang="ts" setup>
    import { type MenuOption, NIcon } from "naive-ui";
    import { computed, h } from "vue";
    import { HomeOutlined, LoginOutlined, SearchOutlined, UserOutlined } from "@vicons/antd";
    import { Link, usePage } from "@inertiajs/vue3";
    import { isNonNullish } from "remeda";
    import type { UserModel } from "@/types/backend";
    import home from "@/_generated/routes/home";
    import profile from "@/_generated/routes/profile";
    import auth from "@/_generated/routes/auth";
    import explore from "@/_generated/routes/explore";
    import ai from "@/_generated/routes/ai";

    const page = usePage<{
        readonly user: UserModel;
    }>();

    const menuOptions: MenuOption[] = [
        {
            label: () =>
                h(
                    Link,
                    {
                        href: home.render().url,
                    },
                    () => "首页",
                ),
            key: home.render().url,
            icon: () => {
                return h(NIcon, {
                    component: HomeOutlined,
                });
            },
        },
        {
            label: () =>
                h(
                    Link,
                    {
                        href: explore.render().url,
                    },
                    () => "浏览",
                ),
            key: explore.render().url,
            icon: () => {
                return h(NIcon, {
                    component: SearchOutlined,
                });
            },
        },
        {
            label: "AI",
            key: "/ai",
            children: [
                {
                    label: () =>
                        h(
                            Link,
                            {
                                href: ai.imageGenerate().url,
                            },
                            () => "诗句意境图片生成",
                        ),
                    key: ai.imageGenerate().url,
                },
                {
                    label: () =>
                        h(
                            Link,
                            {
                                href: ai.couplet().url,
                            },
                            () => "对诗",
                        ),
                    key: ai.couplet().url,
                },
                {
                    label: () =>
                        h(
                            Link,
                            {
                                href: ai.suggest().url,
                            },
                            () => "写诗助手",
                        ),
                    key: ai.suggest().url,
                },
                {
                    label: () =>
                        h(
                            Link,
                            {
                                href: ai.imageToPoem().url,
                            },
                            () => "图片作诗",
                        ),
                    key: ai.imageToPoem().url,
                },
                {
                    label: () =>
                        h(
                            Link,
                            {
                                href: ai.characterTalk().url,
                            },
                            () => "古人对话",
                        ),
                    key: ai.characterTalk().url,
                },
                {
                    label: () =>
                        h(
                            Link,
                            {
                                href: ai.poeticChain().url,
                            },
                            () => "飞花令",
                        ),
                    key: ai.poeticChain().url,
                },
            ],
        },
    ];

    const authMenuOptions = computed<MenuOption[]>(() => {
        return [
            isNonNullish(page.props.user)
                ? {
                      label: () =>
                          h(
                              Link,
                              {
                                  href: profile.render().url,
                              },
                              () => page.props.user.name,
                          ),
                      key: profile.render().url,
                      icon: () => {
                          return h(NIcon, {
                              component: UserOutlined,
                          });
                      },
                  }
                : {
                      label: () =>
                          h(
                              Link,
                              {
                                  href: auth.render().url,
                              },
                              () => "登录 / 注册",
                          ),
                      key: auth.render().url,
                      icon: () => {
                          return h(NIcon, {
                              component: LoginOutlined,
                          });
                      },
                  },
        ];
    });

    const appName = import.meta.env.VITE_APP_NAME;
</script>

<template>
    <n-flex justify="space-between">
        <n-flex align="center" size="small">
            <n-text class="ml-4 text-6 fw-extrabold">{{ appName }}</n-text>
            <n-menu :options="menuOptions" :value="page.url" class="!w-fit" mode="horizontal" />
        </n-flex>

        <n-menu :options="authMenuOptions" :value="page.url" class="!w-fit" mode="horizontal" />
    </n-flex>
</template>