<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import PageHero from '@/components/layout/PageHero.vue';
import SectionContainer from '@/components/ui/SectionContainer.vue';
import SectionHeading from '@/components/ui/SectionHeading.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import ProgramCard from '@/components/content/ProgramCard.vue';
import TeacherCard from '@/components/content/TeacherCard.vue';
import AchievementItem from '@/components/content/AchievementItem.vue';
import FacilityCard from '@/components/content/FacilityCard.vue';
import { useSite } from '@/composables/useSite';
import { index as departmentsIndex } from '@/routes/departments';
import type { Department, SeoMeta } from '@/types/cms';

/**
 * Detail jurusan (PRD 6.2): deskripsi, kompetensi, guru, prestasi,
 * fasilitas pendukung, program terkait, dan CTA pendaftaran.
 */
const props = defineProps<{
    department: Department;
    related: Department[];
    seo: SeoMeta;
}>();

const { admissions, collections } = useSite();

const competencies = computed(() => props.department.competencies ?? []);
const teachers = computed(() => props.department.teachers ?? []);
const achievements = computed(() => props.department.achievements ?? []);
const facilities = computed(() => collections.value.facilities.slice(0, 3));
const ctaUrl = computed(() =>
    admissions.value.active ? admissions.value.url : '/pengumuman',
);
</script>

<template>
    <DefaultLayout>
        <Head :title="seo.title ?? department.name">
            <meta
                v-if="seo.description"
                name="description"
                :content="seo.description"
            />
            <meta property="og:title" :content="department.name" />
            <meta
                v-if="seo.description"
                property="og:description"
                :content="seo.description"
            />
        </Head>

        <PageHero
            :title="department.name"
            :eyebrow="department.code"
            :description="department.short_description"
            :image="department.banner ?? department.thumbnail"
            :breadcrumbs="[
                { label: 'Beranda', href: '/' },
                { label: 'Jurusan', href: departmentsIndex().url },
                { label: department.name },
            ]"
        />

        <SectionContainer v-if="department.description">
            <div class="mx-auto max-w-3xl">
                <!-- eslint-disable-next-line vue/no-v-html -->
                <div class="rich-text" v-html="department.description" />
            </div>
        </SectionContainer>

        <SectionContainer v-if="competencies.length" tone="surface">
            <SectionHeading eyebrow="Kompetensi" title="Kompetensi Unggulan" />
            <ul class="mt-8 grid gap-3 sm:grid-cols-2">
                <li
                    v-for="item in competencies"
                    :key="item"
                    class="flex items-start gap-3 rounded-card border border-border bg-background p-4"
                >
                    <AppIcon
                        name="check-circle"
                        :size="20"
                        class="mt-0.5 shrink-0 text-primary"
                    />
                    <span class="text-sm text-foreground">{{ item }}</span>
                </li>
            </ul>
        </SectionContainer>

        <SectionContainer v-if="achievements.length">
            <SectionHeading eyebrow="Capaian" title="Prestasi Jurusan" />
            <div class="mt-8 grid gap-4 sm:grid-cols-2">
                <AchievementItem
                    v-for="(achievement, index) in achievements"
                    :key="achievement.id"
                    :achievement="achievement"
                    :highlight="index === 0"
                />
            </div>
        </SectionContainer>

        <SectionContainer v-if="teachers.length" tone="surface">
            <SectionHeading
                eyebrow="Pengajar"
                title="Guru dan Tenaga Pendidik"
            />
            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <TeacherCard
                    v-for="teacher in teachers"
                    :key="teacher.id"
                    :teacher="teacher"
                />
            </div>
        </SectionContainer>

        <SectionContainer v-if="facilities.length">
            <SectionHeading eyebrow="Sarana" title="Fasilitas Pendukung" />
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <FacilityCard
                    v-for="facility in facilities"
                    :key="facility.id"
                    :facility="facility"
                />
            </div>
        </SectionContainer>

        <SectionContainer v-if="related.length" tone="surface">
            <SectionHeading eyebrow="Lainnya" title="Program Terkait" />
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <ProgramCard
                    v-for="item in related"
                    :key="item.id"
                    :department="item"
                />
            </div>
        </SectionContainer>

        <SectionContainer tone="dark">
            <div
                class="flex flex-col items-center gap-6 text-center md:flex-row md:justify-between md:text-left"
            >
                <div>
                    <h2 class="text-2xl font-bold text-white">
                        Tertarik dengan {{ department.name }}?
                    </h2>
                    <p class="mt-2 text-white/75">
                        Dapatkan informasi pendaftaran dan program pendidikan
                        selengkapnya.
                    </p>
                </div>
                <BaseButton
                    :href="ctaUrl"
                    variant="white"
                    size="lg"
                    icon="arrow-right"
                >
                    Informasi Pendaftaran
                </BaseButton>
            </div>
        </SectionContainer>
    </DefaultLayout>
</template>
