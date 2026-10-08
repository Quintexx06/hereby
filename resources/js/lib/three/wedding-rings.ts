import { Group, Mesh, MeshPhysicalMaterial, TorusGeometry } from 'three';
import { createLoop, createStudio } from '@/lib/three/studio';
import type { ThreeScene } from '@/lib/three/studio';

/**
 * Two interlinked bands, rose gold and platinum, lit by a studio
 * environment so the metal has real reflections. Decorative only.
 */
export function createWeddingRings(
    canvas: HTMLCanvasElement,
    animate: boolean,
): ThreeScene {
    const { renderer, scene, camera, dispose } = createStudio(canvas);
    camera.position.set(0, 0.4, 7.2);
    // Aim at the rings so they sit in the centre of the canvas. The target is a
    // little below the origin because the tilted bands hang low.
    camera.lookAt(0, -0.25, 0);

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

    const pointer = { x: 0, y: 0 };
    let scroll = 0;

    const loop = createLoop((time) => {
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
    }, animate);

    return {
        setPointer: (x, y) => {
            pointer.x = x;
            pointer.y = y;
        },
        setScroll: (progress) => {
            scroll = progress;
        },
        setActive: loop.setActive,
        destroy: () => {
            loop.stop();
            band.dispose();
            gold.dispose();
            platinum.dispose();
            dispose();
        },
    };
}
