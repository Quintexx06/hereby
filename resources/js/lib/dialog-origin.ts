/**
 * Where a click lands inside the studio popup (fixed, inset 0.5rem on phones
 * and 1.5rem from `sm`, at most 76rem wide and centred), as a CSS
 * transform-origin, so the popup grows out of the window that was clicked.
 * Keyboard "clicks" carry no position and grow from the centre.
 */
export function dialogOrigin(event: MouseEvent): string {
    if (event.detail === 0) {
        return '50% 50%';
    }

    const viewport = window.innerWidth;
    const inset = viewport >= 640 ? 24 : 8;
    const width = Math.min(viewport - inset * 2, 76 * 16);
    const left = (viewport - width) / 2;

    return `${Math.round(event.clientX - left)}px ${Math.round(event.clientY - inset)}px`;
}
