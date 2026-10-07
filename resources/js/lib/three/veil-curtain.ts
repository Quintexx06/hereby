import {
    DoubleSide,
    Mesh,
    OrthographicCamera,
    PlaneGeometry,
    Scene,
    ShaderMaterial,
    Timer,
    Vector2,
    WebGLRenderer,
} from 'three';
import { veilFragmentShader, veilVertexShader } from './veil-shaders';

export type VeilCurtain = {
    /** Gathers both panels to the sides; resolves when fully open. */
    open: (durationMs: number) => Promise<void>;
    /** Drives the veil directly (0 closed … 1 gathered), e.g. from scroll. */
    setOpen: (value: number) => void;
    /** Pointer in client pixels; the cloth bulges gently around it. */
    setPointer: (clientX: number, clientY: number) => void;
    /** Pause rendering while the hero is off screen. */
    setActive: (active: boolean) => void;
    destroy: () => void;
};

const OVERLAP = 0.06;

/**
 * The hero's sheer veil: two cloth panels drawn over the photo, which
 * stays a normal <img> underneath (LCP, alt text, no WebGL dependency).
 */
export function createVeilCurtain(
    canvas: HTMLCanvasElement,
    onFirstFrame: () => void,
): VeilCurtain {
    const renderer = new WebGLRenderer({
        canvas,
        alpha: true,
        antialias: true,
        powerPreference: 'high-performance',
    });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.75));
    renderer.setClearColor(0x000000, 0);

    const scene = new Scene();
    const camera = new OrthographicCamera(-1, 1, 1, -1, 0.1, 10);
    camera.position.z = 5;

    const geometry = new PlaneGeometry(1, 2.3, 260, 120);
    const pointer = new Vector2(0, 5);
    const target = new Vector2(0, 5);

    const panels = [-1, 1].map((side) => {
        const material = new ShaderMaterial({
            vertexShader: veilVertexShader,
            fragmentShader: veilFragmentShader,
            transparent: true,
            premultipliedAlpha: true,
            depthWrite: false,
            side: DoubleSide,
            uniforms: {
                uOpen: { value: 0 },
                uTime: { value: 0 },
                uSide: { value: side },
                uAspect: { value: 1 },
                uPanelWidth: { value: 1 },
                uPointer: { value: pointer },
            },
        });
        const mesh = new Mesh(geometry, material);
        mesh.renderOrder = side;
        scene.add(mesh);

        return material;
    });

    let aspect = 1;
    const resize = () => {
        const { clientWidth: width, clientHeight: height } = canvas;
        aspect = width / Math.max(height, 1);
        renderer.setSize(width, height, false);
        camera.left = -aspect;
        camera.right = aspect;
        camera.updateProjectionMatrix();
        panels.forEach((material) => {
            material.uniforms.uAspect.value = aspect;
            material.uniforms.uPanelWidth.value = aspect + OVERLAP;
        });
    };
    const observer = new ResizeObserver(resize);
    observer.observe(canvas);
    resize();

    const timer = new Timer();
    let frame = 0;
    let active = true;
    let firstFrame = true;
    let opening: { from: number; duration: number; done: () => void } | null =
        null;
    let openValue = 0;

    const tick = () => {
        frame = requestAnimationFrame(tick);
        timer.update();
        const time = timer.getElapsed();

        if (opening) {
            openValue = Math.min(
                (time * 1000 - opening.from) / opening.duration,
                1,
            );
            if (openValue >= 1) {
                opening.done();
                opening = null;
            }
        }

        pointer.lerp(target, 0.06);
        panels.forEach((material) => {
            material.uniforms.uTime.value = time;
            material.uniforms.uOpen.value = openValue;
        });
        renderer.render(scene, camera);

        if (firstFrame) {
            firstFrame = false;
            onFirstFrame();
        }
    };
    tick();

    return {
        open: (durationMs) =>
            new Promise((resolve) => {
                if (openValue >= 1) {
                    resolve();

                    return;
                }

                // Re-timing an opening (a visitor skips) keeps earlier callers waiting on the same finish.
                const previous = opening?.done;
                const now = timer.getElapsed() * 1000;
                opening = {
                    from: now - openValue * durationMs,
                    duration: durationMs,
                    done: () => {
                        previous?.();
                        resolve();
                    },
                };
            }),
        setOpen: (value) => {
            opening = null;
            openValue = Math.min(Math.max(value, 0), 1);
        },
        setPointer: (clientX, clientY) => {
            const rect = canvas.getBoundingClientRect();
            target.set(
                ((clientX - rect.left) / rect.width) * 2 * aspect - aspect,
                1 - ((clientY - rect.top) / rect.height) * 2,
            );
        },
        setActive: (next) => {
            if (next === active) {
                return;
            }
            active = next;
            if (active) {
                tick();
            } else {
                cancelAnimationFrame(frame);
            }
        },
        destroy: () => {
            cancelAnimationFrame(frame);
            observer.disconnect();
            geometry.dispose();
            panels.forEach((material) => material.dispose());
            renderer.dispose();
        },
    };
}
