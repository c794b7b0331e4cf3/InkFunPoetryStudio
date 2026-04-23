<script lang="ts" setup>
    import { isEmptyish, isNullish } from "remeda";
    import type { PoemResource } from "@/types/backend";

    withDefaults(
        defineProps<{
            readonly poem: PoemResource;
            readonly vertical?: boolean;

            readonly titleOnly?: boolean;
        }>(),
        {
            titleOnly: false,
        },
    );
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

        <template v-if="!titleOnly || isNullish(poem.title)">
            <n-text
                :style="{
                    writingMode:
                        vertical && poem.content.includes('\n') ? 'vertical-rl' : 'horizontal-tb',
                }"
                class="whitespace-pre"
            >
                {{ poem.content }}
            </n-text>
        </template>
    </n-flex>
</template>