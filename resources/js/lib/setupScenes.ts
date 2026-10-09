import {
    celebrations,
    estimates,
    eventTypes,
    languages,
    themes,
} from '@/content/setup';
import { landingPhotos } from '@/content/landing-photos';
import type { LandingPhoto } from '@/content/landing-photos';
import { formatDate } from '@/lib/format';
import { daysUntil, sortProgramme } from '@/lib/setup';
import type { SetupForm, SetupStepKey } from '@/types/setup';

/**
 * One photograph per step, behind the live invitation: the couple, the
 * golden hour, the lake, the table, the guests, the flowers, and back to
 * the couple at the end. Credited stock (public/images/landing/CREDITS.md).
 */
export const scenes: Record<SetupStepKey, LandingPhoto> = {
    paar: landingPhotos.veilKiss,
    datum: landingPhotos.heroVeil,
    ort: landingPhotos.lakeJetty,
    ablauf: landingPhotos.tableCandles,
    gaeste: landingPhotos.sparklers,
    look: landingPhotos.bouquet,
    uebersicht: landingPhotos.veilKiss,
};

/** Tall panels look best with the portrait crop when there is one. */
export function sceneSource(photo: LandingPhoto): string {
    return photo.portrait ?? photo.src;
}

/**
 * The story so far, in one line: each step retells what the couple just
 * decided, so the panel changes with them instead of repeating a caption.
 */
export function storyLine(step: SetupStepKey, form: SetupForm): string {
    const names = [form.partner_one, form.partner_two]
        .filter(Boolean)
        .join(' & ');

    switch (step) {
        case 'paar':
            return names
                ? `${names}. Schön, dass ihr da seid.`
                : 'Erzählt uns von euch.';
        case 'datum':
            if (!form.wedding_date) {
                return 'Euer Tag, euer Datum.';
            }

            return `${formatDate(form.wedding_date, 'de-CH')}, noch ${daysUntil(form.wedding_date)} Tage.`;
        case 'ort':
            if (form.venue_undecided) {
                return 'Der Ort folgt. Alles andere kann schon beginnen.';
            }

            return (
                [form.venue_name, form.venue_town].filter(Boolean).join(', ') ||
                'Wo sagt ihr Ja?'
            );
        case 'ablauf': {
            const rows = sortProgramme(form.events);

            if (!form.celebration || rows.length === 0) {
                return 'Vom ersten Glas bis zum letzten Tanz.';
            }

            const first = rows[0];
            const last = rows[rows.length - 1];

            return `${celebrations[form.celebration].label}: ${rows.length} Teile, von ${eventTypes[first.type]} um ${first.time} bis ${eventTypes[last.type]} um ${last.time}.`;
        }
        case 'gaeste':
            return form.guest_estimate
                ? `${estimates[form.guest_estimate]} Gäste, auf ${form.languages.map((locale) => languages[locale]).join(' und ')}.`
                : 'Wer feiert mit euch?';
        case 'look':
            return `${themes[form.theme].name}: ${themes[form.theme].mood}.`;
        case 'uebersicht':
            return names
                ? `${names}, der Vorhang kann aufgehen.`
                : 'Der Vorhang kann aufgehen.';
    }
}
