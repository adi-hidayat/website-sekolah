/**
 * Parsing data section CMS yang toleran (PRD 8.3): field tak dikenal tidak
 * boleh merusak halaman. Semua nilai dari Filament Builder bisa berupa array,
 * JSON string, atau teks baris-per-baris.
 */
import type { SocialLink, Statistic } from '@/types/cms';

export function toStringValue(value: unknown, fallback = ''): string {
    if (value === null || value === undefined) {
        return fallback;
    }

    if (typeof value === 'string') {
        return value.trim() === '' ? fallback : value;
    }

    if (typeof value === 'number' || typeof value === 'boolean') {
        return String(value);
    }

    return fallback;
}

export function toNumber(value: unknown, fallback: number): number {
    let parsed: number;

    if (typeof value === 'number') {
        parsed = value;
    } else if (typeof value === 'string') {
        parsed = Number.parseInt(value, 10);
    } else {
        return fallback;
    }

    return Number.isFinite(parsed) ? parsed : fallback;
}

export function toBoolean(value: unknown, fallback = false): boolean {
    if (typeof value === 'boolean') {
        return value;
    }

    if (typeof value === 'string') {
        return ['1', 'true', 'yes', 'on'].includes(value.toLowerCase());
    }

    return fallback;
}

function parseJson(value: string): unknown {
    try {
        return JSON.parse(value);
    } catch {
        return null;
    }
}

/** Daftar string: array, JSON, atau teks dengan pemisah baris/koma. */
export function toStringList(value: unknown): string[] {
    if (Array.isArray(value)) {
        return value
            .map((item) => toStringValue(item))
            .filter((item) => item !== '');
    }

    const raw = toStringValue(value);

    if (raw === '') {
        return [];
    }

    if (raw.startsWith('[') || raw.startsWith('{')) {
        const decoded = parseJson(raw);

        if (Array.isArray(decoded)) {
            return toStringList(decoded);
        }
    }

    return raw
        .split(/\r?\n|,(?![^[]*\])/)
        .map((item) => item.trim())
        .filter((item) => item !== '');
}

/** Daftar objek: array objek atau JSON string. */
export function toRecordList(value: unknown): Record<string, unknown>[] {
    const decoded = Array.isArray(value)
        ? value
        : (() => {
              const raw = toStringValue(value);

              if (raw === '') {
                  return [];
              }

              const parsed = parseJson(raw);

              return Array.isArray(parsed) ? parsed : [];
          })();

    return decoded.filter(
        (item): item is Record<string, unknown> =>
            typeof item === 'object' && item !== null && !Array.isArray(item),
    );
}

/**
 * Statistik sekolah. Menerima array {label, value}, JSON, atau baris
 * "Label: nilai" (PRD 5.4).
 */
export function toStatistics(value: unknown): Statistic[] {
    const records = toRecordList(value);

    if (records.length > 0) {
        return records
            .map((record) => ({
                label: toStringValue(
                    record.label ?? record.title ?? record.name,
                ),
                value: toStringValue(
                    record.value ?? record.total ?? record.count,
                ),
            }))
            .filter((item) => item.label !== '' && item.value !== '');
    }

    return toStringList(value)
        .map((line) => {
            const separator = line.includes(':') ? ':' : '|';
            const [label, ...rest] = line.split(separator);

            return {
                label: (label ?? '').trim(),
                value: rest.join(separator).trim(),
            };
        })
        .filter((item) => item.label !== '' && item.value !== '');
}

export interface FaqItem {
    question: string;
    answer: string;
}

/** FAQ dari array {q,a}/{question,answer}, JSON, atau blok tanya/jawab. */
export function toFaqItems(value: unknown): FaqItem[] {
    const records = toRecordList(value);

    if (records.length > 0) {
        return records
            .map((record) => ({
                question: toStringValue(record.q ?? record.question),
                answer: toStringValue(record.a ?? record.answer),
            }))
            .filter((item) => item.question !== '');
    }

    const lines = toStringList(value);
    const items: FaqItem[] = [];

    for (let index = 0; index < lines.length; index += 2) {
        const question = lines[index];

        if (question === undefined) {
            break;
        }

        items.push({ question, answer: lines[index + 1] ?? '' });
    }

    return items;
}

const SOCIAL_PLATFORMS = [
    'facebook',
    'instagram',
    'youtube',
    'twitter',
    'x',
    'tiktok',
    'linkedin',
    'whatsapp',
] as const;

/** Normalisasi social_links dari objek map maupun array (PRD 5.14). */
export function normalizeSocialLinks(value: unknown): SocialLink[] {
    if (Array.isArray(value)) {
        return toRecordList(value)
            .map((record) => ({
                platform: toStringValue(
                    record.platform ?? record.name ?? record.type,
                ).toLowerCase(),
                url: toStringValue(record.url ?? record.link),
            }))
            .filter((link) => link.url !== '');
    }

    if (value && typeof value === 'object') {
        return Object.entries(value as Record<string, unknown>)
            .map(([platform, url]) => ({
                platform: platform.toLowerCase(),
                url: toStringValue(url),
            }))
            .filter((link) => link.url !== '');
    }

    return [];
}

export function isSocialPlatform(platform: string): boolean {
    return (SOCIAL_PLATFORMS as readonly string[]).includes(platform);
}
