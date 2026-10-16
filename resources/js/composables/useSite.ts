import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type {
    Admissions,
    AnnouncementBar,
    NavPayload,
    PageCollections,
    SchoolSettings,
    SharedPageProps,
    SiteTheme,
} from '@/types';

const EMPTY_COLLECTIONS: PageCollections = {
    departments: [],
    news: [],
    announcements: [],
    achievements: [],
    facilities: [],
    galleries: [],
    teachers: [],
    statistics: [],
};

const EMPTY_NAVIGATION: NavPayload = { header: [], footer: [] };

const FALLBACK_ADMISSIONS: Admissions = {
    active: false,
    label: 'Informasi Pendaftaran',
    url: '/pengumuman',
    period_text: null,
};

const FALLBACK_THEME: SiteTheme = {
    primary_color: '#176b45',
    accent_color: '#b9d9a8',
    font_body: 'Inter',
    font_heading: 'Plus Jakarta Sans',
};

/**
 * Akses tunggal ke props Inertia yang dibagikan (menu, pengaturan, tema,
 * bar pengumuman, status SPMB, dan koleksi section). Komponen tidak boleh
 * mengambil data ini lewat request API berulang (PRD 11).
 */
export function useSite() {
    const page = usePage<SharedPageProps>();

    const siteSettings = computed<SchoolSettings>(
        () => (page.props.siteSettings ?? {}) as SchoolSettings,
    );

    const siteTheme = computed<SiteTheme>(
        () => (page.props.siteTheme ?? FALLBACK_THEME) as SiteTheme,
    );

    const navigation = computed<NavPayload>(
        () => (page.props.navigation ?? EMPTY_NAVIGATION) as NavPayload,
    );

    const announcementBar = computed<AnnouncementBar | null>(
        () => (page.props.announcementBar ?? null) as AnnouncementBar | null,
    );

    const admissions = computed<Admissions>(
        () => (page.props.admissions ?? FALLBACK_ADMISSIONS) as Admissions,
    );

    const collections = computed<PageCollections>(
        () =>
            (page.props.pageCollections ??
                EMPTY_COLLECTIONS) as PageCollections,
    );

    const currentUrl = computed(() => page.url ?? '/');

    return {
        page,
        siteSettings,
        siteTheme,
        navigation,
        announcementBar,
        admissions,
        collections,
        currentUrl,
    };
}
