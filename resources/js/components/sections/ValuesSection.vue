<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import ValueCard from '@/components/content/ValueCard.vue';
import { toStringValue } from '@/lib/data';
import { useSectionData } from '@/composables/useSectionData';
import type { PageSection } from '@/types/cms';

/**
 * Nilai & karakter Islami (PRD 5.7). Item dapat disesuaikan sekolah lewat
 * CMS (JSON daftar nilai).
 */
const props = defineProps<{ section: PageSection }>();

const { text, records } = useSectionData(props.section);

const title = computed(() => text('title', 'Nilai dan Karakter Islami'));
const description = computed(() => text('description'));

const items = computed(() =>
    records('items')
        .map((item) => ({
            icon: toStringValue(item.icon, 'sparkles'),
            title: toStringValue(item.title ?? item.name),
            description: toStringValue(item.description ?? item.text),
        }))
        .filter((item) => item.title !== ''),
);
</script>

<template>
    <SectionContainer v-if="items.length" :id="section.id" tone="primary">
        <SectionHeading
            :eyebrow="text('eyebrow')"
            :title="title"
            :description="description"
            align="center"
        />

        <div
            class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3"
            :class="items.length % 3 === 0 ? '' : 'justify-center'"
        >
            <ValueCard
                v-for="(item, index) in items"
                :key="index"
                :icon="item.icon"
                :title="item.title"
                :description="item.description"
            />
        </div>
    </SectionContainer>
</template>
