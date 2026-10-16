<script setup lang="ts">
import { computed } from 'vue';
import { useSite } from '@/composables/useSite';

/**
 * Structured data dasar (PRD 11): Organization & WebSite dari pengaturan
 * sekolah. Membantu mesin pencari memahami identitas situs.
 */
const { siteSettings } = useSite();

const data = computed(() => {
    const settings = siteSettings.value;
    const name = settings.school_name ?? 'SMA Islam';

    return {
        '@context': 'https://schema.org',
        '@graph': [
            {
                '@type': 'EducationalOrganization',
                name,
                alternateName: settings.short_name ?? undefined,
                url: settings.website ?? undefined,
                email: settings.email ?? undefined,
                telephone: settings.phone ?? undefined,
                address: settings.address
                    ? {
                          '@type': 'PostalAddress',
                          streetAddress: settings.address,
                      }
                    : undefined,
            },
            {
                '@type': 'WebSite',
                name,
            },
        ],
    };
});

const json = computed(() => JSON.stringify(data.value));
</script>

<template>
    <component :is="'script'" type="application/ld+json" v-text="json" />
</template>
