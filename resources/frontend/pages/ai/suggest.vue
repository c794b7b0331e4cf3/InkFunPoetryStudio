<script lang="ts" setup>
    import { Head, useForm, usePage } from "@inertiajs/vue3";
    import type { UserModel } from "@/types/backend";
    import { isNonNullish, isNullish } from "remeda";
    import { suggest } from "@/_generated/routes/ai";
    import markdownit from "markdown-it";
    import { computed } from "vue";

    defineOptions({
        name: "AI 写诗助手",
    });

    const page = usePage<{
        readonly user: UserModel;
        readonly greeting: string;

        readonly generated?: {
            readonly suggest: string;
        };
    }>();

    const md = markdownit();
    const result = computed(() => {
        if (isNullish(page.props.generated)) {
            return null;
        }

        return md.render(page.props.generated.suggest);
    });

    const generator = useForm(suggest(), {
        input: "",
    });

    const handleGenerate = () => {
        generator.submit();
    };
</script>

<template>
    <Head :title="$options.name" />

    <n-element class="absolute top-1/2 left-1/2 -translate-1/2 min-w-1/5 max-h-1/2 overflow-y-auto">
        <n-card size="small">
            <n-flex align="center" size="small" vertical>
                <n-element class="text-center">
                    <n-text class="mb-4 text-8 fw-bold">
                        {{ page.props.greeting }}, {{ page.props.user.name }}
                    </n-text>
                </n-element>

                <n-input v-model:value="generator.input" autosize type="textarea" />

                <template v-if="isNonNullish(result)">
                    <n-text class="text-6 whitespace-pre" type="info" v-html="result" />
                </template>

                <n-button
                    :disabled="generator.processing"
                    :loading="generator.processing"
                    block
                    secondary
                    @click="handleGenerate"
                >
                    生成修改建议
                </n-button>
            </n-flex>
        </n-card>
    </n-element>
</template>