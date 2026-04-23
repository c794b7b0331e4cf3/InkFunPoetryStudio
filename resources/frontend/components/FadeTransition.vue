<template>
    <Transition appear mode="out-in" name="fade">
        <slot />
    </Transition>
</template>

<style lang="scss">
    .v-fade {
        &-enter-active,
        &-leave-active {
            @apply transition-(opacity ease-linear);
        }

        &-enter-from,
        &-leave-to {
            @apply opacity-0;
        }
    }
</style>