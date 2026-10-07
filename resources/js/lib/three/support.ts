/**
 * True when the browser can create a WebGL2 context. Checked before the
 * three.js chunk is even downloaded, so old devices get the static page.
 */
export function supportsWebGL(): boolean {
    try {
        const canvas = document.createElement('canvas');

        return Boolean(canvas.getContext('webgl2'));
    } catch {
        return false;
    }
}
