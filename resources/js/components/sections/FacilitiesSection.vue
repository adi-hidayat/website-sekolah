<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import FacilityCard from '@/components/content/FacilityCard.vue';
import { useSectionData } from '@/composables/useSectionData';
import { useSite } from '@/composables/useSite';
import type { PageSection } from '@/types/cms';

/**
 * Fasilitas dengan foto besar dan komposisi bervariasi (PRD 5.11).
 * Fasilitas tanpa gambar tidak menghasilkan ruang kosong.
 */
const props = defineProps<{ section: PageSection }>();

const { text, number } = useSectionData(props.section);
const { collections } = useSite();

const title = computed(() => text('title', 'Fasilitas'));
const description = computed(() => text('description'));
const limit = computed(() => number('limit', 6));

const facilities = computed(() =>
    collections.value.facilities.slice(0, limit.value),
);
</script>

<template>
    <SectionContainer v-if="facilities.length" :id="section.id" tone="default">
        <SectionHeading
            :eyebrow="text('eyebrow')"
            :title="title"
            :description="description"
        />

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="(facility, index) in facilities"
                :key="facility.id"
                :class="
                    index === 0 && facilities.length > 2
                        ? 'sm:col-span-2 lg:col-span-2'
                        : ''
                "
            >
                <FacilityCard
                    :facility="facility"
                    :large="index === 0 && facilities.length > 2"
                    class="h-full"
                />
            </div>
        </div>
    </SectionContainer>
</template>
