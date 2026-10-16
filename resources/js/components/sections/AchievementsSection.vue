<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import AchievementItem from '@/components/content/AchievementItem.vue';
import { useSectionData } from '@/composables/useSectionData';
import { useSite } from '@/composables/useSite';
import type { PageSection } from '@/types/cms';

/**
 * Prestasi siswa dan sekolah (PRD 5.10). Aksen gold terbatas pada item
 * unggulan; hijau tetap dominan.
 */
const props = defineProps<{ section: PageSection }>();

const { text, number } = useSectionData(props.section);
const { collections } = useSite();

const title = computed(() => text('title', 'Prestasi'));
const description = computed(() => text('description'));
const limit = computed(() => number('limit', 6));

const achievements = computed(() =>
    collections.value.achievements.slice(0, limit.value),
);
</script>

<template>
    <SectionContainer
        v-if="achievements.length"
        :id="section.id"
        tone="surface"
    >
        <SectionHeading
            :eyebrow="text('eyebrow')"
            :title="title"
            :description="description"
            align="center"
        />

        <div class="mt-10 grid gap-4 sm:grid-cols-2">
            <AchievementItem
                v-for="(achievement, index) in achievements"
                :key="achievement.id"
                :achievement="achievement"
                :highlight="index === 0"
            />
        </div>
    </SectionContainer>
</template>
