<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AspectImage from '@/components/ui/AspectImage.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { formatDate } from '@/lib/format';
import { show as newsShow } from '@/routes/news';
import type { NewsItem } from '@/types/cms';

/**
 * Kartu berita (PRD 5.9, 6.3). `featured` untuk berita utama berukuran
 * besar, `compact` untuk daftar pendukung.
 */
const props = withDefaults(
    defineProps<{
        news: NewsItem;
        variant?: 'default' | 'featured' | 'compact';
    }>(),
    { variant: 'default' },
);

const href = computed(() => newsShow(props.news.slug).url);
const category = computed(() => props.news.categories?.[0]?.name ?? null);
</script>

<template>
    <article
        class="group"
        :class="
            variant === 'featured'
                ? 'grid gap-6 lg:grid-cols-2 lg:items-center'
                : ''
        "
    >
        <Link
            :href="href"
            class="block overflow-hidden focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
            :aria-label="news.title"
        >
            <AspectImage
                :src="news.featured_image"
                :alt="news.title"
                :ratio="variant === 'featured' ? 'wide' : 'video'"
                class="transition-transform duration-300 group-hover:scale-[1.02]"
            />
        </Link>

        <div :class="variant === 'featured' ? '' : 'mt-4'">
            <div class="flex flex-wrap items-center gap-2 text-xs text-muted">
                <span
                    v-if="category"
                    class="rounded-full bg-primary-light px-2.5 py-1 font-semibold text-primary-dark"
                >
                    {{ category }}
                </span>
                <span v-if="news.published_at" class="flex items-center gap-1">
                    <AppIcon name="calendar" :size="14" />
                    {{ formatDate(news.published_at) }}
                </span>
            </div>

            <h3
                class="mt-2 font-display font-semibold text-foreground"
                :class="
                    variant === 'featured'
                        ? 'text-xl sm:text-2xl lg:text-3xl'
                        : 'text-base sm:text-lg'
                "
            >
                <Link
                    :href="href"
                    class="transition-colors hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                >
                    {{ news.title }}
                </Link>
            </h3>

            <p
                v-if="news.excerpt && variant !== 'compact'"
                class="mt-2 line-clamp-3 text-sm leading-relaxed text-muted"
            >
                {{ news.excerpt }}
            </p>

            <Link
                v-if="variant === 'featured'"
                :href="href"
                class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-dark"
            >
                Baca selengkapnya
                <AppIcon name="arrow-right" :size="16" />
            </Link>
        </div>
    </article>
</template>
