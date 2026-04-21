<script lang="ts" setup>
    import { Head, useForm, usePage } from "@inertiajs/vue3";
    import type { PoemResource } from "@/types/backend";
    import { isEmptyish } from "remeda";
    import { edit } from "@/_generated/routes/poems";

    defineOptions({
        name: "编辑诗句",
    });

    const page = usePage<{
        readonly poem: {
            readonly data: PoemResource;
        };
    }>();

    const form = useForm(
        edit.post({
            id: page.props.poem.data.id,
        }),
        {
            title: page.props.poem.data.title,
            author: page.props.poem.data.author,
            dynasty: page.props.poem.data.dynasty,
            content: page.props.poem.data.content,
        },
    );

    const handle = () => {
        form.submit();
    };
</script>

<template>
    <Head :title="$options.name" />

    <n-element class="container mx-auto p-2">
        <n-card size="small">
            <n-form-item
                :feedback="form.errors.title"
                :validation-status="!isEmptyish(form.errors.title) ? 'error' : undefined"
                label="标题"
            >
                <n-input v-model:value="form.title" />
            </n-form-item>

            <n-form-item
                :feedback="form.errors.dynasty"
                :validation-status="!isEmptyish(form.errors.dynasty) ? 'error' : undefined"
                label="朝代"
            >
                <n-input v-model:value="form.dynasty" />
            </n-form-item>

            <n-form-item
                :feedback="form.errors.author"
                :validation-status="!isEmptyish(form.errors.author) ? 'error' : undefined"
                label="作者"
            >
                <n-input v-model:value="form.author" />
            </n-form-item>

            <n-form-item
                :feedback="form.errors.content"
                :validation-status="!isEmptyish(form.errors.content) ? 'error' : undefined"
                label="内容"
            >
                <n-input v-model:value="form.content" autosize type="textarea" />
            </n-form-item>

            <n-form-item :show-feedback="false">
                <n-button
                    :disabled="form.processing"
                    :loading="form.processing"
                    block
                    secondary
                    @click="handle"
                >
                    保存
                </n-button>
            </n-form-item>
        </n-card>
    </n-element>
</template>