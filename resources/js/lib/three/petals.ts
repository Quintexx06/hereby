import { Group, Mesh } from 'three';
import { petalGeometry, petalMaterial } from '@/lib/three/roses';
import { createLoop, createStudio } from '@/lib/three/studio';
import type { ThreeScene } from '@/lib/three/studio';

/** Deterministic noise, so the drift looks the same on every visit. */
const seeded = (seed: number) => () => {
    seed = (seed * 16807) % 2147483647;

    return seed / 2147483647;
};

/**
 * Rose petals drifting down through the margin, each turning on its own
 * axis and swaying side to side; they wrap round to the top. Decorative.
 */
export function createPetals(
    canvas: HTMLCanvasElement,
    animate: boolean,
): ThreeScene {
    const { renderer, scene, camera, dispose } = createStudio(canvas);
    camera.position.set(0, 0, 7);
    camera.lookAt(0, 0, 0);

    const geometry = petalGeometry();
    const materials = [0xdc4a68, 0xc8243f, 0xf0a3b3, 0x7a1028].map(
        petalMaterial,
    );
    const random = seeded(7);
    const field = new Group();

    const petals = Array.from({ length: 22 }, (_, index) => {
        const mesh = new Mesh(geometry, materials[index % materials.length]);
        mesh.scale.setScalar(0.32 + random() * 0.3);
        const state = {
            x: (random() - 0.5) * 4.4,
            y: (random() - 0.5) * 4,
            z: (random() - 0.5) * 2.5,
            fall: 0.18 + random() * 0.22,
            sway: 0.3 + random() * 0.5,
            phase: random() * Math.PI * 2,
            spin: (random() - 0.5) * 1.6,
        };
        field.add(mesh);

        return { mesh, state };
    });
    scene.add(field);

    const pointer = { x: 0, y: 0 };

    const loop = createLoop((time) => {
        petals.forEach(({ mesh, state }) => {
            const travel = (state.y - time * state.fall + 2.4) % 4.8;
            mesh.position.set(
                state.x + Math.sin(time * state.sway + state.phase) * 0.35,
                (travel < 0 ? travel + 4.8 : travel) - 2.4,
                state.z,
            );
            mesh.rotation.set(
                time * state.spin + state.phase,
                time * state.spin * 0.7,
                Math.sin(time * 0.8 + state.phase) * 0.6,
            );
        });
        field.rotation.y += (pointer.x * 0.25 - field.rotation.y) * 0.05;
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
            geometry.dispose();
            materials.forEach((material) => material.dispose());
            dispose();
        },
    };
}
