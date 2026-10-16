/**
 * Format tanggal & teks Bahasa Indonesia. Dipakai semua komponen agar
 * tampilan tanggal konsisten (PRD 4.2 & 11).
 */

const longDate = new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
});

const longDateWithTime = new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
});

function parse(value: string | number | null | undefined): Date | null {
    if (value === null || value === undefined || value === '') {
        return null;
    }

    const date = new Date(value);

    return Number.isNaN(date.getTime()) ? null : date;
}

export function formatDate(value: string | number | null | undefined): string {
    const date = parse(value);

    return date ? longDate.format(date) : '';
}

export function formatDateTime(
    value: string | number | null | undefined,
): string {
    const date = parse(value);

    return date ? longDateWithTime.format(date) : '';
}

export function formatYear(value: string | number | null | undefined): string {
    const date = parse(value);

    return date ? String(date.getFullYear()) : '';
}

/** Ringkas teks panjang untuk kartu tanpa memotong di tengah kata. */
export function excerpt(
    value: string | null | undefined,
    length = 140,
): string {
    const text = (value ?? '')
        .replace(/<[^>]*>/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();

    if (text.length <= length) {
        return text;
    }

    return `${text.slice(0, length).trimEnd()}…`;
}

/** Nomor telepon/WhatsApp → tautan wa.me bila memungkinkan. */
export function whatsappUrl(phone: string | null | undefined): string | null {
    const digits = (phone ?? '').replace(/[^\d]/g, '');

    if (digits.length < 8) {
        return null;
    }

    const normalized = digits.startsWith('0') ? `62${digits.slice(1)}` : digits;

    return `https://wa.me/${normalized}`;
}

export function telUrl(phone: string | null | undefined): string | null {
    const value = (phone ?? '').trim();

    return value === '' ? null : `tel:${value.replace(/[^\d+]/g, '')}`;
}

/**
 * URL gambar dari CMS bisa berupa URL absolut, path publik, atau path
 * media library. Normalisasi tanpa merusak nilai yang sudah valid.
 */
export function imageUrl(
    value: string | null | undefined,
    fallback: string | null = null,
): string | null {
    const path = (value ?? '').trim();

    if (path === '') {
        return fallback;
    }

    if (/^(https?:)?\/\//i.test(path) || path.startsWith('data:')) {
        return path;
    }

    if (path.startsWith('/')) {
        return path;
    }

    return `/storage/${path.replace(/^storage\//, '')}`;
}
