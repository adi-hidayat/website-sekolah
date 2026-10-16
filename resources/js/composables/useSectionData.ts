import { computed } from 'vue';
import {
    toBoolean,
    toFaqItems,
    toNumber,
    toRecordList,
    toStatistics,
    toStringList,
    toStringValue,
} from '@/lib/data';
import type { FaqItem } from '@/lib/data';
import type { PageSection, Statistic } from '@/types/cms';

export interface CtaData {
    label: string;
    url: string;
    target: '_self' | '_blank';
}

/**
 * Akses data section CMS dengan default yang aman (PRD 8.3). Field yang
 * tidak dikenal tidak boleh melempar error.
 */
export function useSectionData(section: PageSection) {
    const data = computed<Record<string, unknown>>(
        () => (section?.data ?? {}) as Record<string, unknown>,
    );

    const text = (key: string, fallback = ''): string =>
        toStringValue(data.value[key], fallback);

    const number = (key: string, fallback: number): number =>
        toNumber(data.value[key], fallback);

    const flag = (key: string, fallback = false): boolean =>
        toBoolean(data.value[key], fallback);

    const list = (key: string): string[] => toStringList(data.value[key]);

    const records = (key: string): Record<string, unknown>[] =>
        toRecordList(data.value[key]);

    const stats = (key: string): Statistic[] => toStatistics(data.value[key]);

    const faq = (key: string): FaqItem[] => toFaqItems(data.value[key]);

    /**
     * CTA dari dua bentuk: `button_label`/`button_url` (section CTA) atau
     * objek `cta: { label, url }` (section hero).
     */
    const cta = (prefix = 'cta', label = 'label', url = 'url'): CtaData => {
        const nested = data.value[prefix];
        const nestedObject: Record<string, unknown> =
            typeof nested === 'object' && nested !== null
                ? (nested as Record<string, unknown>)
                : {};

        return {
            label: toStringValue(
                data.value[`${prefix}_${label}`] ?? nestedObject[label],
            ),
            url: toStringValue(
                data.value[`${prefix}_${url}`] ?? nestedObject[url],
            ),
            target:
                toStringValue(data.value[`${prefix}_target`]) === '_blank'
                    ? '_blank'
                    : '_self',
        };
    };

    return { data, text, number, flag, list, records, stats, faq, cta };
}
