<script lang="ts" setup>
    import { watch } from "vue";
    import { usePage } from "@inertiajs/vue3";
    import { isNonNullish } from "remeda";
    import { useMessage } from "naive-ui";

    const page = usePage<{
        readonly messages: {
            readonly type: keyof typeof message;
            readonly content: string;
        }[];
    }>();

    const message = useMessage();

    watch(
        () => page.props.messages,
        (newMessages) => {
            newMessages.forEach((newMessage) => {
                if (isNonNullish(newMessage.type) && isNonNullish(message[newMessage.type])) {
                    message[newMessage.type](newMessage.content);
                } else {
                    message.create(newMessage.content);
                }
            });
        },
        {
            immediate: true,
        },
    );
</script>

<template />