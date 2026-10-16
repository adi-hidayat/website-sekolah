<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import Breadcrumbs from '@/components/ui/Breadcrumbs.vue';
import ImageGallery from '@/components/content/ImageGallery.vue';
import AspectImage from '@/components/ui/AspectImage.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import {
    index as galleriesIndex,
    show as galleryShow,
} from '@/routes/galleries';
import type { Gallery, GalleryItem, SeoMeta } from '@/types/cms';

/**
 * Detail album galeri (PRD 5.12) dengan lightbox dan album terkait.
 */
const props = defineProps<{
    gallery: Gallery & { items: GalleryItem[] };
    related: Gallery[];
    seo: SeoMeta;
}>();

const images = computed(() =>
    (props.gallery.items ?? []).map((item, index) => ({
        src: item.file_path,
        alt: item.caption ?? `${props.gallery.title} — foto ${index + 1}`,
        caption: item.caption,
    })),
);
</script>

<template>
    <DefaultLayout>
        <Head :title="seo.title ?? gallery.title">
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
                    { label: 'Galeri', href: galleriesIndex().url },
                    { label: gallery.title },
                ]"
            />

            <div class="mt-6 max-w-3xl">
                <h1 class="text-3xl font-bold text-foreground sm:text-4xl">
                    {{ gallery.title }}
                </h1>
                <p
                    v-if="gallery.description"
                    class="mt-3 text-base leading-relaxed text-muted"
                >
                    {{ gallery.description }}
                </p>
            </div>
        </SectionContainer>

        <SectionContainer padding="compact">
            <ImageGallery v-if="images.length" :images="images" :columns="3" />

            <EmptyState
                v-else
                icon="image"
                title="Album masih kosong"
                description="Foto dokumentasi akan tampil di sini setelah ditambahkan."
            />
        </SectionContainer>

        <SectionContainer v-if="related.length" tone="surface" bordered>
            <h2 class="font-display text-xl font-semibold text-foreground">
                Album lainnya
            </h2>

            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="album in related"
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
                    <h3
                        class="p-4 font-display text-base font-semibold text-foreground transition-colors group-hover:text-primary"
                    >
                        {{ album.title }}
                    </h3>
                </Link>
            </div>
        </SectionContainer>
    </DefaultLayout>
</template>
