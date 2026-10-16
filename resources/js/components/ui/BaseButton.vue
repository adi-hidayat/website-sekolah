<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppIcon from '@/components/ui/AppIcon.vue';
import { cn } from '@/lib/utils';

/**
 * Tombol tunggal untuk seluruh situs (PRD 4.3 & 7). Mendukung tautan
 * internal (Inertia) maupun eksternal, tanpa ketergantungan hover saja.
 */
const props = withDefaults(
    defineProps<{
        href?: string | null;
        target?: '_self' | '_blank';
        variant?:
            | 'primary'
            | 'secondary'
            | 'outline'
            | 'outline-inverse'
            | 'ghost'
            | 'gold'
            | 'white';
        size?: 'sm' | 'md' | 'lg';
        type?: 'button' | 'submit' | 'reset';
        icon?: string | null;
        iconPosition?: 'left' | 'right';
        disabled?: boolean;
        loading?: boolean;
        block?: boolean;
    }>(),
    {
        href: null,
        target: '_self',
        variant: 'primary',
        size: 'md',
        type: 'button',
        icon: null,
        iconPosition: 'right',
        disabled: false,
        loading: false,
        block: false,
    },
);

const variants: Record<string, string> = {
    primary: 'bg-primary text-white hover:bg-primary-dark',
    secondary: 'bg-primary-light text-primary-dark hover:bg-accent',
    outline:
        'border border-border bg-background text-foreground hover:border-primary hover:text-primary',
    'outline-inverse':
        'border border-white/40 bg-transparent text-white hover:border-white hover:bg-white/10',
    ghost: 'bg-transparent text-primary hover:bg-primary-light',
    gold: 'bg-gold text-foreground hover:bg-gold/90',
    white: 'bg-white text-primary-dark hover:bg-primary-light',
};

const sizes: Record<string, string> = {
    sm: 'px-3.5 py-2 text-sm',
    md: 'px-5 py-2.5 text-sm',
    lg: 'px-6 py-3 text-base',
};

const isExternal = computed(
    () => Boolean(props.href) && /^(https?:)?\/\//i.test(props.href ?? ''),
);

const classes = computed(() =>
    cn(
        'inline-flex items-center justify-center gap-2 rounded-button font-semibold transition-colors duration-150 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:pointer-events-none disabled:opacity-60',
        variants[props.variant],
        sizes[props.size],
        props.block ? 'w-full' : '',
    ),
);

const linkProps = computed(() =>
    props.target === '_blank' || isExternal.value
        ? { target: '_blank', rel: 'noopener noreferrer' }
        : {},
);
</script>

<template>
    <Link
        v-if="href && !isExternal"
        :href="href"
        :class="classes"
        :aria-busy="loading || undefined"
        :aria-disabled="disabled || undefined"
        v-bind="linkProps"
    >
        <AppIcon
            v-if="icon && iconPosition === 'left'"
            :name="icon"
            :size="18"
        />
        <slot />
        <AppIcon
            v-if="icon && iconPosition === 'right'"
            :name="icon"
            :size="18"
        />
    </Link>

    <a v-else-if="href" :href="href" :class="classes" v-bind="linkProps">
        <AppIcon
            v-if="icon && iconPosition === 'left'"
            :name="icon"
            :size="18"
        />
        <slot />
        <AppIcon
            v-if="icon && iconPosition === 'right'"
            :name="icon"
            :size="18"
        />
    </a>

    <button
        v-else
        :type="type"
        :class="classes"
        :disabled="disabled || loading"
        :aria-busy="loading || undefined"
    >
        <AppIcon
            v-if="loading"
            name="circle"
            :size="18"
            class="animate-spin"
        />
        <AppIcon
            v-else-if="icon && iconPosition === 'left'"
            :name="icon"
            :size="18"
        />
        <slot />
        <AppIcon
            v-if="!loading && icon && iconPosition === 'right'"
            :name="icon"
            :size="18"
        />
    </button>
</template>
