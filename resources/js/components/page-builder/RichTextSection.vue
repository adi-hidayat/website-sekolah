<script setup lang="ts">
import { computed } from 'vue';
import type { PageSection } from '@/types/cms';

const props = defineProps<{ section: PageSection }>();
const html = computed(() => (props.section.data?.html as string) ?? '');
</script>

<template>
    <!-- eslint-disable-next-line vue/no-v-html -->
    <section class="prose max-w-none" v-html="html" />
</template>
