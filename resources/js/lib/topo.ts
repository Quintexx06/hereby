/** One summit on the map: where it stands, how many lines ring it, their shape. */
type Peak = {
    x: number;
    y: number;
    lines: number;
    step: number;
    /** Stretch across the map, as ridges run longer than they are wide. */
    stretch: number;
    seed: number;
};

const POINTS = 56;

/* A closed, smooth curve through the points (Catmull-Rom as cubic Béziers). */
function smooth(points: [number, number][]): string {
    const at = (index: number) =>
        points[(index + points.length) % points.length];
    const round = (value: number) => Math.round(value * 10) / 10;
    let d = `M${round(at(0)[0])} ${round(at(0)[1])}`;

    for (let index = 0; index < points.length; index++) {
        const [p0, p1, p2, p3] = [
            at(index - 1),
            at(index),
            at(index + 1),
            at(index + 2),
        ];
        const c1 = [p1[0] + (p2[0] - p0[0]) / 6, p1[1] + (p2[1] - p0[1]) / 6];
        const c2 = [p2[0] - (p3[0] - p1[0]) / 6, p2[1] - (p3[1] - p1[1]) / 6];
        d += `C${round(c1[0])} ${round(c1[1])} ${round(c2[0])} ${round(c2[1])} ${round(p2[0])} ${round(p2[1])}`;
    }

    return `${d}Z`;
}

/*
 * The contour lines of a Swiss map around the given summits, on a
 * 1200 × 600 sheet. Deterministic, so the server and the browser draw the
 * same lines; irregular, so they read as terrain and not as rings.
 */
export function contourLines(peaks: Peak[]): string[] {
    return peaks.flatMap((peak) =>
        Array.from({ length: peak.lines }, (_, level) => {
            const radius = 18 + level * peak.step;
            const points = Array.from(
                { length: POINTS },
                (_, index): [number, number] => {
                    const angle = (index / POINTS) * Math.PI * 2;
                    const wobble =
                        1 +
                        0.16 * Math.sin(2 * angle + peak.seed) +
                        0.09 *
                            Math.sin(
                                3 * angle + peak.seed * 1.7 + level * 0.35,
                            ) +
                        0.05 *
                            Math.sin(5 * angle + peak.seed * 2.3 - level * 0.2);

                    return [
                        peak.x +
                            Math.cos(angle) * radius * wobble * peak.stretch,
                        peak.y + Math.sin(angle) * radius * wobble,
                    ];
                },
            );

            return smooth(points);
        }),
    );
}

/** The map Arve draws: two summits and a saddle between them. */
export const ARVE_CONTOURS = contourLines([
    { x: 860, y: 210, lines: 11, step: 26, stretch: 1.45, seed: 0.8 },
    { x: 300, y: 430, lines: 8, step: 24, stretch: 1.3, seed: 2.1 },
]);
