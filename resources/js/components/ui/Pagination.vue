<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppIcon from '@/components/ui/AppIcon.vue';

/**
 * Pagination server-side dari Laravel paginator (PRD 5.9, 6.3, 11).
 */
interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

const props = withDefaults(
    defineProps<{ links?: PaginationLink[] }>(),
    {
        links: () => [],
    },
);

const items = computed(() =>
    (props.links ?? []).map((link) => {
        const label = link.label.replace(/&laquo;|&raquo;/g, '').trim();

        return {
            ...link,
            label,
            isPrevious: /previous|sebelumnya|&lsaquo;/i.test(link.label),
            isNext: /next|berikutnya|&rsaquo;/i.test(link.label),
            isEllipsis: label === '...',
        };
    }),
);

const hasPages = computed(
    () => items.value.filter((item) => !item.isEllipsis).length > 3,
);
</script>

<template>
    <nav
        v-if="hasPages"
        aria-label="Navigasi halaman"
        class="flex flex-wrap items-center justify-center gap-1.5"
    >
        <template v-for="(link, index) in items" :key="index">
            <Link
                v-if="link.url && !link.active && !link.isEllipsis"
                :href="link.url"
                :aria-label="
                    link.isPrevious
                        ? 'Halaman sebelumnya'
                        : link.isNext
                          ? 'Halaman berikutnya'
                          : `Halaman ${link.label}`
                "
                class="inline-flex min-w-10 items-center justify-center gap-1 rounded-button border border-border bg-background px-3 py-2 text-sm font-medium text-foreground transition-colors hover:border-primary hover:text-primary"
            >
                <AppIcon v-if="link.isPrevious" name="chevron-left" :size="16" />
                <span :class="link.isPrevious || link.isNext ? 'sr-only sm:not-sr-only' : ''">
                    {{ link.label }}
                </span>
                <AppIcon v-if="link.isNext" name="chevron-right" :size="16" />
            </Link>

            <span
                v-else-if="link.active"
                aria-current="page"
                class="inline-flex min-w-10 items-center justify-center rounded-button bg-primary px-3 py-2 text-sm font-semibold text-white"
            >
                {{ link.label }}
            </span>

            <span
                v-else
                class="inline-flex min-w-10 items-center justify-center px-3 py-2 text-sm text-muted"
            >
                {{ link.isEllipsis ? '…' : link.label }}
            </span>
        </template>
    </nav>
</template>
