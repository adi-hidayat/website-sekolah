<script setup lang="ts">
import { computed, nextTick, onBeforeUnmount, ref, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppIcon from '@/components/ui/AppIcon.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
import type { Admissions, MenuNode } from '@/types/cms';

/**
 * Drawer navigasi mobile (PRD 5.2, 9): dapat ditutup dengan Escape,
 * mengunci scroll, dan mendukung dropdown bertingkat.
 */
const props = defineProps<{
    open: boolean;
    items: MenuNode[];
    admissions: Admissions;
}>();

const emit = defineEmits<{ close: [] }>();

const panel = ref<HTMLElement | null>(null);
const expanded = ref<number[]>([]);
let lastFocused: HTMLElement | null = null;

function toggle(id: number): void {
    expanded.value = expanded.value.includes(id)
        ? expanded.value.filter((item) => item !== id)
        : [...expanded.value, id];
}

function isExpanded(id: number): boolean {
    return expanded.value.includes(id);
}

function onKeydown(event: KeyboardEvent): void {
    if (event.key === 'Escape') {
        emit('close');
    }
}

watch(
    () => props.open,
    (open) => {
        document.body.style.overflow = open ? 'hidden' : '';

        if (open) {
            if (typeof document !== 'undefined') {
                lastFocused = document.activeElement as HTMLElement | null;
            }
            void nextTick(() => panel.value?.focus());
        } else {
            expanded.value = [];
            lastFocused?.focus();
        }
    },
);

onBeforeUnmount(() => {
    document.body.style.overflow = '';
});

const hasItems = computed(() => props.items.length > 0);
</script>

<template>
    <Teleport to="body">
        <div v-if="open" class="fixed inset-0 z-50 lg:hidden">
            <div
                class="absolute inset-0 bg-foreground/50"
                aria-hidden="true"
                @click="emit('close')"
            ></div>

            <div
                ref="panel"
                tabindex="-1"
                role="dialog"
                aria-modal="true"
                aria-label="Menu navigasi"
                class="absolute top-0 right-0 flex h-full w-[85%] max-w-sm flex-col bg-background shadow-soft outline-none"
                @keydown="onKeydown"
            >
                <div
                    class="flex items-center justify-between border-b border-border px-5 py-4"
                >
                    <span
                        class="font-display text-base font-bold text-foreground"
                    >
                        Menu
                    </span>
                    <button
                        type="button"
                        class="flex h-9 w-9 items-center justify-center rounded-full text-foreground hover:bg-surface"
                        aria-label="Tutup menu"
                        @click="emit('close')"
                    >
                        <AppIcon name="close" :size="20" />
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto px-3 py-4">
                    <ul class="space-y-1">
                        <li v-for="item in items" :key="item.id">
                            <div class="flex items-center">
                                <Link
                                    v-if="item.url"
                                    :href="item.url"
                                    :target="
                                        item.target === '_blank'
                                            ? '_blank'
                                            : undefined
                                    "
                                    class="flex-1 rounded-button px-3 py-2.5 text-base font-medium text-foreground hover:bg-surface"
                                    @click="emit('close')"
                                >
                                    {{ item.label }}
                                </Link>
                                <span
                                    v-else
                                    class="flex-1 px-3 py-2.5 text-base font-medium text-foreground"
                                >
                                    {{ item.label }}
                                </span>

                                <button
                                    v-if="item.children.length"
                                    type="button"
                                    class="flex h-9 w-9 items-center justify-center rounded-button text-foreground hover:bg-surface"
                                    :aria-expanded="isExpanded(item.id)"
                                    :aria-label="`Buka submenu ${item.label}`"
                                    @click="toggle(item.id)"
                                >
                                    <AppIcon
                                        name="chevron-down"
                                        :size="18"
                                        :class="
                                            isExpanded(item.id)
                                                ? 'rotate-180 transition-transform'
                                                : 'transition-transform'
                                        "
                                    />
                                </button>
                            </div>

                            <ul
                                v-if="
                                    item.children.length && isExpanded(item.id)
                                "
                                class="mt-1 ml-3 space-y-1 border-l border-border pl-3"
                            >
                                <li
                                    v-for="child in item.children"
                                    :key="child.id"
                                >
                                    <Link
                                        v-if="child.url"
                                        :href="child.url"
                                        :target="
                                            child.target === '_blank'
                                                ? '_blank'
                                                : undefined
                                        "
                                        class="block rounded-button px-3 py-2 text-sm text-muted hover:bg-surface hover:text-primary"
                                        @click="emit('close')"
                                    >
                                        {{ child.label }}
                                    </Link>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <p
                        v-if="!hasItems"
                        class="px-3 py-6 text-center text-sm text-muted"
                    >
                        Menu belum tersedia.
                    </p>
                </nav>

                <div
                    v-if="admissions.active"
                    class="border-t border-border p-4"
                >
                    <BaseButton
                        :href="admissions.url"
                        block
                        size="lg"
                        icon="arrow-right"
                        @click="emit('close')"
                    >
                        {{ admissions.label }}
                    </BaseButton>
                </div>
            </div>
        </div>
    </Teleport>
</template>
