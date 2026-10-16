<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import ImageGallery from '@/components/content/ImageGallery.vue';
import { useSectionData } from '@/composables/useSectionData';
import { useSite } from '@/composables/useSite';
import type { PageSection } from '@/types/cms';

/**
 * Galeri kegiatan (PRD 5.12). Sumber gambar: daftar URL dari CMS atau
 * album galeri dari database. Lightbox + lazy loading ada di ImageGallery.
 */
const props = defineProps<{ section: PageSection }>();

const { text, list, number, records } = useSectionData(props.section);
const { collections } = useSite();

const title = computed(() => text('title', 'Galeri Kegiatan'));
const description = computed(() => text('description'));
const limit = computed(() => number('limit', 12));

const images = computed(() => {
    const configured = records('images')
        .map((item) => ({
            src: (item.src ?? item.url ?? item.image ?? '') as string | null,
            alt: (item.alt ?? item.caption ?? title.value) as string,
            caption: (item.caption ?? '') as string | null,
        }))
        .filter((image) => Boolean(image.src));

    if (configured.length > 0) {
        return configured;
    }

    const urls = list('images');

    if (urls.length > 0) {
        return urls.map((url, index) => ({
            src: url,
            alt: `${title.value} — foto ${index + 1}`,
            caption: null,
        }));
    }

    return collections.value.galleries
        .flatMap((gallery) =>
            (gallery.items ?? []).map((item, index) => ({
                src: item.file_path,
                alt: item.caption ?? `${gallery.title} — foto ${index + 1}`,
                caption: item.caption,
            })),
        )
        .slice(0, limit.value);
});
</script>

<template>
    <SectionContainer v-if="images.length" :id="section.id" tone="surface">
        <SectionHeading
            :eyebrow="text('eyebrow')"
            :title="title"
            :description="description"
        />

        <div class="mt-10">
            <ImageGallery :images="images" :columns="3" />
        </div>
    </SectionContainer>
</template>
