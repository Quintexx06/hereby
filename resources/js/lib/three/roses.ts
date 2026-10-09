import {
    BufferGeometry,
    Color,
    Float32BufferAttribute,
    DoubleSide,
    Group,
    Mesh,
    MeshPhysicalMaterial,
    PlaneGeometry,
} from 'three';
import { createLoop, createStudio } from '@/lib/three/studio';
import type { ThreeScene } from '@/lib/three/studio';

const GOLDEN_ANGLE = Math.PI * (3 - Math.sqrt(5));

/**
 * One petal: a plane bent into a cupped shell, narrow at the base and broad
 * and rounded at the top, with the rim curling outward. Vertex colours
 * darken it towards the base, which gives the bloom its depth. Built once
 * and shared by every petal of every rose.
 */
export function petalGeometry(): BufferGeometry {
    const geometry = new PlaneGeometry(1, 1, 20, 28);
    const position = geometry.attributes.position;
    const shade: number[] = [];

    for (let i = 0; i < position.count; i++) {
        const u = position.getX(i); // -0.5 … 0.5 across
        const v = position.getY(i) + 0.5; // 0 at the base … 1 at the rim
        // Widest at ~60% height, rounded (not pointed) at the rim.
        const width = 1.1 * Math.sin(Math.PI * v ** 1.4) ** 0.5;
        const x = u * width;
        const cup = -0.85 * (2 * u) ** 2 * width * (1 - 0.35 * v);
        const curl = 0.38 * Math.max(v - 0.55, 0) ** 1.6;
        position.setXYZ(i, x, v, cup + curl);

        const light = 0.38 + 0.62 * v ** 0.8;
        shade.push(light, light, light);
    }

    geometry.setAttribute('color', new Float32BufferAttribute(shade, 3));
    geometry.computeVertexNormals();

    return geometry;
}

/**
 * A rose head: petals on a golden-angle spiral, tight and upright in the
 * centre (the bud), larger and opening outward towards the rim.
 */
function createRose(
    geometry: BufferGeometry,
    material: MeshPhysicalMaterial,
    petals = 30,
): Group {
    const rose = new Group();

    for (let i = 0; i < petals; i++) {
        const t = i / (petals - 1);
        const petal = new Mesh(geometry, material);
        petal.scale.setScalar(0.22 + t * 0.55);
        petal.position.z = 0.015 + t * 0.16;
        petal.position.y = -t * 0.12;
        petal.rotation.x = 0.06 + t ** 1.6 * 0.92;

        const pivot = new Group();
        pivot.rotation.y = i * GOLDEN_ANGLE;
        pivot.add(petal);
        rose.add(pivot);
    }

    return rose;
}

export function petalMaterial(color: number): MeshPhysicalMaterial {
    return new MeshPhysicalMaterial({
        color,
        vertexColors: true,
        roughness: 0.62,
        sheen: 0.35,
        sheenRoughness: 0.5,
        sheenColor: new Color(color).offsetHSL(0, 0, 0.12),
        side: DoubleSide,
    });
}

/**
 * Three roses (cherry, oxblood, coral) turning slowly on their own stems,
 * the cluster swaying with the pointer and scroll. Decorative only.
 */
export function createRoses(
    canvas: HTMLCanvasElement,
    animate: boolean,
): ThreeScene {
    const { renderer, scene, camera, dispose } = createStudio(canvas);
    camera.position.set(0, 0, 5.6);
    camera.lookAt(0, 0, 0);

    const geometry = petalGeometry();
    const materials = [
        petalMaterial(0xc8243f),
        petalMaterial(0x7a1028),
        petalMaterial(0xdc4a68),
    ];
    const layout = [
        { x: -0.6, y: 0.2, z: 0, scale: 1, tilt: 0.6, speed: 0.22 },
        { x: 0.62, y: 0.42, z: -0.5, scale: 0.82, tilt: 0.7, speed: -0.17 },
        { x: 0.2, y: -0.6, z: 0.45, scale: 0.74, tilt: 0.5, speed: 0.28 },
    ];

    const cluster = new Group();
    const heads = layout.map((place, index) => {
        const head = createRose(geometry, materials[index]);
        const holder = new Group();
        holder.position.set(place.x, place.y, place.z);
        holder.scale.setScalar(place.scale);
        // Tip the head towards the camera so the spiral reads, not the side.
        holder.rotation.x = place.tilt;
        holder.add(head);
        cluster.add(holder);

        return { head, speed: place.speed };
    });
    scene.add(cluster);

    const pointer = { x: 0, y: 0 };
    let scroll = 0;

    const loop = createLoop((time) => {
        heads.forEach(({ head, speed }, index) => {
            head.rotation.y = time * speed + index * 1.7;
        });
        cluster.rotation.y +=
            (pointer.x * 0.3 + (scroll - 0.5) * 0.4 - cluster.rotation.y) *
            0.05;
        cluster.rotation.x +=
            (-pointer.y * 0.2 +
                Math.sin(time * 0.5) * 0.05 -
                cluster.rotation.x) *
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
            geometry.dispose();
            materials.forEach((material) => material.dispose());
            dispose();
        },
    };
}
