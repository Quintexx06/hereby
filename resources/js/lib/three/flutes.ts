import {
    DoubleSide,
    Group,
    LatheGeometry,
    Mesh,
    MeshPhysicalMaterial,
    SphereGeometry,
    Vector2,
} from 'three';
import { createLoop, createStudio } from '@/lib/three/studio';
import type { ThreeScene } from '@/lib/three/studio';

/** Half a flute's silhouette, turned on a lathe: foot, stem, tall bowl. */
const glassProfile = [
    [0, 0],
    [0.42, 0],
    [0.44, 0.03],
    [0.08, 0.08],
    [0.05, 0.2],
    [0.05, 1.05],
    [0.12, 1.2],
    [0.27, 1.6],
    [0.32, 2.2],
    [0.33, 2.75],
].map(([x, y]) => new Vector2(x, y));

/** The champagne inside, a little narrower and short of the rim. */
const wineProfile = [
    [0, 1.16],
    [0.1, 1.2],
    [0.24, 1.6],
    [0.29, 2.2],
    [0, 2.2],
].map(([x, y]) => new Vector2(x, y));

/**
 * Two champagne flutes leaning in for a toast, bubbles rising in each,
 * clinking gently every few seconds. Decorative only.
 */
export function createFlutes(
    canvas: HTMLCanvasElement,
    animate: boolean,
): ThreeScene {
    const { renderer, scene, camera, dispose } = createStudio(canvas);
    camera.position.set(0, 1.4, 7.4);
    camera.lookAt(0, 1.3, 0);

    const glass = new LatheGeometry(glassProfile, 64);
    const wine = new LatheGeometry(wineProfile, 48);
    const bubble = new SphereGeometry(0.022, 8, 8);
    /* On porcelain there is nothing to refract: tinted, reflective glass reads better. */
    const glassMaterial = new MeshPhysicalMaterial({
        color: 0x8f8580,
        metalness: 0.1,
        roughness: 0.04,
        clearcoat: 1,
        clearcoatRoughness: 0.05,
        transparent: true,
        opacity: 0.32,
        depthWrite: false,
        side: DoubleSide,
    });
    const wineMaterial = new MeshPhysicalMaterial({
        color: 0xe2b85c,
        roughness: 0.2,
        clearcoat: 0.8,
        emissive: 0x6b4a10,
        emissiveIntensity: 0.25,
    });
    const bubbleMaterial = new MeshPhysicalMaterial({
        color: 0xfff6dd,
        roughness: 0.1,
        transparent: true,
        opacity: 0.8,
    });

    const flute = (lean: number) => {
        const holder = new Group();
        const bubbles = Array.from({ length: 14 }, (_, index) => {
            const dot = new Mesh(bubble, bubbleMaterial);
            dot.position.x = Math.sin(index * 2.4) * 0.12;
            dot.position.z = Math.cos(index * 2.4) * 0.12;
            holder.add(dot);

            return { dot, offset: index / 14 };
        });
        holder.add(
            new Mesh(wine, wineMaterial),
            new Mesh(glass, glassMaterial),
        );
        holder.rotation.z = lean;

        return { holder, bubbles, lean };
    };

    const left = flute(-0.16);
    const right = flute(0.16);
    left.holder.position.x = -0.62;
    right.holder.position.x = 0.62;
    const pair = new Group();
    pair.add(left.holder, right.holder);
    scene.add(pair);

    const pointer = { x: 0, y: 0 };

    const loop = createLoop((time) => {
        /* A toast every ~5s: the rims meet, then part. */
        const toast = Math.max(0, Math.sin(time * 1.25)) ** 8 * 0.12;
        left.holder.rotation.z = left.lean - toast;
        right.holder.rotation.z = right.lean + toast;

        [left, right].forEach(({ bubbles }) => {
            bubbles.forEach(({ dot, offset }) => {
                dot.position.y = 1.25 + ((time * 0.35 + offset) % 1) * 0.9;
            });
        });
        pair.rotation.y +=
            (pointer.x * 0.5 + Math.sin(time * 0.3) * 0.2 - pair.rotation.y) *
            0.05;
        renderer.render(scene, camera);
    }, animate);

    return {
        setPointer: (x, y) => {
            pointer.x = x;
            pointer.y = y;
        },
        setScroll: () => {},
        setActive: loop.setActive,
        destroy: () => {
            loop.stop();
            [glass, wine, bubble].forEach((g) => g.dispose());
            [glassMaterial, wineMaterial, bubbleMaterial].forEach((m) =>
                m.dispose(),
            );
            dispose();
        },
    };
}
