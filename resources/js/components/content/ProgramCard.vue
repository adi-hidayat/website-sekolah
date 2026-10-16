<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AspectImage from '@/components/ui/AspectImage.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { show as departmentShow } from '@/routes/departments';
import type { Department } from '@/types/cms';

/**
 * Kartu program/jurusan (PRD 5.6). Data berasal dari tabel jurusan,
 * sehingga admin bisa menambah jurusan tanpa mengubah komponen.
 */
const props = defineProps<{ department: Department }>();

const href = computed(() => departmentShow(props.department.slug).url);
</script>

<template>
    <Link
        :href="href"
        class="group flex flex-col overflow-hidden rounded-card border border-border bg-background transition-colors hover:border-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
    >
        <AspectImage
            :src="department.thumbnail ?? department.banner"
            :alt="department.name"
            ratio="video"
            :rounded="false"
            class="transition-transform duration-300 group-hover:scale-[1.02]"
        />
        <div class="flex flex-1 flex-col p-5 sm:p-6">
            <span
                v-if="department.code"
                class="text-xs font-semibold tracking-[0.18em] text-primary uppercase"
            >
                {{ department.code }}
            </span>
            <h3
                class="mt-1.5 font-display text-lg font-semibold text-foreground group-hover:text-primary"
            >
                {{ department.name }}
            </h3>
            <p
                v-if="department.short_description"
                class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-muted"
            >
                {{ department.short_description }}
            </p>
            <span
                class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-primary"
            >
                Lihat detail
                <AppIcon
                    name="arrow-right"
                    :size="16"
                    class="transition-transform group-hover:translate-x-0.5"
                />
            </span>
        </div>
    </Link>
</template>
