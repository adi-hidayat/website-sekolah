<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import PageHero from '@/components/layout/PageHero.vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import Pagination from '@/components/ui/Pagination.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { formatDate } from '@/lib/format';
import { show as announcementShow } from '@/routes/announcements';
import type { Announcement, Paginated } from '@/types/cms';

/**
 * Daftar pengumuman (PRD 6.5): penanda penting, tanggal, ringkasan,
 * lampiran, dan pagination.
 */
defineProps<{ announcements: Paginated<Announcement> }>();
</script>

<template>
    <DefaultLayout>
        <Head title="Pengumuman">
            <meta
                name="description"
                content="Pengumuman resmi dan informasi terbaru dari sekolah."
            />
        </Head>

        <PageHero
            eyebrow="Informasi"
            title="Pengumuman"
            description="Informasi resmi seputar kegiatan, jadwal, dan kebijakan sekolah."
            :breadcrumbs="[
                { label: 'Beranda', href: '/' },
                { label: 'Pengumuman' },
            ]"
        />

        <SectionContainer>
            <ul v-if="announcements.data.length" class="space-y-4">
                <li
                    v-for="item in announcements.data"
                    :key="item.id"
                    class="rounded-card border border-border bg-background p-5 transition-colors hover:border-primary sm:p-6"
                >
                    <div class="flex flex-wrap items-center gap-3 text-xs">
                        <span
                            v-if="item.is_pinned"
                            class="inline-flex items-center gap-1 rounded-full bg-gold-light px-2.5 py-1 font-semibold text-foreground/80"
                        >
                            <AppIcon name="star" :size="12" />
                            Penting
                        </span>
                        <span
                            v-if="item.category"
                            class="rounded-full bg-primary-light px-2.5 py-1 font-semibold text-primary-dark"
                        >
                            {{ item.category }}
                        </span>
                        <span
                            v-if="item.starts_at || item.ends_at"
                            class="flex items-center gap-1.5 text-muted"
                        >
                            <AppIcon name="calendar" :size="14" />
                            {{ formatDate(item.starts_at ?? item.ends_at) }}
                        </span>
                    </div>

                    <h2 class="mt-3 font-display text-lg font-semibold">
                        <Link
                            :href="announcementShow(item.slug).url"
                            class="text-foreground transition-colors hover:text-primary"
                        >
                            {{ item.title }}
                        </Link>
                    </h2>

                    <div class="mt-3 flex flex-wrap items-center gap-4">
                        <Link
                            :href="announcementShow(item.slug).url"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-dark"
                        >
                            Baca pengumuman
                            <AppIcon name="arrow-right" :size="16" />
                        </Link>
                        <a
                            v-if="item.attachment"
                            :href="item.attachment"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1.5 text-sm font-medium text-muted hover:text-primary"
                        >
                            <AppIcon name="link" :size="16" />
                            Lampiran
                        </a>
                    </div>
                </li>
            </ul>

            <EmptyState
                v-else
                icon="calendar"
                title="Belum ada pengumuman"
                description="Pengumuman terbaru akan tampil di sini setelah dipublikasikan."
            />

            <div class="mt-12">
                <Pagination :links="announcements.links" />
            </div>
        </SectionContainer>
    </DefaultLayout>
</template>
