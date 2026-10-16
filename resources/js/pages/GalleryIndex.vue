<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import PageHero from '@/components/layout/PageHero.vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import AspectImage from '@/components/ui/AspectImage.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import Pagination from '@/components/ui/Pagination.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { show as galleryShow } from '@/routes/galleries';
import type { Gallery, Paginated } from '@/types/cms';

/**
 * Daftar album galeri (PRD 5.12).
 */
defineProps<{ galleries: Paginated<Gallery> }>();
</script>

<template>
    <DefaultLayout>
        <Head title="Galeri">
            <meta
                name="description"
                content="Dokumentasi kegiatan dan momen sekolah dalam album foto."
            />
        </Head>

        <PageHero
            eyebrow="Dokumentasi"
            title="Galeri"
            description="Kumpulan foto kegiatan belajar, prestasi, dan momen kebersamaan sekolah."
            :breadcrumbs="[
                { label: 'Beranda', href: '/' },
                { label: 'Galeri' },
            ]"
        />

        <SectionContainer>
            <div
                v-if="galleries.data.length"
                class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3"
            >
                <Link
                    v-for="album in galleries.data"
                    :key="album.id"
                    :href="galleryShow(album.slug).url"
                    class="group block overflow-hidden rounded-card border border-border bg-background transition-colors hover:border-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                >
                    <AspectImage
                        :src="album.cover_image"
                        :alt="album.title"
                        ratio="video"
                        :rounded="false"
                        class="transition-transform duration-300 group-hover:scale-[1.02]"
                    />

                    <div class="p-5">
                        <h2
                            class="font-display text-lg font-semibold text-foreground transition-colors group-hover:text-primary"
                        >
                            {{ album.title }}
                        </h2>
                        <p
                            v-if="album.description"
                            class="mt-2 line-clamp-2 text-sm leading-relaxed text-muted"
                        >
                            {{ album.description }}
                        </p>
                        <span
                            class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-primary"
                        >
                            <AppIcon name="image" :size="16" />
                            {{ album.items_count ?? 0 }} foto
                            <AppIcon name="arrow-right" :size="16" />
                        </span>
                    </div>
                </Link>
            </div>

            <EmptyState
                v-else
                icon="image"
                title="Belum ada album galeri"
                description="Album dokumentasi akan tampil di sini setelah dipublikasikan."
            />

            <div class="mt-12">
                <Pagination :links="galleries.links" />
            </div>
        </SectionContainer>
    </DefaultLayout>
</template>
