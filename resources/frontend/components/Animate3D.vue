<template>
    <figure
        ref="cardRef"
        class="relative w-full h-full [perspective:800px] flex flex-col items-center justify-center"
        @mouseenter="handleMouseEnter"
        @mouseleave="handleMouseLeave"
        @mousemove="handleMouse"
    >
        <Motion
            :animate="{
                rotateX: rotateXValue,
                rotateY: rotateYValue,
                scale: scaleValue,
            }"
            :transition="springTransition"
            class="relative [transform-style:preserve-3d]"
            tag="div"
        >
            <slot />
        </Motion>
    </figure>
</template>

<script lang="ts" setup>
    import { computed, ref, useTemplateRef } from "vue";
    import { Motion } from "motion-v";

    const rotateAmplitude = 15;
    const scaleOnHover = 1.05;

    const cardRef = useTemplateRef<HTMLElement>("cardRef");
    const xValue = ref(0);
    const yValue = ref(0);
    const rotateXValue = ref(0);
    const rotateYValue = ref(0);
    const scaleValue = ref(1);
    const opacityValue = ref(0);
    const rotateFigcaptionValue = ref(0);
    const lastY = ref(0);

    const springTransition = computed(() => {
        return {
            type: "spring" as const,
            damping: 30,
            stiffness: 100,
            mass: 2,
        };
    });

    function handleMouse(e: MouseEvent) {
        if (!cardRef.value) return;

        const rect = cardRef.value.getBoundingClientRect();
        const offsetX = e.clientX - rect.left - rect.width / 2;
        const offsetY = e.clientY - rect.top - rect.height / 2;

        const rotationX = (offsetY / (rect.height / 2)) * -rotateAmplitude;
        const rotationY = (offsetX / (rect.width / 2)) * rotateAmplitude;

        rotateXValue.value = rotationX;
        rotateYValue.value = rotationY;

        xValue.value = e.clientX - rect.left;
        yValue.value = e.clientY - rect.top;

        const velocityY = offsetY - lastY.value;
        rotateFigcaptionValue.value = -velocityY * 0.6;
        lastY.value = offsetY;
    }

    function handleMouseEnter() {
        scaleValue.value = scaleOnHover;
        opacityValue.value = 1;
    }

    function handleMouseLeave() {
        opacityValue.value = 0;
        scaleValue.value = 1;
        rotateXValue.value = 0;
        rotateYValue.value = 0;
        rotateFigcaptionValue.value = 0;
    }
</script>