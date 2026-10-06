# 0001 — Stack: Laravel + Inertia + Vue + shadcn-vue

**Status:** Accepted (2026-10-06)

## Context
We need a productive full-stack setup with first-class auth, typed routes and an
accessible component base that we fully own.

## Decision
- Official **Laravel Vue starter kit** (`main`, not the stale `v1.0.x` tags):
  Laravel 13, Inertia v3, Vue 3 `<script setup>` + TypeScript, Tailwind v4.
- **Fortify** for auth (registration, email verification, 2FA, passkeys).
- **Wayfinder** for typed routes/actions — never hardcode URLs in Vue.
- **shadcn-vue** (reka-ui) components copied into `resources/js/components/ui`.
- **Laravel Boost** for AI guidelines, skills and the MCP server.

## Consequences
- UI primitives are our code: restyle them via tokens, not forks.
- `Route::redirect()` registers the `QUERY` verb, which Wayfinder can't type
  yet. Register redirects as `GET` (see `routes/settings.php`).
