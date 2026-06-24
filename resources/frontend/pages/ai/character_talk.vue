<script lang="ts" setup>
import {Head, useForm, usePage} from "@inertiajs/vue3";
import type {UserModel} from "@/types/backend";
import {characterTalk} from "@/_generated/routes/ai";
import {isEmptyish} from "remeda";
import Animate3D from "@/components/Animate3D.vue";
import {computed, shallowRef, watch} from "vue";

defineOptions({
    name: "AI 古人对话",
});

const page = usePage<{
    readonly badges: Record<
        string,
        {
            readonly image: string;
            readonly archived: boolean;
            readonly is_new: boolean;
        }
    >;

    readonly user: UserModel;
    readonly greeting: string;

    readonly characters: string[];
    readonly character: string;
    readonly history: string[];
}>();

const generator = useForm<{
    character: string;
    history: string[];
    input: string;
}>(characterTalk(), {
    character: "",
    history: [],
    input: "",
});

const showNewBadgesArchivedModal = shallowRef(false);

const newBadges = computed(() => {
    return Object.fromEntries(
        Object.entries(page.props.badges).filter((data) => {
            return data[1].is_new;
        }),
    );
});

watch(
    newBadges,
    (newBadgesData) => {
        if (!isEmptyish(newBadgesData)) {
            showNewBadgesArchivedModal.value = true;
        }
    },
    {
        immediate: true,
        deep: true,
    },
);

const handleCharacterClick = (character: string) => {
    generator.character = character;
};

const handleGenerate = () => {
    if (!isEmptyish(page.props.character)) {
        generator.character = page.props.character;
    }

    generator.history = page.props.history;
    generator.submit();
};
</script>

<template>
    <Head :title="$options.name"/>

    <n-modal
        v-model:show="showNewBadgesArchivedModal"
        class="md:w-1/2 mx-auto"
        preset="card"
        title="解锁了新的勋章"
    >
        <n-flex size="large">
            <template v-for="(badge, name) in newBadges">
                <Animate3D>
                    <n-flex align="center" justify="space-between" size="small" vertical>
                        <n-image :src="badge.image" class="max-w-60" preview-disabled/>
                        <n-text class="text-6 fw-bold">{{ name }}</n-text>
                    </n-flex>
                </Animate3D>
            </template>
        </n-flex>
    </n-modal>

    <n-element class="absolute top-1/2 left-1/2 -translate-1/2 min-w-1/5 max-h-1/2 overflow-y-auto">
        <n-card size="small">
            <n-flex align="center" size="small" vertical>
                <n-element class="text-center">
                    <n-text class="mb-4 text-8 fw-bold">
                        {{ page.props.greeting }}, {{ page.props.user.name }}
                    </n-text>
                </n-element>

                <template v-if="isEmptyish(page.props.character)">
                    <n-flex :wrap="false" align="center" size="small">
                        <n-text class="text-nowrap">和谁对话:</n-text>

                        <template v-for="character in page.props.characters">
                            <n-button
                                :disabled="generator.processing"
                                :type="generator.character === character ? 'success' : 'default'"
                                secondary
                                size="small"
                                @click="handleCharacterClick(character)"
                            >
                                {{ character }}
                            </n-button>
                        </template>
                    </n-flex>
                </template>

                <template v-else>
                    <n-tag size="small">人物: {{ page.props.character }}</n-tag>

                    <template v-if="!isEmptyish(page.props.history)">
                        <template v-for="(history, index) in page.props.history">
                            <n-element class="text-center">
                                <n-text :type="index % 2 === 0 ? 'info' : 'warning'" class="text-6 whitespace-pre">
                                    {{ history }}
                                </n-text>
                            </n-element>
                        </template>
                    </template>
                </template>

                <n-input v-model:value="generator.input" autosize type="textarea"/>

                <n-button
                    :disabled="generator.processing"
                    :loading="generator.processing"
                    block
                    secondary
                    @click="handleGenerate"
                >
                    交谈
                </n-button>
            </n-flex>
        </n-card>
    </n-element>
</template>
