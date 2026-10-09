import {
    BufferAttribute,
    BufferGeometry,
    CatmullRomCurve3,
    DoubleSide,
    Mesh,
    MeshPhysicalMaterial,
    Vector3,
} from 'three';
import { createLoop, createStudio } from '@/lib/three/studio';
import type { ThreeScene } from '@/lib/three/studio';

const SEGMENTS = 220;
const WIDTH = 0.34;

/**
 * A satin ribbon looping through the margin, its curls breathing like
 * fabric in a draught. The strip is rebuilt each frame from a moving
 * curve: two vertices per step, offset along the curve's normal and
 * twisted as it travels. Decorative only.
 */
export function createRibbon(
    canvas: HTMLCanvasElement,
    animate: boolean,
): ThreeScene {
    const { renderer, scene, camera, dispose } = createStudio(canvas);
    camera.position.set(0, 0, 7);
    camera.lookAt(0, 0, 0);

    const base = [
        [-1.9, -0.5, 0],
        [-1.1, 0.8, 0.6],
        [-0.3, -0.3, -0.4],
        [0.3, 0.9, 0.5],
        [0.9, -0.7, -0.3],
        [1.4, 0.4, 0.4],
        [1.9, -0.2, 0],
    ];
    const points = base.map(([x, y, z]) => new Vector3(x, y, z));
    const curve = new CatmullRomCurve3(points);

    const positions = new Float32Array((SEGMENTS + 1) * 2 * 3);
    const geometry = new BufferGeometry();
    geometry.setAttribute('position', new BufferAttribute(positions, 3));
    const indices: number[] = [];
    for (let i = 0; i < SEGMENTS; i++) {
        const a = i * 2;
        indices.push(a, a + 1, a + 2, a + 1, a + 3, a + 2);
    }
    geometry.setIndex(indices);

    const material = new MeshPhysicalMaterial({
        color: 0xc8243f,
        roughness: 0.38,
        sheen: 1,
        sheenRoughness: 0.35,
        sheenColor: 0xffc2cf,
        side: DoubleSide,
    });
    const ribbon = new Mesh(geometry, material);
    scene.add(ribbon);

    const up = new Vector3(0, 0, 1);
    const tangent = new Vector3();
    const side = new Vector3();
    const point = new Vector3();

    const build = (time: number) => {
        points.forEach((p, index) => {
            const [x, y, z] = base[index];
            p.set(
                x,
                y + Math.sin(time * 0.7 + index) * 0.18,
                z + Math.cos(time * 0.5 + index * 1.3) * 0.25,
            );
        });

        for (let i = 0; i <= SEGMENTS; i++) {
            const t = i / SEGMENTS;
            curve.getPointAt(t, point);
            curve.getTangentAt(t, tangent);
            const twist = t * Math.PI * 2.2 + time * 0.4;
            side.crossVectors(tangent, up).normalize();
            side.applyAxisAngle(tangent, twist).multiplyScalar(WIDTH / 2);
            positions.set(
                [
                    point.x + side.x,
                    point.y + side.y,
                    point.z + side.z,
                    point.x - side.x,
                    point.y - side.y,
                    point.z - side.z,
                ],
                i * 6,
            );
        }
        geometry.attributes.position.needsUpdate = true;
        geometry.computeVertexNormals();
    };

    const pointer = { x: 0, y: 0 };

    const loop = createLoop((time) => {
        build(time);
        ribbon.rotation.y += (pointer.x * 0.4 - ribbon.rotation.y) * 0.05;
        ribbon.rotation.x += (-pointer.y * 0.25 - ribbon.rotation.x) * 0.05;
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
            material.dispose();
            dispose();
        },
    };
}
