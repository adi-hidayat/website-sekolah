<script setup lang="ts">
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';
import AspectImage from '@/components/ui/AspectImage.vue';
import AppIcon from '@/components/ui/AppIcon.vue';
import { imageUrl } from '@/lib/format';

interface GalleryImage {
    src: string | null;
    alt: string;
    caption?: string | null;
}

/**
 * Galeri foto dengan lightbox (PRD 5.12): lazy loading, alt text,
 * navigasi keyboard, dan penutupan dengan Escape.
 */
const props = withDefaults(
    defineProps<{
        images?: GalleryImage[];
        columns?: 2 | 3 | 4;
        featured?: boolean;
    }>(),
    {
        images: () => [],
        columns: 3,
        featured: true,
    },
);

const validImages = computed(() =>
    props.images.filter((image) => imageUrl(image.src)),
);

const columnClasses: Record<number, string> = {
    2: 'grid-cols-1 sm:grid-cols-2',
    3: 'grid-cols-2 sm:grid-cols-3',
    4: 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-4',
};

const openIndex = ref<number | null>(null);
const dialog = ref<HTMLElement | null>(null);
let lastFocused: HTMLElement | null = null;

const active = computed(() =>
    openIndex.value === null
        ? null
        : (validImages.value[openIndex.value] ?? null),
);

function open(index: number): void {
    lastFocused = document.activeElement as HTMLElement | null;
    openIndex.value = index;
}

function close(): void {
    openIndex.value = null;
}

function next(): void {
    if (openIndex.value === null || validImages.value.length === 0) {
        return;
    }

    openIndex.value = (openIndex.value + 1) % validImages.value.length;
}

function previous(): void {
    if (openIndex.value === null || validImages.value.length === 0) {
        return;
    }

    openIndex.value =
        (openIndex.value - 1 + validImages.value.length) %
        validImages.value.length;
}

function onKeydown(event: KeyboardEvent): void {
    if (event.key === 'Escape') {
        close();
    } else if (event.key === 'ArrowRight') {
        next();
    } else if (event.key === 'ArrowLeft') {
        previous();
    }
}

watch(openIndex, (value) => {
    document.body.style.overflow = value === null ? '' : 'hidden';

    if (value === null) {
        lastFocused?.focus();
    } else {
        void nextTick(() => dialog.value?.focus());
    }
});

onBeforeUnmount(() => {
    document.body.style.overflow = '';
});
</script>

<template>
    <div>
        <div
            class="grid gap-3 sm:gap-4"
            :class="columnClasses[columns] ?? columnClasses[3]"
        >
            <button
                v-for="(image, index) in validImages"
                :key="index"
                type="button"
                class="group relative block overflow-hidden rounded-card focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                :class="
                    featured && index === 0 && validImages.length > 2
                        ? 'sm:col-span-2'
                        : ''
                "
                @click="open(index)"
            >
                <AspectImage
                    :src="image.src"
                    :alt="image.alt"
                    :ratio="
                        featured && index === 0 && validImages.length > 2
                            ? 'video'
                            : 'square'
                    "
                    class="transition-transform duration-300 group-hover:scale-[1.03]"
                />
                <span
                    class="absolute inset-0 flex items-end bg-gradient-to-t from-foreground/60 to-transparent p-3 opacity-0 transition-opacity group-hover:opacity-100 group-focus-visible:opacity-100"
                >
                    <span
                        class="line-clamp-2 text-left text-xs font-medium text-white"
                    >
                        {{ image.caption ?? image.alt }}
                    </span>
                </span>
            </button>
        </div>

        <Teleport to="body">
            <div
                v-if="active"
                class="fixed inset-0 z-50 flex items-center justify-center bg-foreground/90 p-4"
                role="dialog"
                aria-modal="true"
                aria-label="Pratinjau galeri"
                @click.self="close"
            >
                <div
                    ref="dialog"
                    tabindex="-1"
                    class="relative w-full max-w-5xl outline-none"
                    @keydown="onKeydown"
                >
                    <img
                        :src="imageUrl(active.src) ?? undefined"
                        :alt="active.alt"
                        class="mx-auto max-h-[80vh] w-auto rounded-card object-contain"
                    />
                    <p
                        v-if="active.caption"
                        class="mt-3 text-center text-sm text-white/80"
                    >
                        {{ active.caption }}
                    </p>

                    <button
                        type="button"
                        class="absolute top-0 right-0 -mt-2 -mr-1 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20"
                        aria-label="Tutup galeri"
                        @click="close"
                    >
                        <AppIcon name="close" :size="20" />
                    </button>

                    <button
                        v-if="validImages.length > 1"
                        type="button"
                        class="absolute top-1/2 left-0 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20"
                        aria-label="Gambar sebelumnya"
                        @click="previous"
                    >
                        <AppIcon name="chevron-left" :size="22" />
                    </button>
                    <button
                        v-if="validImages.length > 1"
                        type="button"
                        class="absolute top-1/2 right-0 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20"
                        aria-label="Gambar berikutnya"
                        @click="next"
                    >
                        <AppIcon name="chevron-right" :size="22" />
                    </button>
                </div>
            </div>
        </Teleport>
    </div>
</template>
