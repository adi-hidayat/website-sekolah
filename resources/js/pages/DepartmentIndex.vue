<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import PageHero from '@/components/layout/PageHero.vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import ProgramCard from '@/components/content/ProgramCard.vue';
import EmptyState from '@/components/ui/EmptyState.vue';
import type { Department } from '@/types/cms';

/**
 * Daftar jurusan (PRD 6.2).
 */
defineProps<{ departments: Department[] }>();
</script>

<template>
    <DefaultLayout>
        <Head title="Program Jurusan">
            <meta
                name="description"
                content="Program dan jurusan yang tersedia di sekolah."
            />
        </Head>

        <PageHero
            eyebrow="Akademik"
            title="Program dan Jurusan"
            description="Pilihan program yang dirancang untuk mengembangkan kompetensi akademik dan keterampilan peserta didik."
            :breadcrumbs="[
                { label: 'Beranda', href: '/' },
                { label: 'Jurusan' },
            ]"
        />

        <SectionContainer>
            <div
                v-if="departments.length"
                class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3"
            >
                <ProgramCard
                    v-for="department in departments"
                    :key="department.id"
                    :department="department"
                />
            </div>

            <EmptyState
                v-else
                icon="academic-cap"
                title="Belum ada program"
                description="Program dan jurusan akan tampil di sini setelah ditambahkan."
            />
        </SectionContainer>
    </DefaultLayout>
</template>
