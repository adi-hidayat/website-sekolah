<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppIcon from '@/components/ui/AppIcon.vue';
import BaseButton from '@/components/ui/BaseButton.vue';
import MobileNavigation from '@/components/layout/MobileNavigation.vue';
import { useSite } from '@/composables/useSite';
import { imageUrl } from '@/lib/format';
import type { MenuNode } from '@/types/cms';

/**
 * Header & navigasi utama (PRD 5.2). Menu dari CMS, dropdown dapat
 * diakses keyboard, header sticky, aktif state hijau, CTA SPMB dari
 * konfigurasi.
 */
const { navigation, siteSettings, whatsappCta, currentUrl } = useSite();

const headerMenu = computed<MenuNode[]>(() => navigation.value.header ?? []);
const logo = computed(() => imageUrl(siteSettings.value.logo));
const schoolName = computed(
    () => siteSettings.value.school_name ?? 'SMA Islam',
);
const shortName = computed(
    () => siteSettings.value.short_name || schoolName.value,
);

const openDropdown = ref<number | null>(null);
const mobileOpen = ref(false);

function pathOf(url: string): string {
    const path = url.split('?')[0] ?? '/';

    return path.replace(/\/+$/, '') || '/';
}

const currentPath = computed(() => pathOf(currentUrl.value));

function isActive(node: MenuNode): boolean {
    if (!node.url) {
        return node.children.some((child) => isActive(child));
    }

    const path = pathOf(node.url);

    return (
        currentPath.value === path || currentPath.value.startsWith(`${path}/`)
    );
}

/**
 * Panjang path tercocok terpanjang di subtree node (0 bila tidak cocok).
 * Dipakai agar hanya SATU menu utama yang aktif: kecocokan paling
 * spesifik menang (mis. /jurusan/mipa mengalahkan /jurusan).
 */
function matchLength(node: MenuNode): number {
    let best = 0;

    if (node.url) {
        const path = pathOf(node.url);

        if (
            currentPath.value === path ||
            currentPath.value.startsWith(`${path}/`)
        ) {
            best = path.length;
        }
    }

    for (const child of node.children) {
        best = Math.max(best, matchLength(child));
    }

    return best;
}

const activeTopId = computed<number | null>(() => {
    let bestId: number | null = null;
    let bestLength = 0;

    for (const item of headerMenu.value) {
        const length = matchLength(item);

        if (length > bestLength) {
            bestLength = length;
            bestId = item.id;
        }
    }

    return bestId;
});

function isTopActive(node: MenuNode): boolean {
    return activeTopId.value !== null && node.id === activeTopId.value;
}

function linkClass(node: MenuNode): string {
    return isTopActive(node)
        ? 'bg-primary-light text-primary-dark'
        : 'text-foreground/80 hover:bg-surface hover:text-primary';
}

function toggleDropdown(id: number): void {
    openDropdown.value = openDropdown.value === id ? null : id;
}

function closeDropdown(): void {
    openDropdown.value = null;
}

function onDocumentClick(event: MouseEvent): void {
    const target = event.target as HTMLElement | null;

    if (!target?.closest('[data-nav-dropdown]')) {
        closeDropdown();
    }
}

function onKeydown(event: KeyboardEvent): void {
    if (event.key === 'Escape') {
        closeDropdown();
    }
}

onMounted(() => {
    document.addEventListener('click', onDocumentClick);
    document.addEventListener('keydown', onKeydown);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', onDocumentClick);
    document.removeEventListener('keydown', onKeydown);
});
</script>

<template>
    <header
        class="sticky top-0 z-40 border-b border-border bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/85"
    >
        <div
            class="content-container flex h-20 items-center justify-between gap-4 lg:h-24"
        >
            <Link
                href="/"
                class="flex min-w-0 items-center gap-3 rounded-md focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
            >
                <img
                    v-if="logo"
                    :src="logo"
                    :alt="schoolName"
                    class="h-16 w-auto max-w-[220px] shrink-0 object-contain sm:max-w-[300px] lg:h-20 lg:max-w-[360px]"
                />
                <span
                    v-else
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-primary text-xl font-bold text-white lg:h-16 lg:w-16"
                    aria-hidden="true"
                >
                    {{ shortName.charAt(0).toUpperCase() }}
                </span>
                <span class="hidden flex-col leading-tight sm:flex">
                    <span
                        class="font-display text-sm font-bold text-foreground sm:text-base"
                    >
                        {{ shortName }}
                    </span>
                    <span
                        v-if="siteSettings.accreditation"
                        class="text-[11px] text-muted"
                    >
                        Terakreditasi {{ siteSettings.accreditation }}
                    </span>
                </span>
            </Link>

            <nav
                class="hidden items-center lg:flex"
                aria-label="Navigasi utama"
            >
                <ul class="flex items-center gap-1">
                    <li
                        v-for="item in headerMenu"
                        :key="item.id"
                        class="relative"
                        data-nav-dropdown
                        @mouseenter="openDropdown = item.id"
                        @mouseleave="closeDropdown"
                    >
                        <div
                            v-if="item.children.length"
                            class="flex items-center rounded-button transition-colors"
                            :class="linkClass(item)"
                        >
                            <Link
                                v-if="item.url"
                                :href="item.url"
                                :target="
                                    item.target === '_blank'
                                        ? '_blank'
                                        : undefined
                                "
                                class="py-2 pr-1 pl-3 text-sm font-medium"
                            >
                                {{ item.label }}
                            </Link>
                            <span
                                v-else
                                class="py-2 pr-1 pl-3 text-sm font-medium"
                            >
                                {{ item.label }}
                            </span>
                            <button
                                type="button"
                                class="flex items-center justify-center py-2 pr-2.5 pl-1 opacity-70 transition hover:opacity-100"
                                :aria-expanded="openDropdown === item.id"
                                aria-haspopup="true"
                                :aria-label="`Buka submenu ${item.label}`"
                                @click="toggleDropdown(item.id)"
                            >
                                <AppIcon
                                    name="chevron-down"
                                    :size="16"
                                    :class="
                                        openDropdown === item.id
                                            ? 'rotate-180 transition-transform'
                                            : 'transition-transform'
                                    "
                                />
                            </button>
                        </div>

                        <Link
                            v-else
                            :href="item.url ?? '#'"
                            :target="
                                item.target === '_blank' ? '_blank' : undefined
                            "
                            class="rounded-button px-3 py-2 text-sm font-medium transition-colors"
                            :class="linkClass(item)"
                        >
                            {{ item.label }}
                        </Link>

                        <div
                            v-show="
                                item.children.length && openDropdown === item.id
                            "
                            class="absolute top-full left-0 z-50 pt-2"
                        >
                            <ul
                                class="min-w-56 rounded-card border border-border bg-background p-2 shadow-soft"
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
                                        class="block rounded-button px-3 py-2 text-sm transition-colors"
                                        :class="
                                            isActive(child)
                                                ? 'bg-primary-light font-medium text-primary-dark'
                                                : 'text-foreground/80 hover:bg-surface hover:text-primary'
                                        "
                                    >
                                        {{ child.label }}
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="flex items-center gap-2">
                <div v-if="whatsappCta" class="hidden lg:block">
                    <BaseButton
                        :href="whatsappCta.url"
                        :icon="whatsappCta.icon"
                    >
                        {{ whatsappCta.label }}
                    </BaseButton>
                </div>

                <button
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-button border border-border text-foreground hover:border-primary hover:text-primary lg:hidden"
                    :aria-expanded="mobileOpen"
                    aria-label="Buka menu navigasi"
                    @click="mobileOpen = true"
                >
                    <AppIcon name="menu" :size="22" />
                </button>
            </div>
        </div>

        <MobileNavigation
            :open="mobileOpen"
            :items="headerMenu"
            @close="mobileOpen = false"
        />
    </header>
</template>
