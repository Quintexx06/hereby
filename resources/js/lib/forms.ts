/**
 * The first validation message under a prefix, e.g. `guests` for
 * `guests.2.first_name`. Nested array errors are shown once, per group.
 */
export function firstError(
    errors: Partial<Record<string, string>>,
    prefix: string,
): string | undefined {
    return Object.entries(errors).find(
        ([key]) => key === prefix || key.startsWith(`${prefix}.`),
    )?.[1];
}
