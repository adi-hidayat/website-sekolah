<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import { formatDate } from '@/lib/format';
import { useSectionData } from '@/composables/useSectionData';
import { useSite } from '@/composables/useSite';
import {
    index as announcementsIndex,
    show as announcementShow,
} from '@/routes/announcements';
import type { PageSection } from '@/types/cms';

/**
 * Pengumuman pada halaman dinamis (PRD 6.5).
 */
const props = defineProps<{ section: PageSection }>();

const { text, number } = useSectionData(props.section);
const { collections } = useSite();

const title = computed(() => text('title', 'Pengumuman'));
const description = computed(() => text('description'));
const limit = computed(() => number('limit', 5));

const announcements = computed(() => {
    const items = [...collections.value.announcements];
    items.sort((a, b) => Number(b.is_pinned) - Number(a.is_pinned));

    return items.slice(0, limit.value);
});
</script>

<template>
    <SectionContainer :id="section.id" tone="default">
        <SectionHeading
            :eyebrow="text('eyebrow')"
            :title="title"
            :description="description"
        >
            <template #action>
                <Link
                    :href="announcementsIndex().url"
                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-dark"
                >
                    Semua pengumuman
                    <AppIcon name="arrow-right" :size="16" />
                </Link>
            </template>
        </SectionHeading>

        <div v-if="announcements.length" class="mt-8 space-y-3">
            <Link
                v-for="item in announcements"
                :key="item.id"
                :href="announcementShow(item.slug).url"
                class="flex flex-col gap-1 rounded-card border border-border bg-background p-4 transition-colors hover:border-primary sm:flex-row sm:items-center sm:justify-between"
            >
                <span class="flex items-center gap-2">
                    <span
                        v-if="item.is_pinned"
                        class="inline-flex items-center gap-1 rounded-full bg-gold-light px-2 py-0.5 text-xs font-semibold text-foreground/80"
                    >
                        <AppIcon name="star" :size="12" />
                        Penting
                    </span>
                    <span class="font-medium text-foreground">
                        {{ item.title }}
                    </span>
                </span>
                <span
                    v-if="item.starts_at || item.ends_at"
                    class="shrink-0 text-xs text-muted"
                >
                    {{ formatDate(item.starts_at ?? item.ends_at) }}
                </span>
            </Link>
        </div>

        <EmptyState
            v-else
            class="mt-8"
            icon="calendar"
            title="Belum ada pengumuman"
            description="Pengumuman terbaru akan tampil di sini setelah dipublikasikan."
        />
    </SectionContainer>
</template>
