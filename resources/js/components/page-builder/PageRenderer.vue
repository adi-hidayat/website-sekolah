<script setup lang="ts">
import { computed } from 'vue';
import type { PageSection } from '@/types/cms';
import { resolveSection } from '@/components/page-builder/SectionRegistry';

/**
 * Merender section dari CMS sesuai urutan dan registry (PRD 8.2, 8.3).
 * Section tak dikenal ditangani SectionFallback agar halaman tidak gagal.
 */
const props = withDefaults(defineProps<{ sections?: PageSection[] }>(), {
    sections: () => [],
});

const visibleSections = computed(() =>
    (props.sections ?? []).filter(
        (section): section is PageSection =>
            Boolean(section) && typeof section.type === 'string',
    ),
);
</script>

<template>
    <div class="flex flex-col">
        <component
            :is="resolveSection(section.type)"
            v-for="section in visibleSections"
            :key="section.id"
            :section="section"
        />
    </div>
</template>
