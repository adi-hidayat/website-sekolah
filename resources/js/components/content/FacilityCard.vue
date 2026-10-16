<script setup lang="ts">
import AspectImage from '@/components/ui/AspectImage.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import type { Facility } from '@/types/cms';

/**
 * Kartu fasilitas (PRD 5.11). Fasilitas tanpa gambar tetap tampil rapi
 * dengan ikon, tidak menghasilkan ruang kosong.
 */
withDefaults(
    defineProps<{
        facility: Facility;
        large?: boolean;
    }>(),
    { large: false },
);
</script>

<template>
    <article
        class="group overflow-hidden rounded-card border border-border bg-background"
    >
        <AspectImage
            :src="facility.image"
            :alt="facility.name"
            :ratio="large ? 'video' : 'square'"
            :rounded="false"
            class="transition-transform duration-300 group-hover:scale-[1.02]"
        />
        <div class="p-5">
            <div class="flex items-center gap-2">
                <span
                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-light text-primary"
                >
                    <AppIcon :name="facility.icon || 'building'" :size="18" />
                </span>
                <h3
                    class="font-display text-base font-semibold text-foreground"
                >
                    {{ facility.name }}
                </h3>
            </div>
            <p
                v-if="facility.description"
                class="mt-2 text-sm leading-relaxed text-muted"
            >
                {{ facility.description }}
            </p>
        </div>
    </article>
</template>
