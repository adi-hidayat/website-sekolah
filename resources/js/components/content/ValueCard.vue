<script setup lang="ts">
import AppIcon from '@/components/ui/AppIcon.vue';

/**
 * Kartu nilai/karakter Islami (PRD 5.7) dengan ikon minimalis beraksen hijau.
 */
withDefaults(
    defineProps<{
        icon?: string;
        title: string;
        description?: string | null;
    }>(),
    { icon: 'sparkles', description: null },
);
</script>

<template>
    <div class="rounded-card border border-border bg-background p-5 sm:p-6">
        <span
            class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-light text-primary"
        >
            <AppIcon :name="icon" :size="22" />
        </span>
        <h3 class="mt-4 font-display text-lg font-semibold text-foreground">
            {{ title }}
        </h3>
        <p v-if="description" class="mt-2 text-sm leading-relaxed text-muted">
            {{ description }}
        </p>
    </div>
</template>
