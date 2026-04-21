<script lang="ts" setup>
    import { isEmptyish } from "remeda";
    import type { PoemResource } from "@/types/backend";

    defineProps<{
        readonly poem: PoemResource;
        readonly vertical?: boolean;
    }>();
</script>

<template>
    <n-flex :align="vertical ? 'end' : 'start'" :size="0" vertical>
        <template v-if="!isEmptyish(poem.title)">
            <n-text class="fw-bold">{{ poem.title }}</n-text>
        </template>

        <template v-if="!isEmptyish(poem.dynasty) || !isEmptyish(poem.author)">
            <n-text class="mb-4">
                <template v-if="!isEmptyish(poem.dynasty)"> [{{ poem.dynasty }}] </template>

                <template v-if="!isEmptyish(poem.author)">
                    {{ poem.author }}
                </template>
            </n-text>
        </template>

        <n-text
            :style="{
                writingMode:
                    vertical && poem.content.includes('\n') ? 'vertical-rl' : 'horizontal-tb',
            }"
            class="whitespace-pre"
        >
            {{ poem.content }}
        </n-text>
    </n-flex>
</template>