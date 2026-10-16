<script setup lang="ts">
import AnnouncementBar from '@/components/layout/AnnouncementBar.vue';
import AppFooter from '@/components/layout/AppFooter.vue';
import AppHeader from '@/components/layout/AppHeader.vue';
import SiteStructuredData from '@/components/layout/SiteStructuredData.vue';
import { useSiteTheme } from '@/composables/useSiteTheme';

useSiteTheme();
</script>

<template>
    <div class="flex min-h-screen flex-col bg-background">
        <a
            href="#main-content"
            class="sr-only focus:not-sr-only focus:absolute focus:top-3 focus:left-3 focus:z-50 focus:rounded-button focus:bg-primary focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-white"
        >
            Lewati ke konten utama
        </a>

        <AnnouncementBar />
        <AppHeader />

        <main id="main-content" class="flex-1">
            <slot />
        </main>

        <AppFooter />
        <SiteStructuredData />
    </div>
</template>
