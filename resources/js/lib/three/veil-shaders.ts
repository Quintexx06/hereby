/**
 * Shaders for the sheer veil curtain (veil-curtain.ts).
 *
 * Each panel is a plane whose u runs from the outer edge (0) to the inner
 * hem (1). `uOpen` gathers the cloth towards the outer edge like a curtain
 * tied back at `TIE` height: rows near the tie-back gather first and
 * tightest, so the fabric drapes instead of sliding like a door.
 *
 * Realism comes from four things real voile does:
 *  - irregular folds that lean and widen towards the hem (gravity),
 *  - wind that moves the hem more, and later, than the top,
 *  - opacity that grows with the viewing angle (light crosses more fabric
 *    on a fold's flank than where it faces you),
 *  - shadowed valleys, a soft sheen on the crests, and fine grain.
 */
export const veilVertexShader = /* glsl */ `
uniform float uOpen;
uniform float uTime;
uniform float uSide;
uniform float uAspect;
uniform float uPanelWidth;
uniform vec2 uPointer;

varying vec3 vPos;
varying vec2 vUv;
varying float vGather;
varying float vFold;

const float TIE = -0.18;
const float FOLDS = 8.0;
const float TAU = 6.2831853;

float easeInOut(float t) {
    return t < 0.5 ? 4.0 * t * t * t : 1.0 - pow(-2.0 * t + 2.0, 3.0) / 2.0;
}

/** Irregular folds: three incommensurate waves whose phase drifts with height. */
float folds(float u, float y, float seed) {
    float p = u * FOLDS * TAU + seed;
    float lean = sin(y * 1.4 + u * 3.1 + seed) * 0.7;
    return sin(p + lean) * 0.62
        + sin(p * 2.17 + 1.9 + y * 0.9) * 0.22
        + sin(p * 0.43 + 0.6 + seed * 2.0) * 0.38;
}

void main() {
    vUv = uv;
    float u = uv.x;
    float y = position.y;
    float seed = uSide * 1.37;
    /** 0 at the rod, 1 at the hem: cloth hangs, so the hem does more. */
    float hang = clamp((1.15 - y) / 2.3, 0.0, 1.0);

    float distanceFromTie = abs(y - TIE);
    float rowDelay = smoothstep(0.0, 1.3, distanceFromTie) * 0.22;
    float t = easeInOut(clamp((uOpen - rowDelay) / 0.78, 0.0, 1.0));

    float gathered = mix(0.075, 0.2, smoothstep(0.0, 1.1, distanceFromTie));
    float x = u * uPanelWidth * mix(1.0, gathered, t);

    float fold = folds(u, y, seed);
    float depth = mix(0.024, 0.055, t) * (0.65 + 0.55 * hang);
    float z = fold * depth;

    float gust = 0.55 + 0.45 * sin(uTime * 0.21 + seed);
    float sway = sin(uTime * 0.65 - hang * 1.6 + u * 3.4 + seed)
        * 0.016 * (0.25 + hang) * gust;
    float ripple = sin(y * 6.0 - uTime * 1.3 + u * 11.0) * 0.003 * hang;
    z += sway + ripple;
    x += sway * 0.9 * (0.3 + u) * (1.0 - t);

    float worldX = uSide < 0.0 ? -uAspect + x : uAspect - x;

    float d = distance(vec2(worldX, y), uPointer);
    z += exp(-d * d * 5.0) * 0.045 * (1.0 - t * 0.6);

    vGather = t;
    vFold = fold;
    vPos = vec3(worldX, y, z);
    gl_Position = projectionMatrix * modelViewMatrix * vec4(worldX, y, z, 1.0);
}
`;

export const veilFragmentShader = /* glsl */ `
varying vec3 vPos;
varying vec2 vUv;
varying float vGather;
varying float vFold;

float hash(vec2 p) {
    return fract(sin(dot(p, vec2(127.1, 311.7))) * 43758.5453);
}

void main() {
    vec3 normal = normalize(cross(dFdx(vPos), dFdy(vPos)));
    if (normal.z < 0.0) {
        normal = -normal;
    }
    vec3 light = normalize(vec3(-0.45, 0.6, 0.65));
    vec3 view = vec3(0.0, 0.0, 1.0);

    float facing = clamp(normal.z, 0.18, 1.0);
    float diffuse = clamp(dot(normal, light) * 0.5 + 0.5, 0.0, 1.0);
    float valley = smoothstep(-0.9, 0.7, vFold);
    float sheen = pow(clamp(dot(reflect(-light, normal), view), 0.0, 1.0), 14.0);

    vec3 lit = vec3(0.995, 0.982, 0.955);
    vec3 shade = vec3(0.70, 0.655, 0.6);
    vec3 color = mix(shade, lit, diffuse * (0.55 + 0.45 * valley));
    color += sheen * 0.22;
    /** Light from the photo behind glows through the thinnest parts. */
    color += vec3(1.0, 0.94, 0.86) * 0.08 * facing * (1.0 - vGather);

    /** Opacity accumulates with path length through the fabric (Beer–Lambert). */
    float thin = 0.34 + vGather * 0.22;
    float alpha = 1.0 - pow(1.0 - thin, 1.0 / facing);
    alpha *= 0.86 + 0.14 * valley;

    float grain = (hash(floor(gl_FragCoord.xy)) - 0.5) * 0.035;
    float threads = sin(vUv.x * 2200.0) * 0.012;
    alpha += grain + threads;

    float hem = smoothstep(0.03, 0.022, vUv.y) - smoothstep(0.008, 0.0, vUv.y) * 0.3;
    alpha += hem * 0.22;
    alpha += smoothstep(0.986, 0.996, vUv.x) * 0.22;
    alpha = clamp(alpha, 0.0, 0.94);

    gl_FragColor = vec4(color * alpha, alpha);
}
`;
