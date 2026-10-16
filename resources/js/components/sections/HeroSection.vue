<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
import AspectImage from '@/components/ui/AspectImage.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import StatCard from '@/components/content/StatCard.vue';
import { useSectionData } from '@/composables/useSectionData';
import { useSite } from '@/composables/useSite';
import type { PageSection } from '@/types/cms';

/**
 * Hero homepage (PRD 5.3): kolom teks + foto autentik, ornamen geometris
 * subtil, statistik pendukung, CTA yang dikonfigurasi CMS.
 */
const props = defineProps<{ section: PageSection }>();

const { text, stats, cta } = useSectionData(props.section);
const { siteSettings, admissions, collections } = useSite();

const eyebrow = computed(() => text('eyebrow', 'Sekolah Menengah Atas Islam'));
const title = computed(() =>
    text('title', siteSettings.value.school_name ?? 'SMA Islam'),
);
const description = computed(() => text('description'));
const image = computed(() => text('image', '') || null);

const primary = computed(() => {
    const button = cta('cta');

    return {
        label:
            button.label ||
            (admissions.value.active
                ? admissions.value.label
                : 'Daftar Sekarang'),
        url:
            button.url ||
            (admissions.value.active ? admissions.value.url : '#program'),
    };
});

const secondary = computed(() => {
    const button = cta('secondary');

    return {
        label: button.label || 'Jelajahi Sekolah',
        url: button.url || '#program',
    };
});

const statistics = computed(() => {
    const configured = stats('statistics');

    return configured.length > 0 ? configured : collections.value.statistics;
});
</script>

<template>
    <SectionContainer
        id="hero"
        tone="surface"
        padding="normal"
        class="overflow-hidden"
    >
        <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div>
                <p
                    class="inline-flex items-center gap-2 text-xs font-semibold tracking-[0.18em] text-primary uppercase"
                >
                    <span class="h-px w-6 bg-accent"></span>
                    {{ eyebrow }}
                </p>

                <h1
                    class="mt-4 text-3xl leading-tight font-bold text-foreground sm:text-4xl lg:text-5xl"
                >
                    {{ title }}
                </h1>

                <p
                    v-if="description"
                    class="mt-5 max-w-xl text-base leading-relaxed text-muted sm:text-lg"
                >
                    {{ description }}
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <BaseButton
                        :href="primary.url"
                        size="lg"
                        icon="arrow-right"
                    >
                        {{ primary.label }}
                    </BaseButton>
                    <BaseButton
                        :href="secondary.url"
                        size="lg"
                        variant="outline"
                        icon="arrow-right"
                    >
                        {{ secondary.label }}
                    </BaseButton>
                </div>

                <dl
                    v-if="statistics.length"
                    class="mt-10 grid grid-cols-2 gap-6 border-t border-border pt-8 sm:grid-cols-3"
                >
                    <div
                        v-for="stat in statistics.slice(0, 3)"
                        :key="stat.label"
                    >
                        <StatCard :statistic="stat" />
                    </div>
                </dl>
            </div>

            <div class="relative">
                <!-- Ornamen geometris Islami, subtil (PRD 3.1). -->
                <div
                    class="absolute -top-6 -right-6 -z-10 h-40 w-40 rounded-[2rem] bg-accent/30"
                    aria-hidden="true"
                ></div>
                <div
                    class="absolute -bottom-8 -left-6 -z-10 h-32 w-32 rounded-full border border-accent/50"
                    aria-hidden="true"
                ></div>

                <AspectImage
                    :src="image"
                    :alt="title"
                    ratio="portrait"
                    eager
                    class="relative"
                />

                <div
                    v-if="siteSettings.accreditation"
                    class="absolute bottom-4 left-4 flex items-center gap-2 rounded-button bg-background/95 px-3 py-2 text-sm font-semibold text-primary-dark shadow-soft"
                >
                    <AppIcon name="shield-check" :size="18" />
                    Akreditasi {{ siteSettings.accreditation }}
                </div>
            </div>
        </div>
    </SectionContainer>
</template>
