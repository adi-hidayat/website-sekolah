<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppIcon from '@/components/ui/AppIcon.vue';
import { useSite } from '@/composables/useSite';
import { normalizeSocialLinks } from '@/lib/data';
import { imageUrl, telUrl, whatsappUrl } from '@/lib/format';
import type { MenuNode } from '@/types/cms';

/**
 * Footer (PRD 5.14): identitas, kontak, menu lokasi terpisah dari header,
 * media sosial, dan copyright.
 */
const { navigation, siteSettings, admissions } = useSite();

const footerMenu = computed<MenuNode[]>(() => navigation.value.footer ?? []);
const logo = computed(() => imageUrl(siteSettings.value.logo));
const schoolName = computed(
    () => siteSettings.value.school_name ?? 'SMA Islam',
);

const socialIcons: Record<string, string> = {
    facebook: 'facebook',
    instagram: 'instagram',
    youtube: 'youtube',
    x: 'x',
    twitter: 'twitter',
    tiktok: 'tiktok',
    linkedin: 'linkedin',
    whatsapp: 'whatsapp',
};

const socials = computed(() =>
    normalizeSocialLinks(siteSettings.value.social_links).map((link) => ({
        ...link,
        icon: socialIcons[link.platform] ?? 'globe',
    })),
);

const year = new Date().getFullYear();
</script>

<template>
    <footer class="mt-auto bg-primary-dark text-white/75">
        <div class="content-container grid gap-10 py-14 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3">
                    <img
                        v-if="logo"
                        :src="logo"
                        :alt="schoolName"
                        class="h-10 w-auto"
                    />
                    <span
                        v-else
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-lg font-bold text-white"
                        aria-hidden="true"
                    >
                        {{ schoolName.charAt(0).toUpperCase() }}
                    </span>
                    <span class="font-display text-lg font-bold text-white">
                        {{ schoolName }}
                    </span>
                </div>

                <p v-if="siteSettings.address" class="mt-4 max-w-md text-sm">
                    {{ siteSettings.address }}
                </p>

                <ul class="mt-5 space-y-2 text-sm">
                    <li
                        v-if="siteSettings.phone"
                        class="flex items-center gap-2"
                    >
                        <AppIcon name="phone" :size="16" class="text-accent" />
                        <a
                            :href="telUrl(siteSettings.phone) ?? '#'"
                            class="hover:text-white"
                            >{{ siteSettings.phone }}</a
                        >
                        <a
                            v-if="whatsappUrl(siteSettings.phone)"
                            :href="whatsappUrl(siteSettings.phone) ?? '#'"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-accent hover:text-white"
                            >WhatsApp</a
                        >
                    </li>
                    <li
                        v-if="siteSettings.email"
                        class="flex items-center gap-2"
                    >
                        <AppIcon name="mail" :size="16" class="text-accent" />
                        <a
                            :href="`mailto:${siteSettings.email}`"
                            class="hover:text-white"
                            >{{ siteSettings.email }}</a
                        >
                    </li>
                    <li class="flex items-center gap-2">
                        <AppIcon name="clock" :size="16" class="text-accent" />
                        <span>Senin–Jumat, 07.00–15.00 WIB</span>
                    </li>
                </ul>

                <ul v-if="socials.length" class="mt-6 flex items-center gap-2">
                    <li v-for="social in socials" :key="social.platform">
                        <a
                            :href="social.url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20"
                            :aria-label="social.platform"
                        >
                            <AppIcon :name="social.icon" :size="18" />
                        </a>
                    </li>
                </ul>
            </div>

            <nav aria-label="Navigasi footer">
                <h2 class="font-display text-sm font-semibold text-white">
                    Tautan
                </h2>
                <ul class="mt-4 space-y-2 text-sm">
                    <li v-for="item in footerMenu" :key="item.id">
                        <Link
                            v-if="item.url"
                            :href="item.url"
                            :target="
                                item.target === '_blank' ? '_blank' : undefined
                            "
                            class="transition-colors hover:text-white"
                        >
                            {{ item.label }}
                        </Link>
                        <span v-else class="text-white/60">{{
                            item.label
                        }}</span>

                        <ul
                            v-if="item.children.length"
                            class="mt-2 space-y-1.5 pl-3"
                        >
                            <li v-for="child in item.children" :key="child.id">
                                <Link
                                    v-if="child.url"
                                    :href="child.url"
                                    class="text-white/70 transition-colors hover:text-white"
                                >
                                    {{ child.label }}
                                </Link>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <div>
                <h2 class="font-display text-sm font-semibold text-white">
                    Pendaftaran
                </h2>
                <p class="mt-4 text-sm">
                    {{
                        admissions.active
                            ? 'Pendaftaran peserta didik baru dibuka.'
                            : 'Informasi penerimaan peserta didik baru.'
                    }}
                </p>
                <Link
                    :href="admissions.url"
                    class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-accent hover:text-white"
                >
                    {{ admissions.label }}
                    <AppIcon name="arrow-right" :size="16" />
                </Link>

                <h2 class="mt-8 font-display text-sm font-semibold text-white">
                    Informasi
                </h2>
                <ul class="mt-4 space-y-2 text-sm">
                    <li>
                        <a href="/sitemap.xml" class="hover:text-white"
                            >Peta Situs</a
                        >
                    </li>
                    <li v-if="siteSettings.npsn">
                        <span>NPSN {{ siteSettings.npsn }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10">
            <div
                class="content-container flex flex-col items-center justify-between gap-2 py-5 text-xs sm:flex-row"
            >
                <p>
                    © {{ year }} {{ schoolName }}. Seluruh hak cipta dilindungi.
                </p>
                <p v-if="siteSettings.website">
                    {{ siteSettings.website }}
                </p>
            </div>
        </div>
    </footer>
</template>
