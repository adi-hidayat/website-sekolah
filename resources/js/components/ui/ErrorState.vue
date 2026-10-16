<script setup lang="ts">
import AppIcon from '@/components/ui/AppIcon.vue';
import BaseButton from '@/components/ui/BaseButton.vue';

/**
 * Keadaan error yang tidak mematikan halaman (PRD 6.3 & 14).
 */
withDefaults(
    defineProps<{
        title?: string;
        description?: string | null;
        retryLabel?: string;
    }>(),
    {
        title: 'Terjadi kesalahan',
        description:
            'Konten gagal dimuat. Silakan coba lagi atau kembali ke beranda.',
        retryLabel: 'Coba lagi',
    },
);

const emit = defineEmits<{ retry: [] }>();
</script>

<template>
    <div
        class="flex flex-col items-center justify-center rounded-card border border-border bg-surface px-6 py-14 text-center"
    >
        <span
            class="flex h-12 w-12 items-center justify-center rounded-full bg-gold-light text-gold"
        >
            <AppIcon name="alert" :size="24" />
        </span>
        <h3 class="mt-4 text-lg font-semibold text-foreground">{{ title }}</h3>
        <p v-if="description" class="mt-1.5 max-w-md text-sm text-muted">
            {{ description }}
        </p>
        <div class="mt-5">
            <BaseButton variant="outline" @click="emit('retry')">
                {{ retryLabel }}
            </BaseButton>
        </div>
    </div>
</template>
