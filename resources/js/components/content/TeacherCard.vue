<script setup lang="ts">
import AspectImage from '@/components/ui/AspectImage.vue';
import type { Teacher } from '@/types/cms';

/**
 * Kartu tenaga pendidik (PRD 6.2). Foto opsional.
 */
defineProps<{ teacher: Teacher }>();
</script>

<template>
    <article
        class="overflow-hidden rounded-card border border-border bg-background"
    >
        <AspectImage
            :src="teacher.photo"
            :alt="teacher.name"
            ratio="portrait"
            :rounded="false"
        />
        <div class="p-4">
            <h3 class="font-display text-sm font-semibold text-foreground">
                {{ teacher.name }}
            </h3>
            <p v-if="teacher.position" class="mt-0.5 text-xs text-primary">
                {{ teacher.position }}
            </p>
            <p v-if="teacher.subject" class="mt-1 text-xs text-muted">
                {{ teacher.subject }}
            </p>
        </div>
    </article>
</template>
