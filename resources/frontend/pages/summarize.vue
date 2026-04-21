<script lang="ts" setup>
    import { Head, usePage } from "@inertiajs/vue3";
    import markdownit from "markdown-it";
    import { computed } from "vue";

    defineOptions({
        name: "总结",
    });

    const page = usePage<{
        readonly generated: {
            readonly summarize: string;
        };
    }>();

    const md = markdownit();
    const result = computed(() => {
        return md.render(page.props.generated.summarize);
    });
</script>

<template>
    <Head :title="$options.name" />

    <n-element class="container mx-auto p-2">
        <n-card size="small" title="AI 总结">
            <n-text class="text-wrap whitespace-pre" type="info" v-html="result" />
        </n-card>
    </n-element>
</template>