#!/usr/bin/env node
/**
 * PostToolUse hook for Edit/Write/MultiEdit.
 *  - PHP: format the edited file with Pint.
 *  - Vue/CSS/TS in resources/: flag DESIGN.md and CLAUDE.md violations.
 * Exit 2 sends the message back to Claude; it never blocks the edit itself.
 */
import { execFileSync } from 'node:child_process';
import { existsSync, readFileSync } from 'node:fs';
import { relative, resolve } from 'node:path';

const root = process.env.CLAUDE_PROJECT_DIR ?? process.cwd();
const input = JSON.parse(readFileSync(0, 'utf8') || '{}');
const filePath = input.tool_input?.file_path;

if (!filePath || !existsSync(filePath)) {
    process.exit(0);
}

const file = relative(root, resolve(filePath)).replaceAll('\\', '/');

if (/^(app|config|database|routes|tests)\/.*\.php$/.test(file)) {
    try {
        execFileSync('php', ['vendor/bin/pint', file, '--quiet'], { cwd: root, stdio: 'ignore' });
    } catch {
        // Pint problems surface in `composer ci:check`; never block an edit here.
    }
    process.exit(0);
}

const isFrontend = /^resources\/(js|css)\//.test(file) && /\.(vue|ts|css)$/.test(file);
const isGenerated = /^resources\/js\/(components\/ui|actions|routes|wayfinder)\//.test(file);

if (!isFrontend || isGenerated || file.startsWith('resources/css/theme/')) {
    process.exit(0);
}

const source = readFileSync(filePath, 'utf8');
const rules = [
    [/#[0-9a-fA-F]{3,8}\b(?![-\w])/, 'raw hex colour: use a semantic token (DESIGN.md "The Token Rule")'],
    [/\b(bg|text|border|ring|fill|stroke)-(neutral|gray|slate|zinc|stone|black|white)\b/, 'raw neutral utility: use bg-background / text-muted-foreground / border-border'],
    [/\beyebrow\b|tracking-\[0\.1\d?em\][^"]*uppercase|uppercase[^"]*tracking-wide/, 'eyebrow / tracked-uppercase label: banned by DESIGN.md "No labels"'],
    [/from ['"]gsap/, 'import GSAP only from @/lib/gsap (CLAUDE.md rule 7)'],
];

/** Template-only rules: CSS partials may legitimately hold scrims and the italic accent. */
const templateRules = [
    [/\b(bg|text|border)-(porcelain|night|sand|blush|moss|ember)-\d/, 'raw palette utility outside resources/css: use bg-brand, text-foreground…'],
    [/class="[^"]*\bitalic\b/, 'raw `italic` utility: use the `.accent` class (DESIGN.md, the one accent voice)'],
    [/bg-(linear|gradient|radial)-|bg-clip-text|backdrop-blur/, 'gradient, gradient text or glass in a template: scrims belong in a named CSS class'],
];

const problems = [...rules, ...(file.endsWith('.css') ? [] : templateRules)]
    .filter(([pattern]) => !(file === 'resources/js/lib/gsap.ts' && pattern.source.includes('gsap')))
    .filter(([pattern]) => pattern.test(source)).map(([, message]) => `- ${message}`);
const lines = source.split('\n').length;

if (file.endsWith('.vue') && lines > 150) {
    problems.push(`- ${lines} lines: split the SFC (CLAUDE.md rule 3, ~150 lines max)`);
}

if (problems.length) {
    process.stderr.write(`Design check for ${file}:\n${problems.join('\n')}\nFix these or say why the exception is intended.\n`);
    process.exit(2);
}
