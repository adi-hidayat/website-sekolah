<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { toStringValue } from '@/lib/data';
import { useSectionData } from '@/composables/useSectionData';
import type { PageSection } from '@/types/cms';

/**
 * Grid kartu generik dari CMS (PRD 8.2). Card tanpa konten valid dilewati.
 */
const props = defineProps<{ section: PageSection }>();

const { text, records } = useSectionData(props.section);

const title = computed(() => text('title'));
const description = computed(() => text('description'));

const cards = computed(() =>
    records('cards')
        .map((card) => ({
            icon: toStringValue(card.icon, 'sparkles'),
            title: toStringValue(card.title ?? card.name),
            description: toStringValue(card.description ?? card.text),
            url: toStringValue(card.url ?? card.link),
            linkLabel: toStringValue(card.link_label, 'Selengkapnya'),
        }))
        .filter((card) => card.title !== '' || card.description !== ''),
);
</script>

<template>
    <SectionContainer v-if="cards.length" :id="section.id" tone="default">
        <SectionHeading
            :eyebrow="text('eyebrow')"
            :title="title"
            :description="description"
        />

        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="(card, index) in cards"
                :key="index"
                class="flex flex-col rounded-card border border-border bg-background p-6"
            >
                <span
                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-light text-primary"
                >
                    <AppIcon :name="card.icon" :size="22" />
                </span>
                <h3
                    v-if="card.title"
                    class="mt-4 font-display text-lg font-semibold text-foreground"
                >
                    {{ card.title }}
                </h3>
                <p
                    v-if="card.description"
                    class="mt-2 flex-1 text-sm leading-relaxed text-muted"
                >
                    {{ card.description }}
                </p>
                <Link
                    v-if="card.url"
                    :href="card.url"
                    class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-dark"
                >
                    {{ card.linkLabel }}
                    <AppIcon name="arrow-right" :size="16" />
                </Link>
            </div>
        </div>
    </SectionContainer>
</template>
