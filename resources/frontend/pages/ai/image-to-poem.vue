<script lang="ts" setup>
    import { Head, useForm, usePage } from "@inertiajs/vue3";
    import type { PoemImageResource, UserModel } from "@/types/backend";
    import { imageToPoem } from "@/_generated/routes/ai";
    import PoemImage from "@/components/PoemImage.vue";
    import { isNonNullish } from "remeda";
    import FadeTransition from "@/components/FadeTransition.vue";
    import type { UploadFileInfo } from "naive-ui";

    defineOptions({
        name: "AI 图生古诗",
    });

    const page = usePage<{
        readonly user: UserModel;
        readonly greeting: string;

        readonly generated?: {
            readonly data: PoemImageResource;
        };
    }>();

    const generator = useForm<{
        input: File | null;
    }>(imageToPoem(), {
        input: null,
    });

    const handleFileListUpdate = (newFileList: UploadFileInfo[]) => {
        if (newFileList.length === 1 && isNonNullish(newFileList[0].file)) {
            generator.input = newFileList[0].file;
        }
    };

    const handleGenerate = () => {
        generator.submit();
    };
</script>

<template>
    <Head :title="$options.name" />

    <FadeTransition>
        <template v-if="isNonNullish(page.props.generated)">
            <n-element class="absolute top-0 left-0 size-full">
                <PoemImage
                    :key="page.props.generated.data.id"
                    :item="page.props.generated.data"
                    poem-class="text-6"
                />
            </n-element>
        </template>
    </FadeTransition>

    <n-element
        class="absolute top-1/2 left-1/2 -translate-1/2 min-w-1/5 max-w-1/2 max-h-1/2 overflow-y-auto"
    >
        <n-card
            :class="{ 'opacity-50': isNonNullish(page.props.generated) }"
            class="transition-(opacity duration-500 ease-in-out) hover:opacity-100"
            size="small"
        >
            <n-flex align="center" size="small" vertical>
                <n-element class="text-center">
                    <n-text class="text-8 fw-bold">
                        {{ page.props.greeting }}, {{ page.props.user.name }}
                    </n-text>
                </n-element>

                <n-upload
                    :max="1"
                    accept="image/*"
                    directory-dnd
                    list-type="image"
                    @update:file-list="handleFileListUpdate"
                >
                    <n-upload-dragger>
                        <n-text> 点击或者拖动图片到该区域来上传 </n-text>
                    </n-upload-dragger>
                </n-upload>

                <n-button
                    :disabled="generator.processing"
                    :loading="generator.processing"
                    block
                    secondary
                    @click="handleGenerate"
                >
                    生成
                </n-button>
            </n-flex>
        </n-card>
    </n-element>
</template>