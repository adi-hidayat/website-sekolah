<script setup lang="ts">
import { computed } from 'vue';
import type { PageSection } from '@/types/cms';

const props = defineProps<{ section: PageSection }>();
const title = computed(() => (props.section.data?.title as string) ?? '');
</script>

<template>
    <section class="rounded-xl border border-slate-200 p-6">
        <h2 v-if="title" class="mb-4 text-xl font-bold">{{ title }}</h2>
        <p class="text-sm text-slate-500">
            Konten {{ section.type }} ditampilkan sesuai konfigurasi CMS.
        </p>
        <dl class="mt-3 grid gap-2 text-sm">
            <div
                v-for="(value, key) in section.data as Record<string, unknown>"
                :key="String(key)"
                class="flex gap-2"
            >
                <dt class="font-medium text-slate-600">{{ key }}:</dt>
                <dd class="text-slate-800">
                    {{ String(value ?? '').slice(0, 160) }}
                </dd>
            </div>
        </dl>
    </section>
</template>
