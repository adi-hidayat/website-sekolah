<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppIcon from '@/components/ui/AppIcon.vue';
import { useSite } from '@/composables/useSite';

/**
 * Bar pengumuman tipis di atas navbar (PRD 5.1). Muncul hanya bila ada
 * pengumuman penting aktif dari CMS, mendukung CTA, dan dapat ditutup.
 */
const { announcementBar } = useSite();

const dismissalKey = computed(() =>
    announcementBar.value
        ? `announcement-bar-dismissed:${announcementBar.value.id}`
        : '',
);

const dismissed = ref(false);

onMounted(() => {
    if (typeof window === 'undefined' || !dismissalKey.value) {
        return;
    }

    dismissed.value = window.sessionStorage.getItem(dismissalKey.value) === '1';
});

const visible = computed(
    () => Boolean(announcementBar.value) && !dismissed.value,
);

function dismiss(): void {
    dismissed.value = true;

    if (typeof window !== 'undefined' && dismissalKey.value) {
        window.sessionStorage.setItem(dismissalKey.value, '1');
    }
}
</script>

<template>
    <div v-if="visible && announcementBar" class="bg-primary-dark text-white">
        <div
            class="content-container flex items-center justify-between gap-4 py-2 text-sm"
        >
            <div class="flex min-w-0 items-center gap-2">
                <AppIcon name="info" :size="16" class="shrink-0 text-accent" />
                <p class="truncate">
                    {{ announcementBar.title }}
                </p>
            </div>

            <div class="flex shrink-0 items-center gap-2">
                <Link
                    :href="announcementBar.url"
                    class="hidden items-center gap-1 font-semibold text-accent hover:text-white sm:inline-flex"
                >
                    Selengkapnya
                    <AppIcon name="arrow-right" :size="14" />
                </Link>
                <button
                    type="button"
                    class="flex h-7 w-7 items-center justify-center rounded-full text-white/70 transition-colors hover:bg-white/10 hover:text-white"
                    aria-label="Tutup pengumuman"
                    @click="dismiss"
                >
                    <AppIcon name="close" :size="16" />
                </button>
            </div>
        </div>
    </div>
</template>
