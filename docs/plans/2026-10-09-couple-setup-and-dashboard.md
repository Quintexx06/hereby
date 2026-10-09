# Plan: couple setup, guest import, dashboard

Spec: [2026-10-09-couple-setup-and-dashboard-design](../specs/2026-10-09-couple-setup-and-dashboard-design.md)

## Backend

- [x] Migration: weddings gain status, partners, celebration, guest_estimate, languages, venue_*, setup_step; wedding_date nullable while drafting; households gain email
- [x] Enums: `WeddingStatus`, `SetupStep` (order, rules, next), `Celebration`, `GuestEstimate`
- [x] `WeddingPolicy` (owner only) and `User::weddings()`
- [x] Setup: `StartWeddingSetup`, `SaveSetupStep`, `ApplyProgramme`, `SuggestTheme`, `CompleteWeddingSetup`; `WeddingSetupController` (show/update/complete), `UpdateSetupStepRequest`
- [x] Address search: `SwissAddressSearch` (geo.admin, cache, fallback) + `AddressSearchController`
- [x] Guest import: `ParsedHousehold` DTO; parsers `DelimitedListParser`, `PlainListParser`, `VCardParser`, `XlsxReader`; `GuestListReader` (dispatch by input), `FindDuplicateGuests`, `ImportHouseholds`; `GuestImportController` (preview JSON + store), `StoreHouseholdController` (manual)
- [x] Dashboard: `DashboardController` + `WeddingOverview` (counts, segments, next actions); `GuestsController` (list + filters)

## Frontend

- [x] `layouts/SetupLayout` (progress, back, save-and-exit)
- [x] `pages/setup/Step.vue` choosing step components in `components/setup/*`
- [x] `AddressCombobox` (debounced, keyboard), `ChoiceCard` group, `EventPresetRow`
- [x] `pages/Dashboard.vue`: empty / draft / active; `components/dashboard/*`
- [x] `pages/guests/Index.vue` + `components/guests/*` (segments, rows, copy link, import sheet)

## Verify

- [x] Feature tests per controller, unit tests per parser
- [x] `composer ci:check`, screenshots of every step on desktop and phone
