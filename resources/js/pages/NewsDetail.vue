<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import Breadcrumbs from '@/components/ui/Breadcrumbs.vue';
import AspectImage from '@/components/ui/AspectImage.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { formatDate } from '@/lib/format';
import { index as newsIndex } from '@/routes/news';
import type { NewsItem, SeoMeta } from '@/types/cms';

/**
 * Detail berita (PRD 6.4): breadcrumb, kategori, meta, featured image,
 * isi artikel, share, dan artikel terkait.
 */
const props = defineProps<{
    article: NewsItem & {
        content: string;
        tags?: Array<{ id: number; name: string }>;
    };
    related: NewsItem[];
    seo: SeoMeta;
}>();

const shareUrl = ref('');

onMounted(() => {
    if (typeof window !== 'undefined') {
        shareUrl.value = window.location.href;
    }
});

const category = computed(() => props.article.categories?.[0] ?? null);

const shareLinks = computed(() => {
    const url = encodeURIComponent(shareUrl.value);
    const text = encodeURIComponent(props.article.title);

    return [
        {
            name: 'WhatsApp',
            icon: 'whatsapp',
            href: `https://wa.me/?text=${text}%20${url}`,
        },
        {
            name: 'Facebook',
            icon: 'facebook',
            href: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
        },
        {
            name: 'X',
            icon: 'x',
            href: `https://twitter.com/intent/tweet?url=${url}&text=${text}`,
        },
    ];
});

const copied = ref(false);

async function copyLink(): Promise<void> {
    if (typeof navigator === 'undefined' || !navigator.clipboard) {
        return;
    }

    await navigator.clipboard.writeText(shareUrl.value);
    copied.value = true;
    window.setTimeout(() => (copied.value = false), 2000);
}
</script>

<template>
    <DefaultLayout>
        <Head :title="seo.title ?? article.title">
            <meta
                v-if="seo.description"
                name="description"
                :content="seo.description"
            />
            <meta property="og:type" content="article" />
            <meta property="og:title" :content="article.title" />
            <meta
                v-if="seo.description"
                property="og:description"
                :content="seo.description"
            />
            <link v-if="seo.canonical" rel="canonical" :href="seo.canonical" />
        </Head>

        <SectionContainer padding="compact">
            <Breadcrumbs
                :items="[
                    { label: 'Beranda', href: '/' },
                    { label: 'Berita', href: newsIndex().url },
                    { label: article.title },
                ]"
            />

            <div class="mt-6 max-w-3xl">
                <div class="flex flex-wrap items-center gap-3 text-sm">
                    <span
                        v-if="category"
                        class="rounded-full bg-primary-light px-3 py-1 text-xs font-semibold text-primary-dark"
                    >
                        {{ category.name }}
                    </span>
                    <span class="flex items-center gap-1.5 text-muted">
                        <AppIcon name="calendar" :size="15" />
                        {{ formatDate(article.published_at) }}
                    </span>
                    <span
                        v-if="article.author"
                        class="flex items-center gap-1.5 text-muted"
                    >
                        <AppIcon name="user" :size="15" />
                        {{ article.author.name }}
                    </span>
                </div>

                <h1 class="mt-4 text-3xl font-bold text-foreground sm:text-4xl">
                    {{ article.title }}
                </h1>

                <p
                    v-if="article.excerpt"
                    class="mt-4 text-lg leading-relaxed text-muted"
                >
                    {{ article.excerpt }}
                </p>
            </div>
        </SectionContainer>

        <SectionContainer padding="compact">
            <AspectImage
                v-if="article.featured_image"
                :src="article.featured_image"
                :alt="article.title"
                ratio="wide"
                eager
                class="mb-10"
            />

            <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_300px]">
                <!-- eslint-disable-next-line vue/no-v-html -->
                <article class="rich-text" v-html="article.content" />

                <aside class="space-y-8">
                    <div>
                        <h2
                            class="font-display text-sm font-semibold text-foreground"
                        >
                            Bagikan
                        </h2>
                        <div class="mt-3 flex items-center gap-2">
                            <a
                                v-for="link in shareLinks"
                                :key="link.name"
                                :href="link.href"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex h-9 w-9 items-center justify-center rounded-full border border-border text-foreground/80 transition-colors hover:border-primary hover:text-primary"
                                :aria-label="`Bagikan ke ${link.name}`"
                            >
                                <AppIcon :name="link.icon" :size="18" />
                            </a>
                            <button
                                type="button"
                                class="flex h-9 w-9 items-center justify-center rounded-full border border-border text-foreground/80 transition-colors hover:border-primary hover:text-primary"
                                :aria-label="
                                    copied ? 'Tautan disalin' : 'Salin tautan'
                                "
                                @click="copyLink"
                            >
                                <AppIcon
                                    :name="copied ? 'check' : 'link'"
                                    :size="18"
                                />
                            </button>
                        </div>
                    </div>

                    <div v-if="related.length">
                        <h2
                            class="font-display text-sm font-semibold text-foreground"
                        >
                            Artikel terkait
                        </h2>
                        <ul class="mt-3 space-y-4">
                            <li
                                v-for="item in related"
                                :key="item.id"
                                class="border-b border-border pb-4 last:border-0"
                            >
                                <Link
                                    :href="`/berita/${item.slug}`"
                                    class="text-sm font-medium text-foreground transition-colors hover:text-primary"
                                >
                                    {{ item.title }}
                                </Link>
                                <p class="mt-1 text-xs text-muted">
                                    {{ formatDate(item.published_at) }}
                                </p>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </SectionContainer>
    </DefaultLayout>
</template>
