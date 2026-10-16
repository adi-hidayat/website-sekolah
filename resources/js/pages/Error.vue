<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import PageContainer from '@/components/ui/PageContainer.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { home } from '@/routes';

/**
 * Halaman error (PRD 6.6, 15). Diresolusi dari exception handler Inertia.
 */
const props = withDefaults(
    defineProps<{
        status?: number;
        message?: string | null;
    }>(),
    {
        status: 500,
        message: null,
    },
);

const titles: Record<number, string> = {
    403: 'Akses ditolak',
    404: 'Halaman tidak ditemukan',
    500: 'Terjadi kesalahan',
    503: 'Layanan tidak tersedia',
};

const descriptions: Record<number, string> = {
    403: 'Anda tidak memiliki izin untuk membuka halaman ini.',
    404: 'Halaman yang Anda cari tidak tersedia atau telah dipindahkan.',
    500: 'Terjadi gangguan pada server. Silakan coba beberapa saat lagi.',
    503: 'Layanan sedang dalam pemeliharaan. Silakan kembali nanti.',
};

const title = computed(
    () => props.message || titles[props.status] || 'Terjadi kesalahan',
);
const description = computed(
    () =>
        descriptions[props.status] ||
        'Terjadi gangguan yang tidak terduga. Silakan coba lagi.',
);
</script>

<template>
    <DefaultLayout>
        <Head :title="title" />

        <PageContainer class="py-20">
            <div class="mx-auto max-w-lg text-center">
                <p class="font-display text-6xl font-bold text-primary">
                    {{ status }}
                </p>
                <h1
                    class="mt-4 font-display text-2xl font-bold text-foreground"
                >
                    {{ title }}
                </h1>
                <p class="mt-3 text-base leading-relaxed text-muted">
                    {{ description }}
                </p>

                <div class="mt-8 flex flex-wrap justify-center gap-3">
                    <BaseButton :href="home().url" icon="arrow-left">
                        Kembali ke beranda
                    </BaseButton>
                </div>

                <p
                    class="mt-10 flex items-center justify-center gap-2 text-sm text-muted"
                >
                    <AppIcon name="info" :size="16" />
                    Jika masalah berlanjut, hubungi administrator sekolah.
                </p>
            </div>
        </PageContainer>
    </DefaultLayout>
</template>
