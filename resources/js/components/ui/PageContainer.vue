<script setup lang="ts">
/**
 * Wadah konten utama (PRD 4.4): max 1280px, gutter responsif.
 */
withDefaults(
    defineProps<{
        as?: string;
        wide?: boolean;
    }>(),
    {
        as: 'div',
        wide: false,
    },
);
</script>

<template>
    <component
        :is="as"
        class="mx-auto w-full px-4 sm:px-6 lg:px-8"
        :class="wide ? 'max-w-[1400px]' : 'max-w-content'"
    >
        <slot />
    </component>
</template>
