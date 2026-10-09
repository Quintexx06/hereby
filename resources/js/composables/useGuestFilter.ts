import { computed, ref } from 'vue';
import type { Ref } from 'vue';
import type { HouseholdRow, ReplyStatusKey } from '@/types';

/* Search ignores case and accents: "Zoe" finds "Zoë", "muller" finds "Müller". */
const normalise = (value: string): string =>
    value
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .toLowerCase();

/**
 * The guest list's search and reply filter. Runs in the browser: a wedding
 * has at most ~1,000 households. Matches the household or anyone in it.
 */
export function useGuestFilter(households: Ref<HouseholdRow[]>) {
    const query = ref('');
    const filter = ref<ReplyStatusKey | 'all'>('all');

    const visible = computed(() => {
        const needle = normalise(query.value.trim());

        return households.value.filter(
            (household) =>
                (filter.value === 'all' ||
                    household.reply_status === filter.value) &&
                (!needle ||
                    normalise(
                        [
                            household.name,
                            ...household.guests.map((guest) => guest.name),
                        ].join(' '),
                    ).includes(needle)),
        );
    });

    return { query, filter, visible };
}
