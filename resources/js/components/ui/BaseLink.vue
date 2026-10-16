<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { cn } from '@/lib/utils';

/**
 * Tautan teks reusable dengan state aktif (PRD 5.2, 7, 12).
 */
const props = withDefaults(
    defineProps<{
        href: string;
        active?: boolean;
        external?: boolean;
        target?: '_self' | '_blank';
    }>(),
    {
        active: false,
        external: false,
        target: '_self',
    },
);

const isExternal = computed(
    () => props.external || /^(https?:)?\/\//i.test(props.href),
);

const classes = computed(() =>
    cn(
        'rounded-md text-sm font-medium transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary',
        props.active
            ? 'text-primary'
            : 'text-foreground/80 hover:text-primary',
    ),
);
</script>

<template>
    <Link v-if="!isExternal" :href="href" :class="classes">
        <slot />
    </Link>
    <a
        v-else
        :href="href"
        :class="classes"
        target="_blank"
        rel="noopener noreferrer"
    >
        <slot />
    </a>
</template>
