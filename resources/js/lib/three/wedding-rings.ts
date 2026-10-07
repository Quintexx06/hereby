import {
    ACESFilmicToneMapping,
    Group,
    Mesh,
    MeshPhysicalMaterial,
    PerspectiveCamera,
    PMREMGenerator,
    Scene,
    Timer,
    TorusGeometry,
    WebGLRenderer,
} from 'three';
import { RoomEnvironment } from 'three/addons/environments/RoomEnvironment.js';

export type WeddingRings = {
    setPointer: (x: number, y: number) => void;
    setScroll: (progress: number) => void;
    setActive: (active: boolean) => void;
    destroy: () => void;
};

/**
 * Two interlinked bands, rose gold and platinum, lit by a studio
 * environment so the metal has real reflections. Decorative only.
 */
export function createWeddingRings(
    canvas: HTMLCanvasElement,
    animate: boolean,
): WeddingRings {
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

    const camera = new PerspectiveCamera(30, 1, 0.1, 50);
    camera.position.set(0, 0.4, 7.2);

    const band = new TorusGeometry(1, 0.13, 48, 160);
    const gold = new MeshPhysicalMaterial({
        color: 0xe9b3a6,
        metalness: 1,
        roughness: 0.2,
        clearcoat: 0.6,
        clearcoatRoughness: 0.15,
    });
    const platinum = new MeshPhysicalMaterial({
        color: 0xe9e6e1,
        metalness: 1,
        roughness: 0.16,
        clearcoat: 0.6,
    });

    const first = new Mesh(band, gold);
    const second = new Mesh(band, platinum);
    second.position.x = 1;
    second.rotation.x = Math.PI / 2;

    const rings = new Group();
    rings.add(first, second);
    rings.position.x = -0.5;
    const stage = new Group();
    stage.add(rings);
    stage.rotation.set(0.35, -0.5, 0.2);
    scene.add(stage);

    const resize = () => {
        const { clientWidth: width, clientHeight: height } = canvas;
        renderer.setSize(width, height, false);
        camera.aspect = width / Math.max(height, 1);
        camera.updateProjectionMatrix();
    };
    const observer = new ResizeObserver(resize);
    observer.observe(canvas);
    resize();

    const pointer = { x: 0, y: 0 };
    let scroll = 0;
    let frame = 0;
    let active = true;
    const timer = new Timer();

    const render = () => {
        timer.update();
        const time = timer.getElapsed();
        stage.rotation.y +=
            (pointer.x * 0.35 -
                0.5 +
                scroll * 1.6 +
                time * 0.12 -
                stage.rotation.y) *
            0.05;
        stage.rotation.x +=
            (0.35 -
                pointer.y * 0.25 +
                Math.sin(time * 0.4) * 0.08 -
                stage.rotation.x) *
            0.05;
        renderer.render(scene, camera);
    };

    const tick = () => {
        frame = requestAnimationFrame(tick);
        render();
    };

    if (animate) {
        tick();
    } else {
        render();
    }

    return {
        setPointer: (x, y) => {
            pointer.x = x;
            pointer.y = y;
        },
        setScroll: (progress) => {
            scroll = progress;
        },
        setActive: (next) => {
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
        destroy: () => {
            cancelAnimationFrame(frame);
            observer.disconnect();
            band.dispose();
            gold.dispose();
            platinum.dispose();
            environment.dispose();
            pmrem.dispose();
            renderer.dispose();
        },
    };
}
