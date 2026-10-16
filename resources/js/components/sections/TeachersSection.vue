<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import TeacherCard from '@/components/content/TeacherCard.vue';
import { useSectionData } from '@/composables/useSectionData';
import { useSite } from '@/composables/useSite';
import type { PageSection } from '@/types/cms';

/**
 * Tenaga pendidik (PRD 8.2). Data dari tabel guru.
 */
const props = defineProps<{ section: PageSection }>();

const { text, number } = useSectionData(props.section);
const { collections } = useSite();

const title = computed(() => text('title', 'Tenaga Pendidik'));
const description = computed(() => text('description'));
const limit = computed(() => number('limit', 8));

const teachers = computed(() =>
    collections.value.teachers.slice(0, limit.value),
);
</script>

<template>
    <SectionContainer v-if="teachers.length" :id="section.id" tone="surface">
        <SectionHeading
            :eyebrow="text('eyebrow')"
            :title="title"
            :description="description"
        />

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <TeacherCard
                v-for="teacher in teachers"
                :key="teacher.id"
                :teacher="teacher"
            />
        </div>
    </SectionContainer>
</template>
