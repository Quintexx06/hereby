import { usePage } from '@inertiajs/vue3';
import type { Translations } from '@/types';

type Replacements = Record<string, string | number>;

function lookup(tree: Translations, key: string): string | undefined {
    let node: string | Translations | undefined = tree;

    for (const segment of key.split('.')) {
        if (typeof node !== 'object') {
            return undefined;
        }

        node = node[segment];
    }

    return typeof node === 'string' ? node : undefined;
}

/**
 * Translate with Laravel's `lang/` files, shared through Inertia.
 * `t('invitation.reply_by', { date })` replaces `:date`. Missing keys
 * render the key itself so they are easy to spot.
 */
export function useTrans() {
    const page = usePage();

    function t(key: string, replacements: Replacements = {}): string {
        const line = lookup(page.props.translations, key) ?? key;

        return Object.entries(replacements).reduce(
            (text, [name, value]) => text.replaceAll(`:${name}`, String(value)),
            line,
        );
    }

    /** BCP 47 tag of the current locale, e.g. `de-CH`. */
    const localeTag = (): string => page.props.locale.replace('_', '-');

    return { t, localeTag };
}
