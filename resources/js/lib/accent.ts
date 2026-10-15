/**
 * A couple's own accent colour (any #rrggbb) as the hue and chroma the
 * theme ladder needs (wedding.css). Lightness stays the ladder's, so every
 * colour keeps WCAG AA on porcelain, whatever the couple picks.
 */
export type AccentVars = { '--accent-hue': string; '--accent-chroma': string };

const toLinear = (channel: number): number =>
    channel <= 0.04045 ? channel / 12.92 : ((channel + 0.055) / 1.055) ** 2.4;

export function accentVars(hex: string | null | undefined): AccentVars | null {
    const match = /^#?([0-9a-f]{6})$/i.exec(hex ?? '');

    if (!match) {
        return null;
    }

    const value = parseInt(match[1], 16);
    const [r, g, b] = [value >> 16, (value >> 8) & 255, value & 255].map(
        (channel) => toLinear(channel / 255),
    );

    // Linear sRGB to OKLab (Björn Ottosson).
    const l = Math.cbrt(0.4122214708 * r + 0.5363325363 * g + 0.0514459929 * b);
    const m = Math.cbrt(0.2119034982 * r + 0.6806995451 * g + 0.1073969566 * b);
    const s = Math.cbrt(0.0883024619 * r + 0.2817188376 * g + 0.6299787005 * b);
    const a = 1.9779984951 * l - 2.428592205 * m + 0.4505937099 * s;
    const bb = 0.0259040371 * l + 0.7827717662 * m - 0.808675766 * s;

    const hue = ((Math.atan2(bb, a) * 180) / Math.PI + 360) % 360;
    // Strong colours are calmed to the themes' range; greys stay grey.
    const chroma = Math.min(Math.hypot(a, bb), 0.15);

    return {
        '--accent-hue': hue.toFixed(1),
        '--accent-chroma': chroma.toFixed(3),
    };
}
