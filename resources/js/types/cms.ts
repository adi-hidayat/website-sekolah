/**
 * Kontrak data bersama antara Laravel/Filament dan komponen Vue.
 * Semua komponen section menerima data melalui props/inject ini dan tidak
 * boleh menyimpan konten sekolah yang hardcoded (PRD 7 & 8).
 */

export interface PageSection {
    id: string;
    type: string;
    data: Record<string, unknown>;
}

export interface MenuNode {
    id: number;
    label: string;
    type: string;
    url: string | null;
    target: string;
    children: MenuNode[];
}

export interface SeoMeta {
    title?: string | null;
    description?: string | null;
    image?: string | null;
    canonical?: string | null;
}

export interface NewsCategory {
    id: number;
    name: string;
    slug: string;
}

export interface NewsItem {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    content?: string | null;
    featured_image: string | null;
    published_at: string | null;
    views_count?: number;
    categories?: NewsCategory[];
    author?: { name: string } | null;
}

export interface Teacher {
    id: number;
    name: string;
    position: string | null;
    subject: string | null;
    photo: string | null;
    bio: string | null;
}

export interface Achievement {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    category: string | null;
    level: string | null;
    rank: string | null;
    achieved_at: string | null;
    image: string | null;
}

export interface Department {
    id: number;
    name: string;
    code: string | null;
    slug: string;
    short_description: string | null;
    description: string | null;
    thumbnail: string | null;
    banner: string | null;
    competencies: string[] | null;
    teachers?: Teacher[];
    achievements?: Achievement[];
}

export interface Facility {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    image: string | null;
    icon: string | null;
}

export interface GalleryItem {
    id: number;
    type: string;
    file_path: string;
    caption: string | null;
}

export interface Gallery {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    cover_image: string | null;
    items?: GalleryItem[];
    items_count?: number;
}

export interface Announcement {
    id: number;
    title: string;
    slug: string;
    content?: string | null;
    category: string | null;
    starts_at: string | null;
    ends_at: string | null;
    attachment: string | null;
    is_pinned: boolean;
}

export interface Statistic {
    label: string;
    value: string | number;
}

export interface SocialLink {
    platform: string;
    url: string;
}

export interface SchoolSettings {
    site_name?: string | null;
    site_tagline?: string | null;
    school_name?: string;
    short_name?: string | null;
    npsn?: string | null;
    accreditation?: string | null;
    address?: string | null;
    phone?: string | null;
    whatsapp?: string | null;
    email?: string | null;
    website?: string | null;
    logo?: string | null;
    favicon?: string | null;
    seo_title?: string | null;
    seo_description?: string | null;
    seo_keywords?: string | null;
    og_image?: string | null;
    principal_name?: string | null;
    principal_welcome?: string | null;
    principal_photo?: string | null;
    vision?: string | null;
    mission?: string | null;
    history?: string | null;
    maps_embed_url?: string | null;
    social_links?: SocialLink[] | Record<string, string> | null;
    statistics?: Statistic[] | null;
}

export interface SiteTheme {
    primary_color: string;
    accent_color: string;
    font_body: string;
    font_heading: string;
}

/** Bar pengumuman tipis di atas navbar (PRD 5.1), diturunkan dari CMS. */
export interface AnnouncementBar {
    id: number;
    title: string;
    url: string;
    is_pinned: boolean;
}

/** Status SPMB (PRD 5.2 & 5.13), diturunkan dari data CMS yang ada. */
export interface Admissions {
    active: boolean;
    label: string;
    url: string;
    period_text: string | null;
}

/** Koleksi data publik yang tersedia untuk semua section page builder. */
export interface PageCollections {
    departments: Department[];
    news: NewsItem[];
    announcements: Announcement[];
    achievements: Achievement[];
    facilities: Facility[];
    galleries: Gallery[];
    teachers: Teacher[];
    statistics: Statistic[];
}

export interface NavPayload {
    header: MenuNode[];
    footer: MenuNode[];
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface Paginated<T> {
    data: T[];
    links: PaginationLink[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
}
