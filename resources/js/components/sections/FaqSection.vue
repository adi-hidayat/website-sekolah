<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { useSectionData } from '@/composables/useSectionData';
import type { PageSection } from '@/types/cms';

/**
 * FAQ dari CMS (PRD 8.2). Memakai elemen native <details> agar dapat
 * diakses via keyboard tanpa JavaScript tambahan.
 */
const props = defineProps<{ section: PageSection }>();

const { text, faq } = useSectionData(props.section);

const title = computed(() => text('title', 'Pertanyaan yang Sering Diajukan'));
const description = computed(() => text('description'));
const items = computed(() => faq('items'));
</script>

<template>
    <SectionContainer v-if="items.length" :id="section.id" tone="default">
        <SectionHeading
            :eyebrow="text('eyebrow')"
            :title="title"
            :description="description"
            align="center"
        />

        <div class="mx-auto mt-10 max-w-3xl space-y-3">
            <details
                v-for="(item, index) in items"
                :key="index"
                class="group rounded-card border border-border bg-background p-5"
            >
                <summary
                    class="flex cursor-pointer items-center justify-between gap-4 font-display text-base font-semibold text-foreground focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                >
                    {{ item.question }}
                    <AppIcon
                        name="chevron-down"
                        :size="18"
                        class="shrink-0 text-primary transition-transform group-open:rotate-180"
                    />
                </summary>
                <!-- eslint-disable-next-line vue/no-v-html -->
                <div
                    v-if="item.answer"
                    class="rich-text mt-3 text-sm text-muted"
                    v-html="item.answer"
                />
            </details>
        </div>
    </SectionContainer>
</template>
