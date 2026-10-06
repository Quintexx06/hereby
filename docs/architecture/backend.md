# Backend architecture

Laravel conventions first; add structure only when it pays for itself.

## Request flow

```
Route → FormRequest (validate + authorize) → Controller (thin) → Action → Model
                                                   ↓
                                    Inertia::render(page, Resource props)
```

## Folders

| Path                        | Responsibility                                                    |
| --------------------------- | ----------------------------------------------------------------- |
| `app/Http/Controllers/<Domain>` | Thin: resolve input, call one Action, return a response. ≤ ~7 methods (resourceful). |
| `app/Http/Requests/<Domain>`    | Validation and `authorize()`. Share rules through `app/Concerns/*Rules` traits. |
| `app/Actions/<Domain>`          | One business operation per class, with a single `handle()` method. Reused by controllers, jobs, commands and tests. |
| `app/Models`                    | Relationships, casts, scopes. No HTTP and no orchestration. |
| `app/Policies`                  | Authorization per model; always covered by tests. |
| `app/Http/Resources`            | Shape the props sent to Inertia/JSON. Never pass raw models with hidden fields. |
| `app/Enums`                     | Backed enums for states (e.g. `DeclarationStatus`), cast on models. |
| `app/Events`, `app/Listeners`, `app/Jobs`, `app/Notifications` | Side effects, kept out of Actions where possible. |
| `app/Services`                  | Only wrappers around external systems (payment, PDF, AI). Not a dumping ground. |

## Rules

- **Strict models** outside production (`Model::shouldBeStrict()`): no lazy
  loading, no silently dropped attributes. Eager-load on purpose.
- **Immutable dates** (`CarbonImmutable`).
- Destructive DB commands are blocked in production.
- Every feature ships with **Feature tests** (HTTP + Inertia assertions) and
  Action **unit tests** where the logic is non-trivial. Use factories and states.
- Use `php artisan make:*` generators, then trim the output.
- Code style: Pint (`vendor/bin/pint --dirty`). Static analysis: Larastan (`phpstan.neon`).
- Laravel docs: use the Boost MCP `search-docs` tool, not memory.

## Domain map

```
User ─ owns ─ Wedding ─┬─ Event (civil ceremony, dinner…)
                       └─ Household (locale, token) ─ Guest ─ EventResponse ─ Event
                               └── n:m event_household: who is invited to what
```

- Guest-facing routes live in `routes/invitations.php`. They're public, with the
  token as the credential; throttled and `noindex` (ADR 0005).
- Enums in `app/Enums`: `Locale`, `EventType`, `ResponseStatus`, `WeddingTheme`.
- Translations: `lang/{de_CH,fr,it,en}/*.php`, shared via `App\Support\FrontendTranslations` (ADR 0006).
- Retention: `Wedding` is `MassPrunable` (`config/hereby.php`), scheduled in `routes/console.php` (ADR 0007).

## Example: the Phase 1 RSVP

```
app/Actions/Invitations/SubmitRsvp.php             upserts EventResponses in one transaction
app/Http/Requests/Invitations/SubmitRsvpRequest.php only events this household is invited to
app/Http/Controllers/Invitations/RsvpController.php (store, update until the deadline)
resources/js/components/invitation/rsvp/*.vue       one step per question, under 60s total
tests/Feature/Invitations/SubmitRsvpTest.php
```
