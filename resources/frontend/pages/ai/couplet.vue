<script lang="ts" setup>
    import { Head, useForm, usePage } from "@inertiajs/vue3";
    import type { UserModel } from "@/types/backend";
    import { couplet } from "@/_generated/routes/ai";
    import { isEmptyish } from "remeda";

    defineOptions({
        name: "AI 对诗",
    });

    const page = usePage<{
        readonly user: UserModel;
        readonly greeting: string;

        readonly history: string[];
    }>();

    const generator = useForm<{
        history: string[];
        input: string;
    }>(couplet(), {
        history: [],
        input: "",
    });

    const handleGenerate = () => {
        generator.history = page.props.history;
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

                <template v-if="!isEmptyish(page.props.history)">
                    <template v-for="(history, index) in page.props.history">
                        <n-text :type="index % 2 === 0 ? 'info' : 'warning'" class="text-6">
                            {{ history }}
                        </n-text>
                    </template>
                </template>

                <n-input v-model:value="generator.input" autosize type="textarea" />

                <n-button
                    :disabled="generator.processing"
                    :loading="generator.processing"
                    block
                    secondary
                    @click="handleGenerate"
                >
                    对诗
                </n-button>
            </n-flex>
        </n-card>
    </n-element>
</template>