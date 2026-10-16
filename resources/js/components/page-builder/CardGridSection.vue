<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import type { PageSection } from '@/types/cms';

const props = defineProps<{ section: PageSection }>();
const title = computed(() => (props.section.data?.title as string) ?? '');
</script>

<template>
    <section>
        <h2 v-if="title" class="mb-4 text-xl font-bold">{{ title }}</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 p-5">
                <p class="text-sm text-slate-500">
                    Card grid dikonfigurasi melalui CMS.
                </p>
                <Link
                    href="/berita"
                    class="mt-2 inline-block text-sm font-semibold text-emerald-700"
                    >Lihat semua →</Link
                >
            </div>
        </div>
    </section>
</template>
