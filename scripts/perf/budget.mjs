/**
 * Performance budget for guest pages (roadmap 1.13, product rule 2): every
 * guest page loads in under two seconds on mobile data. Runs Lighthouse with
 * its default mobile throttling (slow 4G, mid-range phone) several times per
 * page, takes the median and fails above the budget in budget.json.
 *
 *   node scripts/perf/budget.mjs http://127.0.0.1:8099/i/<token>
 */
import { execFileSync } from 'node:child_process';
import { readFileSync, mkdtempSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';

const base = process.argv[2];
const config = JSON.parse(
    readFileSync(new URL('./budget.json', import.meta.url), 'utf8'),
);
const dir = mkdtempSync(join(tmpdir(), 'hereby-perf-'));
const median = (values) =>
    [...values].sort((a, b) => a - b)[Math.floor(values.length / 2)];
let failed = false;

if (!base) {
    console.error('Usage: node scripts/perf/budget.mjs <guest link URL>');
    process.exit(2);
}

for (const suffix of config.pages) {
    const url = base + suffix;
    const results = [];

    for (let run = 0; run < config.runs; run++) {
        const output = join(dir, `run-${results.length}.json`);
        execFileSync(
            'npx',
            [
                '-y',
                'lighthouse@12.6.1',
                url,
                '--quiet',
                '--only-categories=performance',
                '--chrome-flags=--headless=new --no-sandbox',
                '--output=json',
                `--output-path=${output}`,
            ],
            { stdio: 'inherit' },
        );
        results.push(JSON.parse(readFileSync(output, 'utf8')).audits);
    }

    console.log(`\n${url}`);

    for (const [audit, limit] of Object.entries(config.budget)) {
        const value = median(
            results.map((audits) => audits[audit].numericValue),
        );
        const ok = value <= limit;
        failed ||= !ok;
        console.log(
            `  ${ok ? 'ok  ' : 'FAIL'} ${audit.padEnd(26)} ${Math.round(value * 1000) / 1000} (budget ${limit})`,
        );
    }
}

process.exit(failed ? 1 : 0);
