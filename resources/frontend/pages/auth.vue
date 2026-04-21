<script lang="ts" setup>
    import AuthLogin from "@/components/AuthLogin.vue";
    import AuthRegister from "@/components/AuthRegister.vue";
    import { Head } from "@inertiajs/vue3";

    defineOptions({
        name: "注册 / 登录",
    });
</script>

<template>
    <Head :title="$options.name" />

    <n-element class="container mx-auto p-2">
        <n-card size="small">
            <n-tabs animated justify-content="space-evenly" type="line">
                <n-tab-pane name="登录">
                    <AuthLogin />
                </n-tab-pane>

                <n-tab-pane name="注册">
                    <AuthRegister />
                </n-tab-pane>
            </n-tabs>
        </n-card>
    </n-element>
</template>