<script setup lang="ts">
import { computed, useSlots } from 'vue';

/**
 * Judul section konsisten: eyebrow, heading, deskripsi, dan slot aksi
 * (PRD 4.2 & 5.6).
 */
const props = withDefaults(
    defineProps<{
        eyebrow?: string | null;
        title?: string | null;
        description?: string | null;
        align?: 'left' | 'center';
        tone?: 'default' | 'inverse';
        level?: 2 | 3;
        titleId?: string;
    }>(),
    {
        eyebrow: null,
        title: null,
        description: null,
        align: 'left',
        tone: 'default',
        level: 2,
    },
);

const slots = useSlots();
const heading = computed(() => `h${props.level}`);
const hasAction = computed(() => Boolean(slots.action));
const isCenter = computed(() => props.align === 'center');
const isInverse = computed(() => props.tone === 'inverse');
</script>

<template>
    <div
        class="flex w-full gap-6"
        :class="
            hasAction && !isCenter
                ? 'flex-col md:flex-row md:items-end md:justify-between'
                : isCenter
                  ? 'flex-col items-center text-center'
                  : 'flex-col'
        "
    >
        <div class="max-w-3xl" :class="isCenter ? 'mx-auto' : ''">
            <p
                v-if="eyebrow"
                class="text-xs font-semibold tracking-[0.18em] uppercase"
                :class="isInverse ? 'text-accent' : 'text-primary'"
            >
                {{ eyebrow }}
            </p>
            <component
                :is="heading"
                :id="titleId"
                v-if="title"
                class="mt-2 text-2xl font-bold sm:text-3xl lg:text-4xl"
                :class="isInverse ? 'text-white' : 'text-foreground'"
            >
                {{ title }}
            </component>
            <p
                v-if="description"
                class="mt-3 text-base leading-relaxed"
                :class="isInverse ? 'text-white/75' : 'text-muted'"
            >
                {{ description }}
            </p>
        </div>
        <div v-if="hasAction" :class="isCenter ? 'mt-2' : 'shrink-0'">
            <slot name="action" />
        </div>
    </div>
</template>
