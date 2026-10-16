<script setup lang="ts">
import { computed } from 'vue';
import AspectImage from '@/components/ui/AspectImage.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { formatYear } from '@/lib/format';
import type { Achievement } from '@/types/cms';

/**
 * Item prestasi (PRD 5.10). Aksen gold hanya sebagai penanda penghargaan,
 * hijau tetap warna utama.
 */
const props = withDefaults(
    defineProps<{
        achievement: Achievement;
        highlight?: boolean;
    }>(),
    { highlight: false },
);

const year = computed(() => formatYear(props.achievement.achieved_at));
</script>

<template>
    <article
        class="flex gap-4 rounded-card border border-border bg-background p-4 sm:p-5"
        :class="highlight ? 'border-l-4 border-l-gold' : ''"
    >
        <AspectImage
            v-if="achievement.image"
            :src="achievement.image"
            :alt="achievement.title"
            ratio="square"
            class="w-20 shrink-0 sm:w-24"
        />
        <span
            v-else
            class="flex h-16 w-16 shrink-0 items-center justify-center rounded-card bg-gold-light text-gold sm:h-20 sm:w-20"
        >
            <AppIcon name="trophy" :size="28" />
        </span>

        <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <span
                    v-if="achievement.level"
                    class="rounded-full bg-primary-light px-2 py-0.5 font-semibold text-primary-dark"
                >
                    {{ achievement.level }}
                </span>
                <span
                    v-if="achievement.rank"
                    class="rounded-full bg-gold-light px-2 py-0.5 font-semibold text-foreground/80"
                >
                    {{ achievement.rank }}
                </span>
                <span v-if="year" class="text-muted">{{ year }}</span>
            </div>
            <h3
                class="mt-1.5 font-display text-base font-semibold text-foreground"
            >
                {{ achievement.title }}
            </h3>
            <p
                v-if="achievement.description"
                class="mt-1 line-clamp-2 text-sm text-muted"
            >
                {{ achievement.description }}
            </p>
        </div>
    </article>
</template>
