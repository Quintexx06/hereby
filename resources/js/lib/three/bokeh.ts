import {
    AdditiveBlending,
    NormalBlending,
    BufferAttribute,
    BufferGeometry,
    CanvasTexture,
    Color,
    Points,
    PointsMaterial,
} from 'three';
import { createLoop, createStudio } from '@/lib/three/studio';
import type { ThreeScene } from '@/lib/three/studio';

const COUNT = 90;

/** A soft round sprite: a radial gradient drawn once on a small canvas. */
export function glowTexture(): CanvasTexture {
    const canvas = document.createElement('canvas');
    canvas.width = canvas.height = 64;
    const context = canvas.getContext('2d')!;
    const gradient = context.createRadialGradient(32, 32, 0, 32, 32, 32);
    gradient.addColorStop(0, 'rgba(255,255,255,1)');
    gradient.addColorStop(0.35, 'rgba(255,255,255,0.55)');
    gradient.addColorStop(1, 'rgba(255,255,255,0)');
    context.fillStyle = gradient;
    context.fillRect(0, 0, 64, 64);

    return new CanvasTexture(canvas);
}

/** Any CSS colour (oklch included) to a three.js Color, via the canvas. */
export function cssColor(value: string): Color {
    const context = document.createElement('canvas').getContext('2d')!;
    context.fillStyle = value;
    context.fillRect(0, 0, 1, 1);
    const [r, g, b] = context.getImageData(0, 0, 1, 1).data;

    return new Color(r / 255, g / 255, b / 255);
}

/**
 * Out-of-focus lights drifting in the theme's accent, like a string of
 * fairy lights behind a lens: near ones large and soft, far ones small.
 * The colour comes from the canvas's own --brand token. Decorative only.
 */
export function createBokeh(
    canvas: HTMLCanvasElement,
    animate: boolean,
): ThreeScene {
    const { renderer, scene, camera, dispose } = createStudio(canvas, 50);
    camera.position.set(0, 0, 8);

    const styles = getComputedStyle(canvas);
    const brand = cssColor(
        styles.getPropertyValue('--brand').trim() || '#c8243f',
    );
    /* Additive light glows on dark paper but vanishes on porcelain. */
    const background = cssColor(
        styles.getPropertyValue('--background').trim() || '#fff',
    );
    const onLight =
        background.r * 0.3 + background.g * 0.59 + background.b * 0.11 > 0.5;
    const positions = new Float32Array(COUNT * 3);
    const seeds = new Float32Array(COUNT);
    for (let i = 0; i < COUNT; i++) {
        positions.set(
            [
                (Math.random() - 0.5) * 16,
                (Math.random() - 0.5) * 9,
                (Math.random() - 0.5) * 8,
            ],
            i * 3,
        );
        seeds[i] = Math.random() * Math.PI * 2;
    }

    const geometry = new BufferGeometry();
    geometry.setAttribute('position', new BufferAttribute(positions, 3));
    const texture = glowTexture();
    const material = new PointsMaterial({
        size: 1.7,
        map: texture,
        color: brand,
        transparent: true,
        opacity: onLight ? 0.35 : 0.55,
        depthWrite: false,
        blending: onLight ? NormalBlending : AdditiveBlending,
        sizeAttenuation: true,
    });
    const lights = new Points(geometry, material);
    scene.add(lights);

    const pointer = { x: 0, y: 0 };
    const loop = createLoop((time) => {
        const position = geometry.attributes.position as BufferAttribute;
        for (let i = 0; i < COUNT; i++) {
            position.setY(
                i,
                position.getY(i) + Math.sin(time * 0.4 + seeds[i]) * 0.0025,
            );
        }
        position.needsUpdate = true;
        lights.rotation.y += (pointer.x * 0.15 - lights.rotation.y) * 0.03;
        lights.rotation.x += (-pointer.y * 0.1 - lights.rotation.x) * 0.03;
        material.opacity = (onLight ? 0.3 : 0.45) + Math.sin(time * 0.6) * 0.08;
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
            texture.dispose();
            dispose();
        },
    };
}
