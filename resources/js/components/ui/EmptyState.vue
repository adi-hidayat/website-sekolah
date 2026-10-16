<script setup lang="ts">
import AppIcon from '@/components/ui/AppIcon.vue';

/**
 * Keadaan kosong yang aman untuk konten CMS yang belum diisi (PRD 6.3,
 * 8.3, 14).
 */
withDefaults(
    defineProps<{
        title?: string;
        description?: string | null;
        icon?: string;
    }>(),
    {
        title: 'Belum ada konten',
        description: null,
        icon: 'info',
    },
);
</script>

<template>
    <div
        class="flex flex-col items-center justify-center rounded-card border border-dashed border-border bg-surface px-6 py-14 text-center"
    >
        <span
            class="flex h-12 w-12 items-center justify-center rounded-full bg-primary-light text-primary"
        >
            <AppIcon :name="icon" :size="24" />
        </span>
        <h3 class="mt-4 text-lg font-semibold text-foreground">{{ title }}</h3>
        <p v-if="description" class="mt-1.5 max-w-md text-sm text-muted">
            {{ description }}
        </p>
        <div v-if="$slots.action" class="mt-5">
            <slot name="action" />
        </div>
    </div>
</template>
