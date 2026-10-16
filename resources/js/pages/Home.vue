<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import PageRenderer from '@/components/page-builder/PageRenderer.vue';
import { defaultHomepageSections } from '@/lib/homeSections';
import type { PageSection, SeoMeta } from '@/types/cms';

/**
 * Homepage (PRD 5). Seluruh section dirender dari CMS; bila admin belum
 * menyusun section, komposisi default dipakai agar halaman tetap lengkap.
 */
const props = defineProps<{
    homepage: {
        title: string;
        sections: PageSection[];
        seo: SeoMeta;
    } | null;
}>();

const sections = computed<PageSection[]>(() =>
    props.homepage?.sections?.length
        ? props.homepage.sections
        : defaultHomepageSections(),
);

const seoTitle = computed(() => props.homepage?.seo?.title ?? 'Beranda');
const seoDescription = computed(() => props.homepage?.seo?.description ?? null);
</script>

<template>
    <DefaultLayout>
        <Head :title="seoTitle">
            <meta
                v-if="seoDescription"
                name="description"
                :content="seoDescription"
            />
            <meta property="og:title" :content="seoTitle" />
            <meta
                v-if="seoDescription"
                property="og:description"
                :content="seoDescription"
            />
            <meta property="og:type" content="website" />
        </Head>

        <PageRenderer :sections="sections" />
    </DefaultLayout>
</template>
