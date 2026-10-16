<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { useSectionData } from '@/composables/useSectionData';
import { useSite } from '@/composables/useSite';
import type { CtaData } from '@/composables/useSectionData';
import type { PageSection } from '@/types/cms';

/**
 * CTA SPMB dengan latar hijau gelap (PRD 5.13). Otomatis tersembunyi bila
 * periode pendaftaran tidak aktif dan tidak ada tombol yang dikonfigurasi.
 */
const props = defineProps<{ section: PageSection }>();

const { text, cta } = useSectionData(props.section);
const { admissions } = useSite();

const title = computed(() =>
    text('title', 'Mulai Perjalanan Pendidikan yang Bermakna.'),
);
const description = computed(() =>
    text(
        'description',
        'Kenali program pendidikan dan lingkungan belajar kami untuk membantu mempersiapkan masa depan putra-putri Anda.',
    ),
);

const primary = computed<CtaData | null>(() => {
    const button = cta('button');

    if (button.label || button.url) {
        return {
            label: button.label || 'Informasi Pendaftaran',
            url: button.url || admissions.value.url,
            target: button.target,
        };
    }

    if (admissions.value.active) {
        return {
            label: admissions.value.label,
            url: admissions.value.url,
            target: '_self',
        };
    }

    return null;
});

const secondary = computed<CtaData | null>(() => {
    const button = cta('secondary_button');

    if (!button.label && !button.url) {
        return null;
    }

    return {
        label: button.label || 'Hubungi Sekolah',
        url: button.url,
        target: button.target,
    };
});
</script>

<template>
    <SectionContainer v-if="primary" :id="section.id" tone="dark">
        <div
            class="flex flex-col items-center gap-8 text-center md:flex-row md:justify-between md:text-left"
        >
            <div class="max-w-2xl">
                <p
                    v-if="admissions.period_text"
                    class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold text-accent"
                >
                    <AppIcon name="calendar" :size="14" />
                    {{ admissions.period_text }}
                </p>
                <h2 class="mt-4 text-2xl font-bold text-white sm:text-3xl">
                    {{ title }}
                </h2>
                <p
                    v-if="description"
                    class="mt-3 text-base leading-relaxed text-white/75"
                >
                    {{ description }}
                </p>
            </div>

            <div class="flex shrink-0 flex-col gap-3 sm:flex-row">
                <BaseButton
                    :href="primary.url"
                    :target="primary.target"
                    variant="white"
                    size="lg"
                    icon="arrow-right"
                >
                    {{ primary.label }}
                </BaseButton>
                <BaseButton
                    v-if="secondary"
                    :href="secondary.url"
                    :target="secondary.target"
                    variant="outline-inverse"
                    size="lg"
                >
                    {{ secondary.label }}
                </BaseButton>
            </div>
        </div>
    </SectionContainer>
</template>
