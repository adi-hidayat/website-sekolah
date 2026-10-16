<script setup lang="ts">
import type { Statistic } from '@/types/cms';

/**
 * Kartu statistik ringkas (PRD 5.4). Angka berasal dari data valid
 * (pengaturan sekolah atau hitungan backend).
 */
const props = withDefaults(
    defineProps<{
        statistic: Statistic;
        tone?: 'default' | 'inverse';
    }>(),
    { tone: 'default' },
);

const value = props.statistic.value;
</script>

<template>
    <div class="text-center sm:text-left">
        <p
            class="font-display text-3xl font-bold sm:text-4xl"
            :class="tone === 'inverse' ? 'text-white' : 'text-primary'"
        >
            {{ value }}
        </p>
        <p
            class="mt-1 text-sm font-medium"
            :class="tone === 'inverse' ? 'text-white/75' : 'text-muted'"
        >
            {{ statistic.label }}
        </p>
    </div>
</template>
