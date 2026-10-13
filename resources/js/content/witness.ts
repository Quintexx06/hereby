/** Copy for the witnesses' private page (spec 2026-10-13). German, as the app. */

export const witnessPage = {
    title: 'Für die Trauzeugen',
    lede: (couple: string) =>
        `Was die Gäste für den Abend von ${couple} vorbereiten. Ihr entscheidet und bringt es in eine Reihenfolge. Das Brautpaar sieht nur, wie viele Einlagen es sind.`,
    private:
        'Dieser Link ist privat. Teilt ihn nur mit den anderen Trauzeugen.',
    programme: 'Einlagen',
    empty: 'Noch keine Einlagen. Gäste melden sie über ihre persönliche Einladung an.',
    accept: 'Annehmen',
    decline: 'Ablehnen',
    up: 'Nach oben',
    down: 'Nach unten',
    print: 'Ablauf drucken',
    total: (minutes: number, budget: number | null) =>
        budget
            ? `${minutes} von ${budget} Minuten verplant`
            : `${minutes} Minuten verplant`,
    over: 'Mehr als der Zeitrahmen des Brautpaars.',
    kinds: {
        speech: 'Rede',
        game: 'Spiel',
        music: 'Musik',
        video: 'Film oder Diashow',
        other: 'Etwas anderes',
    } as Record<string, string>,
    status: {
        pending: 'Offen',
        accepted: 'Angenommen',
        declined: 'Abgelehnt',
    } as Record<string, string>,
};
