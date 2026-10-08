import {
    ACESFilmicToneMapping,
    PerspectiveCamera,
    PMREMGenerator,
    Scene,
    Timer,
    WebGLRenderer,
} from 'three';
import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';

/** What every decorative 3D scene exposes to `useThreeScene`. */
export type ThreeScene = {
    setPointer: (x: number, y: number) => void;
    setScroll: (progress: number) => void;
    setActive: (active: boolean) => void;
    destroy: () => void;
};

export type SceneFactory = (
    canvas: HTMLCanvasElement,
    animate: boolean,
) => ThreeScene;

/**
 * A transparent renderer lit by a soft studio environment (so metals and
 * petals get real reflections), with a camera that tracks the canvas size.
 */
export function createStudio(canvas: HTMLCanvasElement, fov = 30) {
    const renderer = new WebGLRenderer({
        canvas,
        alpha: true,
        antialias: true,
    });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.toneMapping = ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.05;

    const scene = new Scene();
    const pmrem = new PMREMGenerator(renderer);
    const environment = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;
    scene.environment = environment;

    const camera = new PerspectiveCamera(fov, 1, 0.1, 50);

    const resize = () => {
        const { clientWidth: width, clientHeight: height } = canvas;
        renderer.setSize(width, height, false);
        camera.aspect = width / Math.max(height, 1);
        camera.updateProjectionMatrix();
    };
    const observer = new ResizeObserver(resize);
    observer.observe(canvas);
    resize();

    return {
        renderer,
        scene,
        camera,
        dispose: () => {
            observer.disconnect();
            environment.dispose();
            pmrem.dispose();
            renderer.dispose();
        },
    };
}

/**
 * A render loop that can pause off screen. Without `animate` (reduced
 * motion) it draws one still frame and never ticks.
 */
export function createLoop(render: (time: number) => void, animate: boolean) {
    const timer = new Timer();
    let frame = 0;
    let active = true;

    const draw = () => {
        timer.update();
        render(timer.getElapsed());
    };
    const tick = () => {
        frame = requestAnimationFrame(tick);
        draw();
    };

    if (animate) {
        tick();
    } else {
        draw();
    }

    return {
        setActive: (next: boolean) => {
            if (!animate || next === active) {
                return;
            }
            active = next;
            if (active) {
                tick();
            } else {
                cancelAnimationFrame(frame);
            }
        },
        stop: () => cancelAnimationFrame(frame),
    };
}
