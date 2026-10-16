<script setup lang="ts">
import type { PageSection } from '@/types/cms';

/**
 * Fallback untuk section yang tidak dikenal (PRD 8.3): tidak boleh
 * menggagalkan seluruh halaman. Di produksi dirender tanpa output.
 */
defineProps<{ section: PageSection }>();

const showNotice = import.meta.env.DEV;
</script>

<template>
    <div v-if="showNotice" class="content-container py-6">
        <p
            class="rounded-card border border-dashed border-border bg-surface px-4 py-3 text-sm text-muted"
        >
            Section “{{ section.type }}” belum didukung renderer; konten
            dilewati agar halaman tetap tampil.
        </p>
    </div>
</template>
