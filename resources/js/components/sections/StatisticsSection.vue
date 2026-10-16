<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import StatCard from '@/components/content/StatCard.vue';
import { useSectionData } from '@/composables/useSectionData';
import { useSite } from '@/composables/useSite';
import type { PageSection } from '@/types/cms';

/**
 * Ringkasan statistik sekolah (PRD 5.4). Angka berasal dari CMS atau
 * hitungan data valid; section disembunyikan bila kosong.
 */
const props = defineProps<{ section: PageSection }>();

const { text, stats } = useSectionData(props.section);
const { collections } = useSite();

const title = computed(() => text('title'));
const description = computed(() => text('description'));

const items = computed(() => {
    const configured = stats('items');

    return configured.length > 0 ? configured : collections.value.statistics;
});
</script>

<template>
    <SectionContainer
        v-if="items.length"
        :id="section.id"
        tone="surface"
        padding="compact"
        bordered
    >
        <SectionHeading
            v-if="title"
            :eyebrow="text('eyebrow')"
            :title="title"
            :description="description"
            align="center"
        />
        <dl
            class="mt-8 grid grid-cols-2 gap-6 sm:grid-cols-2 lg:grid-cols-4"
            :class="title ? '' : 'mt-0'"
        >
            <div
                v-for="stat in items"
                :key="stat.label"
                class="rounded-card border border-border bg-background px-5 py-6"
            >
                <StatCard :statistic="stat" />
            </div>
        </dl>
    </SectionContainer>
</template>
