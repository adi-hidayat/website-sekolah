import { onMounted, watch } from 'vue';
import { useSite } from '@/composables/useSite';

/** Campur dua warna hex, bobot untuk warna kedua (0–1). */
function mix(hex1: string, hex2: string, weight: number): string {
    const toRgb = (hex: string): [number, number, number] => {
        let h = hex.replace('#', '');

        if (h.length === 3) {
            h = h
                .split('')
                .map((c) => c + c)
                .join('');
        }

        return [
            parseInt(h.slice(0, 2), 16),
            parseInt(h.slice(2, 4), 16),
            parseInt(h.slice(4, 6), 16),
        ];
    };

    const [r1, g1, b1] = toRgb(hex1);
    const [r2, g2, b2] = toRgb(hex2);
    const w = Math.min(1, Math.max(0, weight));

    const toHex = (v: number): string =>
        Math.round(v).toString(16).padStart(2, '0');

    return `#${toHex(r1 * (1 - w) + r2 * w)}${toHex(g1 * (1 - w) + g2 * w)}${toHex(b1 * (1 - w) + b2 * w)}`;
}

const LOADED_FONTS = new Set<string>();

function ensureFontLoaded(family: string): void {
    if (typeof document === 'undefined' || LOADED_FONTS.has(family)) {
        return;
    }

    LOADED_FONTS.add(family);

    const link = document.createElement('link');

    link.rel = 'stylesheet';
    link.href = `https://fonts.googleapis.com/css2?family=${encodeURIComponent(family).replace(/%20/g, '+')}:wght@400;500;600;700;800&display=swap`;

    document.head.appendChild(link);
}

/**
 * Terapkan warna aksen & font dari Kustomisasi ke CSS variables saat
 * navigasi SPA, melengkapi inline style Blade pada first paint.
 */
export function useSiteTheme() {
    const { siteTheme } = useSite();

    function apply(): void {
        if (typeof document === 'undefined') {
            return;
        }

        const theme = siteTheme.value;

        if (!theme) {
            return;
        }

        const root = document.documentElement;

        root.style.setProperty('--color-primary', theme.primary_color);
        root.style.setProperty(
            '--color-primary-dark',
            mix(theme.primary_color, '#000000', 0.22),
        );
        root.style.setProperty(
            '--color-primary-light',
            mix(theme.primary_color, '#ffffff', 0.87),
        );
        root.style.setProperty('--color-accent', theme.accent_color);
        root.style.setProperty(
            '--font-sans',
            `'${theme.font_body}', ui-sans-serif, system-ui, sans-serif`,
        );
        root.style.setProperty(
            '--font-display',
            `'${theme.font_heading}', 'Inter', ui-sans-serif, system-ui, sans-serif`,
        );

        ensureFontLoaded(theme.font_body);
        ensureFontLoaded(theme.font_heading);
    }

    onMounted(apply);
    watch(siteTheme, apply, { deep: true });

    return { apply };
}
