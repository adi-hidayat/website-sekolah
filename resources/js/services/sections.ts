import type { PageSection } from '@/types/cms';
import { SECTION_TYPES } from '@/components/page-builder/SectionRegistry';

/** Daftar type section yang didukung page builder (PRD 8.2). */
export { SECTION_TYPES };

export function isKnownSection(section: PageSection): boolean {
    return SECTION_TYPES.includes(section.type);
}
