<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import Breadcrumbs from '@/components/ui/Breadcrumbs.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { formatDate } from '@/lib/format';
import { index as announcementsIndex } from '@/routes/announcements';
import type { Announcement, SeoMeta } from '@/types/cms';

/**
 * Detail pengumuman (PRD 6.5).
 */
defineProps<{
    announcement: Announcement & { content: string };
    seo: SeoMeta;
}>();
</script>

<template>
    <DefaultLayout>
        <Head :title="seo.title ?? announcement.title">
            <meta
                v-if="seo.description"
                name="description"
                :content="seo.description"
            />
        </Head>

        <SectionContainer padding="compact">
            <Breadcrumbs
                :items="[
                    { label: 'Beranda', href: '/' },
                    { label: 'Pengumuman', href: announcementsIndex().url },
                    { label: announcement.title },
                ]"
            />

            <div class="mt-6 max-w-3xl">
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <span
                        v-if="announcement.is_pinned"
                        class="inline-flex items-center gap-1 rounded-full bg-gold-light px-2.5 py-1 font-semibold text-foreground/80"
                    >
                        <AppIcon name="star" :size="12" />
                        Penting
                    </span>
                    <span
                        v-if="announcement.category"
                        class="rounded-full bg-primary-light px-2.5 py-1 font-semibold text-primary-dark"
                    >
                        {{ announcement.category }}
                    </span>
                    <span
                        v-if="announcement.starts_at || announcement.ends_at"
                        class="flex items-center gap-1.5 text-muted"
                    >
                        <AppIcon name="calendar" :size="14" />
                        {{ formatDate(announcement.starts_at) }}
                        <template v-if="announcement.ends_at">
                            – {{ formatDate(announcement.ends_at) }}
                        </template>
                    </span>
                </div>

                <h1 class="mt-4 text-3xl font-bold text-foreground sm:text-4xl">
                    {{ announcement.title }}
                </h1>
            </div>
        </SectionContainer>

        <SectionContainer padding="compact">
            <div class="mx-auto max-w-3xl">
                <a
                    v-if="announcement.attachment"
                    :href="announcement.attachment"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mb-8 flex items-center gap-3 rounded-card border border-border bg-surface p-4 transition-colors hover:border-primary"
                >
                    <span
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-light text-primary"
                    >
                        <AppIcon name="link" :size="20" />
                    </span>
                    <span class="text-sm font-medium text-foreground">
                        Unduh lampiran pengumuman
                    </span>
                </a>

                <!-- eslint-disable-next-line vue/no-v-html -->
                <article class="rich-text" v-html="announcement.content" />
            </div>
        </SectionContainer>
    </DefaultLayout>
</template>
