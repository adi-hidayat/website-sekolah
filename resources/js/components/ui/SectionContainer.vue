<script setup lang="ts">
import { computed } from 'vue';
import PageContainer from '@/components/ui/PageContainer.vue';

/**
 * Pembungkus section dengan padding & latar mengikuti design token
 * (PRD 4.3, 4.4, 8.3). Setiap section baru cukup memakai komponen ini.
 */
const props = withDefaults(
    defineProps<{
        id?: string;
        tone?: 'default' | 'surface' | 'primary' | 'dark' | 'transparent';
        padding?: 'normal' | 'compact' | 'none';
        bordered?: boolean;
        contained?: boolean;
    }>(),
    {
        tone: 'default',
        padding: 'normal',
        bordered: false,
        contained: true,
    },
);

const toneClass = computed(
    () =>
        ({
            default: 'bg-background text-foreground',
            surface: 'bg-surface text-foreground',
            primary: 'bg-primary-light text-foreground',
            dark: 'bg-primary-dark text-white',
            transparent: '',
        })[props.tone],
);

const paddingClass = computed(
    () =>
        ({
            normal: 'py-12 sm:py-16 lg:py-24',
            compact: 'py-8 sm:py-10 lg:py-12',
            none: 'py-0',
        })[props.padding],
);
</script>

<template>
    <section
        :id="id"
        class="scroll-mt-28"
        :class="[toneClass, paddingClass, bordered ? 'border-y border-border' : '']"
    >
        <PageContainer v-if="contained">
            <slot />
        </PageContainer>
        <slot v-else />
    </section>
</template>
