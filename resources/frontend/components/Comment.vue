<script lang="ts" setup>
    import type { PoemImageCommentResource, UserResource } from "@/types/backend";
    import { router, useHttp, usePage } from "@inertiajs/vue3";
    import users from "@/_generated/routes/users";
    import comments from "@/_generated/routes/poem_images/comments";
    import { DeleteOutlined } from "@vicons/antd";

    const props = defineProps<{
        readonly comment: PoemImageCommentResource;
    }>();

    const emits = defineEmits<{
        (event: "delete"): void;
    }>();

    const page = usePage<{
        readonly user: UserResource;
    }>();

    const handleUserClick = (id: number) => {
        router.visit(
            users.render({
                id,
            }),
        );
    };

    const handleDelete = () => {
        useHttp().post(
            comments.delete({
                id: props.comment.id,
            }).url,
            {
                onSuccess: (_response, httpResponse) => {
                    if (httpResponse.status === 204) {
                        emits("delete");
                    }

                    router.reload({
                        only: ["messages"],
                    });
                },
            },
        );
    };
</script>

<template>
    <n-flex align="center" justify="space-between">
        <n-flex :size="0" align="center">
            <n-text
                class="text-5 fw-bold hover:cursor-pointer"
                type="info"
                @click="handleUserClick(comment.user!.id)"
            >
                {{ comment.user!.name }}
            </n-text>

            <n-text class="text-5">: {{ comment.content }}</n-text>
        </n-flex>

        <template v-if="comment.user!.id === page.props.user.id">
            <n-popconfirm @positive-click="handleDelete">
                <template #trigger>
                    <n-button secondary size="small" type="error">
                        <template #icon>
                            <n-icon :component="DeleteOutlined" />
                        </template>

                        删除
                    </n-button>
                </template>

                确认删除吗
            </n-popconfirm>
        </template>
    </n-flex>
</template>