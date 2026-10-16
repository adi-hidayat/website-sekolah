<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import type { PageSection } from '@/types/cms';

const props = defineProps<{ section: PageSection }>();
const data = computed(
    () => (props.section.data ?? {}) as Record<string, unknown>,
);
const cta = computed(
    () => (data.value.cta ?? {}) as { label?: string; url?: string },
);
</script>

<template>
    <section
        class="overflow-hidden rounded-2xl bg-emerald-800 px-6 py-12 text-white md:px-12"
    >
        <h1 class="max-w-2xl text-3xl font-bold md:text-4xl">
            {{ (data.title as string) ?? '' }}
        </h1>
        <p v-if="data.description" class="mt-3 max-w-xl text-emerald-100">
            {{ data.description as string }}
        </p>
        <Link
            v-if="cta.label && cta.url"
            :href="cta.url"
            class="mt-6 inline-block rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-emerald-800 hover:bg-emerald-50"
        >
            {{ cta.label }}
        </Link>
    </section>
</template>
