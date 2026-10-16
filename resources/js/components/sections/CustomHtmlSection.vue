<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import { useSectionData } from '@/composables/useSectionData';
import type { PageSection } from '@/types/cms';

/**
 * HTML kustom (PRD 8.2). Konten sudah disanitasi di backend; hanya admin
 * yang dapat menyimpan section ini.
 */
const props = defineProps<{ section: PageSection }>();

const { text } = useSectionData(props.section);
const html = computed(() => text('html'));
</script>

<template>
    <SectionContainer v-if="html" :id="section.id">
        <!-- eslint-disable-next-line vue/no-v-html -->
        <div class="rich-text" v-html="html" />
    </SectionContainer>
</template>
