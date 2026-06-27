<script lang="ts" setup>
    import { Head, useForm, usePage } from "@inertiajs/vue3";
    import type { UserModel } from "@/types/backend";
    import { poeticChain } from "@/_generated/routes/ai";
    import { isEmptyish } from "remeda";
    import { ref } from "vue";

    defineOptions({
        name: "AI 古人对话",
    });

    const page = usePage<{
        readonly user: UserModel;
        readonly greeting: string;

        readonly keywords: string[];
        readonly history: string[];
    }>();

    const keywords = ref("");

    const generator = useForm<{
        keywords: string[];
        history: string[];
        input: string;
    }>(poeticChain(), {
        keywords: [],
        history: [],
        input: "",
    });

    const handleKeywordSave = () => {
        generator.input = "";
        generator.keywords = keywords.value.split(" ");
        generator.submit();
    };

    const handleGenerate = () => {
        if (!isEmptyish(page.props.keywords)) {
            generator.keywords = page.props.keywords;
        }

        generator.history = page.props.history;
        generator.submit();
    };
</script>

<template>
    <Head :title="$options.name" />

    <n-element
        class="absolute top-1/2 left-1/2 -translate-1/2 min-w-1/5 max-w-1/2 max-h-1/2 overflow-y-auto"
    >
        <n-card size="small">
            <n-flex align="center" size="small" vertical>
                <n-element class="text-center">
                    <n-text class="mb-4 text-8 fw-bold">
                        {{ page.props.greeting }}, {{ page.props.user.name }}
                    </n-text>
                </n-element>

                <template v-if="isEmptyish(page.props.keywords)">
                    <n-input
                        :key="false"
                        v-model:value="keywords"
                        placeholder="关键词 (用空格分割)"
                    />

                    <n-button
                        :disabled="isEmptyish(keywords) || generator.processing"
                        :loading="generator.processing"
                        block
                        secondary
                        @click="handleKeywordSave"
                    >
                        提交
                    </n-button>
                </template>

                <template v-else>
                    <n-tag size="small">关键词: {{ keywords }}</n-tag>

                    <template v-if="!isEmptyish(page.props.history)">
                        <template v-for="(history, index) in page.props.history">
                            <n-element class="text-center">
                                <n-text
                                    :type="index % 2 === 0 ? 'info' : 'warning'"
                                    class="text-(6 wrap) whitespace-pre"
                                >
                                    {{ history }}
                                </n-text>
                            </n-element>
                        </template>
                    </template>

                    <n-input :key="true" v-model:value="generator.input" autosize type="textarea" />

                    <n-button
                        :disabled="generator.processing"
                        :loading="generator.processing"
                        block
                        secondary
                        @click="handleGenerate"
                    >
                        接
                    </n-button>
                </template>
            </n-flex>
        </n-card>
    </n-element>
</template>