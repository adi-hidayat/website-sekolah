<script setup lang="ts">
import { computed } from 'vue';

/**
 * Ikon minimalis (garis) dengan aksen hijau mengikuti konteks teks.
 * Semua ikon dekoratif; label aksesibilitas diberikan oleh elemen pemanggil
 * (PRD 12).
 */
const props = withDefaults(
    defineProps<{
        name: string;
        size?: number | string;
        strokeWidth?: number | string;
    }>(),
    {
        size: 20,
        strokeWidth: 1.75,
    },
);

const icons: Record<string, string[]> = {
    // Navigasi & UI
    menu: ['M4 7h16', 'M4 12h16', 'M4 17h16'],
    close: ['M6 6l12 12', 'M18 6L6 18'],
    'chevron-down': ['M6 9l6 6 6-6'],
    'chevron-right': ['M9 6l6 6-6 6'],
    'chevron-left': ['M15 6l-6 6 6 6'],
    'arrow-right': ['M5 12h14', 'M13 6l6 6-6 6'],
    'arrow-left': ['M19 12H5', 'M11 18l-6-6 6-6'],
    'external-link': [
        'M14 5h5v5',
        'M19 5l-8 8',
        'M18 14v4.5H5.5V6H10',
    ],
    search: ['M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14', 'M20 20l-4.2-4.2'],
    check: ['M5 12.5l4.5 4.5L19 7'],
    plus: ['M12 5v14', 'M5 12h14'],
    minus: ['M5 12h14'],
    link: [
        'M9.5 14.5l5-5',
        'M8 12l-2 2a3.5 3.5 0 0 0 5 5l2-2',
        'M16 12l2-2a3.5 3.5 0 0 0-5-5l-2 2',
    ],
    circle: ['M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z'],
    info: ['M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z', 'M12 11v6', 'M12 7.5h.01'],
    alert: ['M12 4l9 16H3z', 'M12 10v4', 'M12 17h.01'],
    'check-circle': [
        'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z',
        'M8.5 12.5l2.5 2.5 4.5-5',
    ],

    // Kontak
    phone: [
        'M6.5 3.5h3l1.5 4-2 1.5a12 12 0 0 0 6 6l1.5-2 4 1.5v3a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 4.5 5.7 2 2 0 0 1 6.5 3.5z',
    ],
    mail: ['M4 6h16v12H4z', 'M4 7l8 6 8-6'],
    'map-pin': [
        'M12 21s7-6.1 7-11a7 7 0 1 0-14 0c0 4.9 7 11 7 11z',
        'M12 13a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z',
    ],
    clock: ['M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z', 'M12 7.5V12l3 2'],
    calendar: ['M4 6h16v14H4z', 'M4 10h16', 'M8 3.5v4', 'M16 3.5v4'],
    globe: [
        'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z',
        'M3.5 12h17',
        'M12 3.5c2.4 2.4 3.5 5.4 3.5 8.5s-1.1 6.1-3.5 8.5c-2.4-2.4-3.5-5.4-3.5-8.5s1.1-6.1 3.5-8.5z',
    ],

    // Pendidikan & nilai
    'academic-cap': [
        'M2.5 9.5L12 5l9.5 4.5L12 14z',
        'M6 11.5V16c0 1.1 2.7 2.5 6 2.5s6-1.4 6-2.5v-4.5',
    ],
    book: [
        'M4 5.5A2 2 0 0 1 6 3.5h13v15H6a2 2 0 0 0-2 2z',
        'M19 18.5H6a2 2 0 0 0-2 2',
    ],
    beaker: [
        'M9 3.5h6',
        'M10 3.5v6l-4.5 7.5a2 2 0 0 0 1.7 3h9.6a2 2 0 0 0 1.7-3L14 9.5v-6',
        'M7.5 15h9',
    ],
    building: [
        'M4 21V6l7-3v18',
        'M11 21h9V10l-9-4',
        'M7 10h1',
        'M7 14h1',
        'M15 12h1',
        'M15 16h1',
    ],
    trophy: [
        'M8 4h8v4a4 4 0 0 1-8 0z',
        'M8 5H5v2a3 3 0 0 0 3 3',
        'M16 5h3v2a3 3 0 0 1-3 3',
        'M12 12v4',
        'M9 20h6',
        'M10 20v-2h4v2',
    ],
    star: [
        'M12 3.5l2.6 5.4 5.9.8-4.3 4.1 1 5.9L12 17l-5.2 2.7 1-5.9L3.5 9.7l5.9-.8z',
    ],
    sparkles: [
        'M11 3.5l1.5 4.2L16.7 9l-4.2 1.3L11 14.5 9.5 10.3 5.3 9l4.2-1.3z',
        'M18 14l.8 2.2 2.2.8-2.2.8-.8 2.2-.8-2.2-2.2-.8 2.2-.8z',
    ],
    heart: [
        'M12 20s-7-4.3-7-9.2A4 4 0 0 1 12 8a4 4 0 0 1 7 2.8C19 15.7 12 20 12 20z',
    ],
    'shield-check': [
        'M12 3l7 2.5v5.5c0 4.5-3 8-7 10-4-2-7-5.5-7-10V5.5z',
        'M9 12l2 2 4-4',
    ],
    users: [
        'M8 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z',
        'M2.5 20a5.5 5.5 0 0 1 11 0',
        'M16 4.5a3.5 3.5 0 0 1 0 7',
        'M17 20h4.5a5 5 0 0 0-3.5-4.8',
    ],
    user: ['M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8z', 'M4.5 20a7.5 7.5 0 0 1 15 0'],
    image: [
        'M4 5h16v14H4z',
        'M8.5 10.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z',
        'M4 16l4.5-4 4 3.5 3-2.5L20 16',
    ],
    quote: [
        'M7.5 6.5C5 7.5 4 9.5 4 12v5.5h5.5V12H7c0-1.6.6-3 2.5-3.6z',
        'M16.5 6.5C14 7.5 13 9.5 13 12v5.5h5.5V12H16c0-1.6.6-3 2.5-3.6z',
    ],
    play: ['M9 6.5l9 5.5-9 5.5z'],
    share: ['M12 3.5v11', 'M8 7l4-3.5L16 7', 'M5 14v5.5h14V14'],

    // Media sosial
    facebook: [
        'M13.5 21v-7h2.5l.5-3h-3V9.2c0-.9.3-1.4 1.5-1.4h1.7V5.1A19 19 0 0 0 14.8 5C12.6 5 11 6.3 11 8.9V11H8.5v3H11v7z',
    ],
    instagram: [
        'M7 3.5h10A3.5 3.5 0 0 1 20.5 7v10a3.5 3.5 0 0 1-3.5 3.5H7A3.5 3.5 0 0 1 3.5 17V7A3.5 3.5 0 0 1 7 3.5z',
        'M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z',
        'M16.8 7.2h.01',
    ],
    youtube: [
        'M2.5 8.5a4 4 0 0 1 4-4h11a4 4 0 0 1 4 4v7a4 4 0 0 1-4 4h-11a4 4 0 0 1-4-4z',
        'M10 9.2l5 2.8-5 2.8z',
    ],
    x: ['M4.5 4.5l15 15', 'M19.5 4.5l-15 15'],
    twitter: ['M4.5 4.5l15 15', 'M19.5 4.5l-15 15'],
    tiktok: ['M13.5 4v10.5a3.5 3.5 0 1 1-3.5-3.5', 'M13.5 4c.6 2.8 2.3 4.5 5 4.7'],
    whatsapp: [
        'M12 3.5a8.5 8.5 0 0 0-7.3 12.9L3.5 20.5l4.2-1.1A8.5 8.5 0 1 0 12 3.5z',
        'M8.6 8.6c.4-1 1.1-1 1.5-1 .3 0 .5.1.7.6l.6 1.5c.1.3 0 .5-.2.7l-.5.5c-.2.2-.2.4-.1.6.4.7 1 1.5 1.8 2 .6.4 1.1.5 1.4.5.2 0 .4-.1.5-.3l.5-.6c.2-.2.4-.3.6-.2l1.4.7c.4.2.5.4.4.8-.1.6-.7 1.3-1.4 1.4-.5.1-1.3.1-2.2-.3-1.3-.6-2.7-1.6-3.6-3-.6-.9-1-2-1-2.8 0-.7.2-1.3.5-1.7z',
    ],
    linkedin: [
        'M5 3.5h14A1.5 1.5 0 0 1 20.5 5v14a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 19V5A1.5 1.5 0 0 1 5 3.5z',
        'M8 10.5V17',
        'M8 7.6h.01',
        'M11.5 17v-3.6a2.4 2.4 0 0 1 4.8 0V17',
    ],
};

const paths = computed(() => icons[props.name] ?? icons.circle ?? []);
</script>

<template>
    <svg
        :width="size"
        :height="size"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        :stroke-width="strokeWidth"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
        focusable="false"
    >
        <path v-for="(path, index) in paths" :key="index" :d="path" />
    </svg>
</template>
