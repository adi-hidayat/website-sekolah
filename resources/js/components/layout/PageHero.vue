<script setup lang="ts">
import SectionContainer from '@/components/ui/SectionContainer.vue';
import Breadcrumbs from '@/components/ui/Breadcrumbs.vue';
import AspectImage from '@/components/ui/AspectImage.vue';

/**
 * Judul halaman internal + breadcrumb + gambar banner opsional (PRD 6).
 */
const props = withDefaults(
    defineProps<{
        title: string;
        eyebrow?: string | null;
        description?: string | null;
        image?: string | null;
        breadcrumbs?: Array<{ label: string; href?: string | null }>;
    }>(),
    {
        eyebrow: null,
        description: null,
        image: null,
        breadcrumbs: () => [],
    },
);

const hasImage = Boolean(props.image);
</script>

<template>
    <SectionContainer tone="surface" padding="compact" bordered>
        <div
            class="grid gap-8"
            :class="
                hasImage
                    ? 'lg:grid-cols-[minmax(0,1fr)_minmax(0,320px)] lg:items-center'
                    : ''
            "
        >
            <div>
                <Breadcrumbs v-if="breadcrumbs.length" :items="breadcrumbs" />

                <p
                    v-if="eyebrow"
                    class="mt-5 text-xs font-semibold tracking-[0.18em] text-primary uppercase"
                >
                    {{ eyebrow }}
                </p>
                <h1
                    class="mt-2 text-3xl font-bold text-foreground sm:text-4xl"
                    :class="eyebrow ? '' : breadcrumbs.length ? 'mt-5' : ''"
                >
                    {{ title }}
                </h1>
                <p
                    v-if="description"
                    class="mt-3 max-w-2xl text-base leading-relaxed text-muted"
                >
                    {{ description }}
                </p>
            </div>

            <AspectImage
                v-if="hasImage"
                :src="image"
                :alt="title"
                ratio="wide"
            />
        </div>
    </SectionContainer>
</template>
