<script setup lang="ts">
import { computed } from 'vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { imageUrl } from '@/lib/format';

/**
 * Bingkai gambar dengan rasio terkunci (mencegah layout shift, PRD 11) dan
 * placeholder aman bila gambar CMS belum diisi (PRD 8.3).
 */
const props = withDefaults(
    defineProps<{
        src?: string | null;
        alt?: string;
        ratio?: 'video' | 'square' | 'portrait' | 'wide' | 'auto';
        eager?: boolean;
        rounded?: boolean;
    }>(),
    {
        src: null,
        alt: '',
        ratio: 'video',
        eager: false,
        rounded: true,
    },
);

const ratioClasses: Record<string, string> = {
    video: 'aspect-video',
    square: 'aspect-square',
    portrait: 'aspect-4/5',
    wide: 'aspect-video',
    auto: '',
};

const resolved = computed(() => imageUrl(props.src));
</script>

<template>
    <div
        class="relative overflow-hidden bg-surface"
        :class="[ratioClasses[ratio], rounded ? 'rounded-card' : '']"
    >
        <img
            v-if="resolved"
            :src="resolved"
            :alt="alt"
            :loading="eager ? 'eager' : 'lazy'"
            :fetchpriority="eager ? 'high' : 'auto'"
            decoding="async"
            class="h-full w-full object-cover"
        />
        <div
            v-else
            class="flex h-full w-full items-center justify-center bg-primary-light text-primary/60"
            :class="ratio === 'auto' ? 'min-h-40' : ''"
        >
            <AppIcon name="image" :size="32" />
        </div>
    </div>
</template>
