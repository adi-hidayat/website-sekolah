<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import type { PageSection } from '@/types/cms';

const props = defineProps<{ section: PageSection }>();
const title = computed(
    () => (props.section.data?.title as string) ?? 'Berita Terbaru',
);
</script>

<template>
    <section>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-bold">{{ title }}</h2>
            <Link href="/berita" class="text-sm font-semibold text-emerald-700"
                >Semua berita →</Link
            >
        </div>
        <p class="text-sm text-slate-500">
            Daftar berita terbaru dimuat dari halaman beranda.
        </p>
    </section>
</template>
