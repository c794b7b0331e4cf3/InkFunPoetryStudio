<script lang="ts" setup>
    import { Head, usePage } from "@inertiajs/vue3";
    import type { UserModel } from "@/types/backend";

    defineOptions({
        name: "用户",
    });

    const page = usePage<{
        readonly user: UserModel;
    }>();
</script>

<template>
    <Head :title="$options.name" />

    <n-element class="container mx-auto p-2">
        <n-card :title="page.props.user.name" size="small">
            用户 ID: {{ page.props.user.id }} <br />
            注册时间: {{ new Date(page.props.user.created_at).toLocaleString() }}
        </n-card>
    </n-element>
</template>