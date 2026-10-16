<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import PageHero from '@/components/layout/PageHero.vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import Breadcrumbs from '@/components/ui/Breadcrumbs.vue';
import PageRenderer from '@/components/page-builder/PageRenderer.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import type { PageSection, SeoMeta } from '@/types/cms';

/**
 * Halaman dinamis dari CMS (PRD 6.1, 6.6). Section dirender lewat
 * PageRenderer; jika belum ada section, konten rich text ditampilkan.
 */
const props = defineProps<{
    page: {
        title: string;
        slug: string;
        excerpt: string | null;
        content: string | null;
        template: string;
        featured_image: string | null;
        sections: PageSection[];
        breadcrumbs: Array<{ label: string; href?: string | null }>;
        seo: SeoMeta;
    };
}>();

const hasSections = computed(() => props.page.sections.length > 0);
const hasContent = computed(() => Boolean(props.page.content));
const isEmpty = computed(() => !hasSections.value && !hasContent.value);
</script>

<template>
    <DefaultLayout>
        <Head :title="page.seo.title ?? page.title">
            <meta
                v-if="page.seo.description"
                name="description"
                :content="page.seo.description"
            />
            <meta property="og:title" :content="page.title" />
            <meta
                v-if="page.seo.description"
                property="og:description"
                :content="page.seo.description"
            />
            <link
                v-if="page.seo.canonical"
                rel="canonical"
                :href="page.seo.canonical"
            />
        </Head>

        <template v-if="hasSections">
            <SectionContainer padding="compact" tone="surface" bordered>
                <Breadcrumbs :items="page.breadcrumbs" />
            </SectionContainer>
            <PageRenderer :sections="page.sections" />
        </template>

        <template v-else>
            <PageHero
                :title="page.title"
                :description="page.excerpt"
                :image="page.featured_image"
                :breadcrumbs="page.breadcrumbs"
            />

            <SectionContainer v-if="hasContent">
                <div class="mx-auto max-w-3xl">
                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <article class="rich-text" v-html="page.content" />
                </div>
            </SectionContainer>

            <SectionContainer v-else-if="isEmpty">
                <EmptyState
                    icon="info"
                    title="Halaman belum memiliki konten"
                    description="Konten halaman ini sedang disiapkan."
                />
            </SectionContainer>
        </template>
    </DefaultLayout>
</template>
