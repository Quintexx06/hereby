import { nextTick } from 'vue';
import { prefersReducedMotion } from '@/lib/motion';

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

/**
 * After a failed save the message can sit far up the page, or under a
 * sticky save bar: bring the first one into view and focus its field.
 */
export async function revealFirstError(
    root: HTMLElement | null,
): Promise<void> {
    await nextTick();

    const message = [
        ...(root?.querySelectorAll<HTMLElement>('[data-input-error]') ?? []),
    ].find((element) => element.offsetParent && element.textContent?.trim());

    message?.scrollIntoView({
        block: 'center',
        behavior: prefersReducedMotion() ? 'auto' : 'smooth',
    });
    root?.querySelector<HTMLElement>('[aria-invalid="true"]')?.focus({
        preventScroll: true,
    });
}
