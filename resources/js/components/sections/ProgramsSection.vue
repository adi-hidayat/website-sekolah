<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
import ProgramCard from '@/components/content/ProgramCard.vue';
import { useSectionData } from '@/composables/useSectionData';
import { useSite } from '@/composables/useSite';
import { index as departmentsIndex } from '@/routes/departments';
import type { PageSection } from '@/types/cms';

/**
 * Program & jurusan (PRD 5.6). Grid 1–3 kolom dari tabel jurusan, admin
 * tidak perlu mengubah komponen untuk menambah jurusan.
 */
const props = defineProps<{ section: PageSection }>();

const { text, number } = useSectionData(props.section);
const { collections } = useSite();

const title = computed(() => text('title', 'Program dan Jurusan'));
const description = computed(() => text('description'));
const limit = computed(() => number('limit', 6));

const departments = computed(() =>
    collections.value.departments.slice(0, limit.value),
);
</script>

<template>
    <SectionContainer v-if="departments.length" :id="section.id" tone="default">
        <SectionHeading
            :eyebrow="text('eyebrow')"
            :title="title"
            :description="description"
        >
            <template #action>
                <BaseButton
                    :href="departmentsIndex().url"
                    variant="outline"
                    icon="arrow-right"
                >
                    Semua jurusan
                </BaseButton>
            </template>
        </SectionHeading>

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <ProgramCard
                v-for="department in departments"
                :key="department.id"
                :department="department"
            />
        </div>
    </SectionContainer>
</template>
