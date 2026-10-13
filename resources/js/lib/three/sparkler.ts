import {
    AdditiveBlending,
    BufferAttribute,
    BufferGeometry,
    Color,
    Group,
    LineBasicMaterial,
    LineSegments,
    Points,
    PointsMaterial,
} from 'three';
import { glowTexture } from '@/lib/three/bokeh';
import { createLoop, createStudio } from '@/lib/three/studio';
import type { ThreeScene } from '@/lib/three/studio';

const TRAIL = 160;
const SPARKS = 220;
/** One heart in this many seconds. */
const PERIOD = 6.5;
const GOLD = new Color(1, 0.78, 0.42);
const EMBER = new Color(1, 0.45, 0.12);

/** The classic heart curve, scaled to about three units across. */
function heart(t: number): [number, number] {
    const s = Math.sin(t);

    return [
        (16 * s * s * s) / 11,
        (13 * Math.cos(t) -
            5 * Math.cos(2 * t) -
            2 * Math.cos(3 * t) -
            Math.cos(4 * t)) /
            11,
    ];
}

/**
 * A sparkler drawing a heart in the night, as in a long exposure: the tip
 * traces the curve, its light lingers as a glowing trail that fades from
 * the tail, and short sparks spray off the tip and fall. Both fade by alpha, so
 * nothing dark is ever drawn over the photo. Additive light,
 * meant for a dark photo. Under reduced motion it renders the whole heart,
 * still, without sparks. Decorative only.
 */
export function createSparkler(
    canvas: HTMLCanvasElement,
    animate: boolean,
): ThreeScene {
    const { renderer, scene, camera, dispose } = createStudio(canvas, 35);
    camera.position.set(0, 0, 6);

    const texture = glowTexture();
    const group = new Group();
    scene.add(group);

    /* The trail: soft glow sprites along the curve, brightest at the tip. */
    const trailGeometry = new BufferGeometry();
    const trailPositions = new Float32Array(TRAIL * 3);
    const trailColors = new Float32Array(TRAIL * 4);
    trailGeometry.setAttribute(
        'position',
        new BufferAttribute(trailPositions, 3),
    );
    trailGeometry.setAttribute('color', new BufferAttribute(trailColors, 4));
    const trailMaterial = new PointsMaterial({
        size: 0.4,
        map: texture,
        vertexColors: true,
        transparent: true,
        depthWrite: false,
        blending: AdditiveBlending,
        toneMapped: false,
    });
    group.add(new Points(trailGeometry, trailMaterial));

    /* The sparks: short streaks, head bright, tail dark (so invisible). */
    const sparkGeometry = new BufferGeometry();
    const sparkPositions = new Float32Array(SPARKS * 6);
    const sparkColors = new Float32Array(SPARKS * 8);
    sparkGeometry.setAttribute(
        'position',
        new BufferAttribute(sparkPositions, 3),
    );
    sparkGeometry.setAttribute('color', new BufferAttribute(sparkColors, 4));
    const sparkMaterial = new LineBasicMaterial({
        vertexColors: true,
        transparent: true,
        depthWrite: false,
        blending: AdditiveBlending,
        toneMapped: false,
    });
    group.add(new LineSegments(sparkGeometry, sparkMaterial));

    const sparks = Array.from({ length: SPARKS }, () => ({
        x: 0,
        y: 0,
        vx: 0,
        vy: 0,
        age: 1,
        life: 0,
    }));
    const tip = new Color();
    const spark = new Color();
    const pointer = { x: 0, y: 0 };
    let previous = 0;
    let next = 0;

    const drawTrail = (phase: number, span: number) => {
        for (let i = 0; i < TRAIL; i++) {
            const fade = 1 - i / TRAIL;
            const [x, y] = heart(phase - (i / TRAIL) * span);
            trailPositions.set([x, y, 0], i * 3);
            tip.copy(EMBER).lerp(GOLD, fade);
            trailColors.set([tip.r, tip.g, tip.b, fade * fade], i * 4);
        }
        trailGeometry.attributes.position.needsUpdate = true;
        trailGeometry.attributes.color.needsUpdate = true;
    };

    const drawSparks = (dt: number, [tx, ty]: [number, number]) => {
        sparks.forEach((s, i) => {
            if (s.age >= s.life && next-- > 0) {
                const angle = Math.random() * Math.PI * 2;
                const speed = 1 + Math.random() * 2;
                Object.assign(s, {
                    x: tx,
                    y: ty,
                    vx: Math.cos(angle) * speed,
                    vy: Math.sin(angle) * speed + 0.4,
                    age: 0,
                    life: 0.25 + Math.random() * 0.5,
                });
            }
            s.age += dt;
            s.vy -= 2.2 * dt;
            s.x += s.vx * dt;
            s.y += s.vy * dt;
            const glow = Math.max(0, 1 - s.age / s.life);
            spark.copy(EMBER).lerp(GOLD, glow);
            sparkPositions.set(
                [s.x, s.y, 0, s.x - s.vx * 0.06, s.y - s.vy * 0.06, 0],
                i * 6,
            );
            sparkColors.set(
                [spark.r, spark.g, spark.b, glow, spark.r, spark.g, spark.b, 0],
                i * 8,
            );
        });
        sparkGeometry.attributes.position.needsUpdate = true;
        sparkGeometry.attributes.color.needsUpdate = true;
    };

    const loop = createLoop((time) => {
        const dt = Math.min(time - previous, 0.05);
        previous = time;
        const phase = (time / PERIOD) * Math.PI * 2;
        if (animate) {
            drawTrail(phase, Math.PI * 1.5);
            next = Math.round(dt * 320);
            drawSparks(dt, heart(phase));
        } else {
            drawTrail(Math.PI * 2, Math.PI * 2);
        }
        group.rotation.y += (pointer.x * 0.3 - group.rotation.y) * 0.05;
        group.rotation.x += (-pointer.y * 0.2 - group.rotation.x) * 0.05;
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
            trailGeometry.dispose();
            sparkGeometry.dispose();
            trailMaterial.dispose();
            sparkMaterial.dispose();
            texture.dispose();
            dispose();
        },
    };
}
