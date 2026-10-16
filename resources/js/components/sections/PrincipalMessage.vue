<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
import AspectImage from '@/components/ui/AspectImage.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { useSectionData } from '@/composables/useSectionData';
import { useSite } from '@/composables/useSite';
import type { PageSection } from '@/types/cms';

/**
 * Sambutan kepala sekolah dengan tata letak editorial (PRD 5.5), bukan
 * kartu generik. Nilai default berasal dari pengaturan sekolah.
 */
const props = defineProps<{ section: PageSection }>();

const { text, cta } = useSectionData(props.section);
const { siteSettings } = useSite();

const eyebrow = computed(() => text('eyebrow', 'Sambutan'));
const title = computed(() => text('title', 'Sambutan Kepala Sekolah'));
const name = computed(() =>
    text('name', siteSettings.value.principal_name ?? ''),
);
const position = computed(() => text('position', 'Kepala Sekolah'));
const photo = computed(
    () => text('photo', siteSettings.value.principal_photo ?? '') || null,
);
const message = computed(() =>
    text('message', siteSettings.value.principal_welcome ?? ''),
);

const visible = computed(() => Boolean(message.value || name.value));

const action = computed(() => {
    const button = cta('cta', 'label', 'url');

    return {
        label: button.label || 'Lihat Profil Lengkap',
        url: button.url || '/profil-sekolah',
    };
});
</script>

<template>
    <SectionContainer
        v-if="visible"
        :id="section.id"
        tone="default"
        class="overflow-hidden"
    >
        <div
            class="grid gap-10 lg:grid-cols-[minmax(0,320px)_minmax(0,1fr)] lg:gap-16"
        >
            <div class="relative">
                <AspectImage
                    :src="photo"
                    :alt="name || title"
                    ratio="portrait"
                />
                <div
                    class="absolute -right-3 -bottom-3 -z-10 h-24 w-24 rounded-card border border-accent/60"
                    aria-hidden="true"
                ></div>
            </div>

            <div class="lg:pt-6">
                <p
                    class="text-xs font-semibold tracking-[0.18em] text-primary uppercase"
                >
                    {{ eyebrow }}
                </p>
                <h2 class="mt-3 text-2xl font-bold text-foreground sm:text-3xl">
                    {{ title }}
                </h2>

                <div
                    v-if="message"
                    class="relative mt-6 border-l-2 border-accent pl-6"
                >
                    <AppIcon
                        name="quote"
                        :size="28"
                        class="absolute -top-2 -left-3 bg-background text-accent"
                    />
                    <!-- eslint-disable-next-line vue/no-v-html -->
                    <div
                        class="rich-text text-lg leading-relaxed text-foreground/85"
                        v-html="message"
                    />
                </div>

                <div v-if="name" class="mt-8">
                    <p
                        class="font-display text-base font-semibold text-foreground"
                    >
                        {{ name }}
                    </p>
                    <p class="text-sm text-muted">{{ position }}</p>
                </div>

                <BaseButton
                    :href="action.url"
                    variant="outline"
                    class="mt-6"
                    icon="arrow-right"
                >
                    {{ action.label }}
                </BaseButton>
            </div>
        </div>
    </SectionContainer>
</template>
