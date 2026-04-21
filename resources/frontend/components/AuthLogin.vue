<script lang="ts" setup>
    import { login } from "@/_generated/routes/auth";
    import { useForm } from "@inertiajs/vue3";
    import { isEmptyish } from "remeda";

    const form = useForm(login.post(), {
        name: "",
        password: "",
    });

    const handle = () => {
        form.submit();
    };
</script>

<template>
    <n-form-item
        :feedback="form.errors.name"
        :validation-status="!isEmptyish(form.errors.name) ? 'error' : undefined"
        label="用户名"
    >
        <n-input v-model:value="form.name"
    /></n-form-item>

    <n-form-item
        :feedback="form.errors.password"
        :validation-status="!isEmptyish(form.errors.password) ? 'error' : undefined"
        label="密码"
    >
        <n-input v-model:value="form.password" show-password-on="mousedown" type="password" />
    </n-form-item>

    <n-form-item :show-feedback="false">
        <n-button
            :disabled="form.processing"
            :loading="form.processing"
            block
            secondary
            @click="handle"
        >
            登录
        </n-button>
    </n-form-item>
</template>