<script setup lang="ts">
import { computed } from 'vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { telUrl, whatsappUrl } from '@/lib/format';
import { useSectionData } from '@/composables/useSectionData';
import { useSite } from '@/composables/useSite';
import type { PageSection } from '@/types/cms';

/**
 * Kontak sekolah (PRD 8.2), dengan fallback pengaturan sekolah.
 */
const props = defineProps<{ section: PageSection }>();

const { text } = useSectionData(props.section);
const { siteSettings } = useSite();

const title = computed(() => text('title', 'Hubungi Kami'));
const description = computed(() => text('description'));
const address = computed(() =>
    text('address', siteSettings.value.address ?? ''),
);
const phone = computed(() => text('phone', siteSettings.value.phone ?? ''));
const email = computed(() => text('email', siteSettings.value.email ?? ''));
const hours = computed(() => text('hours', 'Senin–Jumat, 07.00–15.00 WIB'));
const mapUrl = computed(
    () =>
        text('maps_embed_url', siteSettings.value.maps_embed_url ?? '') || null,
);

const visible = computed(() =>
    Boolean(address.value || phone.value || email.value),
);
</script>

<template>
    <SectionContainer v-if="visible" :id="section.id" tone="surface">
        <SectionHeading
            :eyebrow="text('eyebrow')"
            :title="title"
            :description="description"
        />

        <div class="mt-10 grid gap-10 lg:grid-cols-2">
            <ul class="space-y-5">
                <li v-if="address" class="flex gap-3">
                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary"
                    >
                        <AppIcon name="map-pin" :size="20" />
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-foreground">
                            Alamat
                        </p>
                        <p class="text-sm text-muted">{{ address }}</p>
                    </div>
                </li>
                <li v-if="phone" class="flex gap-3">
                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary"
                    >
                        <AppIcon name="phone" :size="20" />
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-foreground">
                            Telepon
                        </p>
                        <a
                            :href="telUrl(phone) ?? '#'"
                            class="text-sm text-muted hover:text-primary"
                            >{{ phone }}</a
                        >
                        <a
                            v-if="whatsappUrl(phone)"
                            :href="whatsappUrl(phone) ?? '#'"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="ml-2 text-sm font-medium text-primary hover:text-primary-dark"
                            >WhatsApp</a
                        >
                    </div>
                </li>
                <li v-if="email" class="flex gap-3">
                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary"
                    >
                        <AppIcon name="mail" :size="20" />
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-foreground">
                            Email
                        </p>
                        <a
                            :href="`mailto:${email}`"
                            class="text-sm text-muted hover:text-primary"
                            >{{ email }}</a
                        >
                    </div>
                </li>
                <li v-if="hours" class="flex gap-3">
                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-light text-primary"
                    >
                        <AppIcon name="clock" :size="20" />
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-foreground">
                            Jam operasional
                        </p>
                        <p class="text-sm text-muted">{{ hours }}</p>
                    </div>
                </li>
            </ul>

            <div
                v-if="mapUrl"
                class="overflow-hidden rounded-card border border-border"
            >
                <iframe
                    :src="mapUrl"
                    title="Peta lokasi sekolah"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    class="h-72 w-full lg:h-full"
                ></iframe>
            </div>
        </div>
    </SectionContainer>
</template>
