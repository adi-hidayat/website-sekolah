<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
import NewsCard from '@/components/content/NewsCard.vue';
import { useSectionData } from '@/composables/useSectionData';
import { useSite } from '@/composables/useSite';
import { index as newsIndex } from '@/routes/news';
import type { PageSection } from '@/types/cms';

/**
 * Berita & kegiatan dengan pola editorial (PRD 5.9): satu berita utama
 * besar dan tiga berita pendukung.
 */
const props = defineProps<{ section: PageSection }>();

const { text, number } = useSectionData(props.section);
const { collections } = useSite();

const title = computed(() => text('title', 'Berita dan Kegiatan'));
const description = computed(() => text('description'));
const limit = computed(() => number('limit', 4));

const news = computed(() => collections.value.news.slice(0, limit.value));
const featured = computed(() => news.value[0] ?? null);
const supporting = computed(() => news.value.slice(1, 4));
</script>

<template>
    <SectionContainer v-if="featured" :id="section.id" tone="default">
        <SectionHeading
            :eyebrow="text('eyebrow')"
            :title="title"
            :description="description"
        >
            <template #action>
                <BaseButton
                    :href="newsIndex().url"
                    variant="outline"
                    icon="arrow-right"
                >
                    Semua berita
                </BaseButton>
            </template>
        </SectionHeading>

        <div class="mt-10 space-y-8">
            <NewsCard :news="featured" variant="featured" />

            <div
                v-if="supporting.length"
                class="grid gap-6 border-t border-border pt-8 sm:grid-cols-2 lg:grid-cols-3"
            >
                <NewsCard
                    v-for="item in supporting"
                    :key="item.id"
                    :news="item"
                />
            </div>
        </div>
    </SectionContainer>
</template>
