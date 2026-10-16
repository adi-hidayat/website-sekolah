<script setup lang="ts">
/**
 * Skeleton loading untuk konten async / halaman Inertia yang lambat
 * (PRD 6.3, 14). Menggunakan animasi pulse yang tetap menghormati
 * prefers-reduced-motion lewat CSS global.
 */
withDefaults(
    defineProps<{
        label?: string;
        rows?: number;
        cards?: number;
    }>(),
    {
        label: 'Memuat konten…',
        rows: 3,
        cards: 0,
    },
);
</script>

<template>
    <div role="status" :aria-label="label" class="w-full">
        <span class="sr-only">{{ label }}</span>

        <div v-if="cards > 0" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="index in cards"
                :key="index"
                class="animate-pulse rounded-card border border-border p-5"
            >
                <div class="h-40 w-full rounded-lg bg-surface"></div>
                <div class="mt-4 h-4 w-2/3 rounded bg-surface"></div>
                <div class="mt-2 h-3 w-full rounded bg-surface"></div>
                <div class="mt-2 h-3 w-4/5 rounded bg-surface"></div>
            </div>
        </div>

        <div v-else class="animate-pulse space-y-3">
            <div
                v-for="index in rows"
                :key="index"
                class="h-4 rounded bg-surface"
                :class="index === rows ? 'w-3/5' : 'w-full'"
            ></div>
        </div>
    </div>
</template>
