<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import { useSectionData } from '@/composables/useSectionData';
import type { PageSection } from '@/types/cms';

/**
 * Rich text dari CMS (PRD 8.2). HTML sudah disanitasi di backend
 * (PageSections::sanitizeHtml).
 */
const props = defineProps<{ section: PageSection }>();

const { text } = useSectionData(props.section);

const html = computed(() => text('html'));
const title = computed(() => text('title'));
</script>

<template>
    <SectionContainer v-if="html || title" :id="section.id" tone="default">
        <div class="mx-auto max-w-3xl">
            <SectionHeading
                v-if="title"
                :eyebrow="text('eyebrow')"
                :title="title"
            />
            <!-- eslint-disable-next-line vue/no-v-html -->
            <div class="rich-text mt-6" v-html="html" />
        </div>
    </SectionContainer>
</template>
