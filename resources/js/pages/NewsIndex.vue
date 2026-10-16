<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import PageHero from '@/components/layout/PageHero.vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import NewsCard from '@/components/content/NewsCard.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import Pagination from '@/components/ui/Pagination.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { index as newsIndex } from '@/routes/news';
import type { NewsCategory, NewsItem, Paginated } from '@/types/cms';

/**
 * Daftar berita (PRD 6.3): berita utama, grid, filter kategori,
 * pencarian, pagination, dan empty state.
 */
const props = defineProps<{
    news: Paginated<NewsItem>;
    categories: NewsCategory[];
    filters: { q: string; category: string };
}>();

const q = ref(props.filters.q ?? '');
const category = ref(props.filters.category ?? '');

const hasFilters = computed(
    () => q.value.trim() !== '' || category.value !== '',
);

const featured = computed<NewsItem | null>(() =>
    !hasFilters.value && props.news.current_page === 1
        ? (props.news.data[0] ?? null)
        : null,
);

const items = computed(() =>
    featured.value ? props.news.data.slice(1) : props.news.data,
);

function submit(): void {
    router.get(
        newsIndex().url,
        { q: q.value, category: category.value },
        { preserveState: true, preserveScroll: true, replace: true },
    );
}

function selectCategory(slug: string): void {
    category.value = category.value === slug ? '' : slug;
    submit();
}

function reset(): void {
    q.value = '';
    category.value = '';
    submit();
}
</script>

<template>
    <DefaultLayout>
        <Head title="Berita">
            <meta
                name="description"
                content="Berita dan kegiatan terbaru sekolah."
            />
        </Head>

        <PageHero
            eyebrow="Informasi"
            title="Berita dan Kegiatan"
            description="Kabar terbaru seputar pembelajaran, prestasi, dan aktivitas sekolah."
            :breadcrumbs="[
                { label: 'Beranda', href: '/' },
                { label: 'Berita' },
            ]"
        />

        <SectionContainer padding="compact">
            <form class="flex flex-col gap-4" @submit.prevent="submit">
                <div class="flex gap-2">
                    <label for="news-search" class="sr-only">Cari berita</label>
                    <div class="relative flex-1">
                        <AppIcon
                            name="search"
                            :size="18"
                            class="pointer-events-none absolute top-1/2 left-3 -translate-y-1/2 text-muted"
                        />
                        <input
                            id="news-search"
                            v-model="q"
                            type="search"
                            placeholder="Cari berita…"
                            class="w-full rounded-input border border-border bg-background py-2.5 pr-3 pl-10 text-sm text-foreground placeholder:text-muted focus:border-primary focus:outline-none"
                        />
                    </div>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-button bg-primary px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-primary-dark"
                    >
                        Cari
                    </button>
                </div>

                <div
                    v-if="categories.length"
                    class="flex flex-wrap items-center gap-2"
                >
                    <button
                        type="button"
                        class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors"
                        :class="
                            category === ''
                                ? 'border-primary bg-primary text-white'
                                : 'border-border text-foreground/80 hover:border-primary hover:text-primary'
                        "
                        @click="selectCategory('')"
                    >
                        Semua
                    </button>
                    <button
                        v-for="item in categories"
                        :key="item.id"
                        type="button"
                        class="rounded-full border px-3.5 py-1.5 text-sm font-medium transition-colors"
                        :class="
                            category === item.slug
                                ? 'border-primary bg-primary text-white'
                                : 'border-border text-foreground/80 hover:border-primary hover:text-primary'
                        "
                        @click="selectCategory(item.slug)"
                    >
                        {{ item.name }}
                    </button>
                </div>
            </form>
        </SectionContainer>

        <SectionContainer padding="compact">
            <div v-if="featured" class="mb-12">
                <NewsCard :news="featured" variant="featured" />
            </div>

            <div
                v-if="items.length"
                class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3"
            >
                <NewsCard v-for="item in items" :key="item.id" :news="item" />
            </div>

            <EmptyState
                v-else-if="!featured"
                icon="search"
                title="Berita tidak ditemukan"
                description="Coba ubah kata kunci pencarian atau pilih kategori lain."
            >
                <template #action>
                    <button
                        type="button"
                        class="rounded-button border border-border px-4 py-2 text-sm font-semibold text-foreground hover:border-primary hover:text-primary"
                        @click="reset"
                    >
                        Reset filter
                    </button>
                </template>
            </EmptyState>

            <div class="mt-12">
                <Pagination :links="news.links" />
            </div>
        </SectionContainer>
    </DefaultLayout>
</template>
