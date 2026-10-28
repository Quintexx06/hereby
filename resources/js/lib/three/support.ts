/**
 * True when the browser can create a WebGL2 context on real graphics
 * hardware. Checked before the three.js chunk is even downloaded, so old
 * devices get the static page, and so do browsers that would draw in
 * software (a blocked GPU, a remote desktop): there a scene stalls the page
 * for seconds instead of decorating it.
 */
export function supportsWebGL(): boolean {
    try {
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('webgl2', {
            failIfMajorPerformanceCaveat: true,
        });

        context?.getExtension('WEBGL_lose_context')?.loseContext();

        return Boolean(context);
    } catch {
        return false;
    }
}
