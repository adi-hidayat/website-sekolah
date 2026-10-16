<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import AspectImage from '@/components/ui/AspectImage.vue';
import { useSectionData } from '@/composables/useSectionData';
import type { PageSection } from '@/types/cms';

/**
 * Gambar tunggal dari CMS (PRD 8.2).
 */
const props = defineProps<{ section: PageSection }>();

const { text } = useSectionData(props.section);

const src = computed(() => text('src', '') || null);
const caption = computed(() => text('caption'));
const alignment = computed(() => text('alignment', 'center'));

const wrapperClass = computed(
    () =>
        ({
            left: 'mr-auto',
            center: 'mx-auto',
            right: 'ml-auto',
        })[alignment.value] ?? 'mx-auto',
);
</script>

<template>
    <SectionContainer v-if="src" :id="section.id" padding="compact">
        <figure class="max-w-4xl" :class="wrapperClass">
            <AspectImage :src="src" :alt="caption || 'Gambar'" ratio="wide" />
            <figcaption
                v-if="caption"
                class="mt-2 text-center text-sm text-muted"
            >
                {{ caption }}
            </figcaption>
        </figure>
    </SectionContainer>
</template>
