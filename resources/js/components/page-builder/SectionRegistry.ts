import type { Component } from 'vue';
import HeroSection from '@/components/sections/HeroSection.vue';
import StatisticsSection from '@/components/sections/StatisticsSection.vue';
import PrincipalMessage from '@/components/sections/PrincipalMessage.vue';
import ProgramsSection from '@/components/sections/ProgramsSection.vue';
import ValuesSection from '@/components/sections/ValuesSection.vue';
import NewsSection from '@/components/sections/NewsSection.vue';
import AchievementsSection from '@/components/sections/AchievementsSection.vue';
import FacilitiesSection from '@/components/sections/FacilitiesSection.vue';
import GallerySection from '@/components/sections/GallerySection.vue';
import CTASection from '@/components/sections/CTASection.vue';
import RichTextSection from '@/components/sections/RichTextSection.vue';
import ImageSection from '@/components/sections/ImageSection.vue';
import CardGridSection from '@/components/sections/CardGridSection.vue';
import TeachersSection from '@/components/sections/TeachersSection.vue';
import AnnouncementsSection from '@/components/sections/AnnouncementsSection.vue';
import ContactSection from '@/components/sections/ContactSection.vue';
import FaqSection from '@/components/sections/FaqSection.vue';
import CustomHtmlSection from '@/components/sections/CustomHtmlSection.vue';
import SectionFallback from '@/components/page-builder/SectionFallback.vue';

/**
 * Registry section page builder (PRD 8.2). Tipe section dari backend
 * dipetakan ke komponen Vue di sini; menambah section baru cukup
 * menambahkan entri tanpa mengubah layout global.
 */
export const sectionRegistry: Record<string, Component> = {
    hero: HeroSection,
    statistics: StatisticsSection,
    principal: PrincipalMessage,
    programs: ProgramsSection,
    values: ValuesSection,
    latest_news: NewsSection,
    news: NewsSection,
    achievements: AchievementsSection,
    facilities: FacilitiesSection,
    gallery: GallerySection,
    image_gallery: GallerySection,
    cta: CTASection,
    rich_text: RichTextSection,
    image: ImageSection,
    card_grid: CardGridSection,
    teachers: TeachersSection,
    announcements: AnnouncementsSection,
    contact: ContactSection,
    faq: FaqSection,
    custom_html: CustomHtmlSection,
};

export const SECTION_TYPES: string[] = Object.keys(sectionRegistry);

export function resolveSection(type: string): Component {
    return sectionRegistry[type] ?? SectionFallback;
}
