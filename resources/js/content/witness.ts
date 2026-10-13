/** Copy for the witnesses' private page (spec 2026-10-13). German, as the app. */

export const witnessPage = {
    title: 'Für die Trauzeugen',
    lede: (couple: string) =>
        `Was die Gäste für ${couple} vorbereiten. Ihr entscheidet und bringt es in eine Reihenfolge. Das Brautpaar sieht nur, wie viel es ist.`,
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
    groups: 'Gruppenfotos',
    groupsHint:
        'Tippt auf «Aufrufen», wenn eine Gruppe dran ist: Die Gäste bekommen eine Mail und sehen es auf ihrer Einladung.',
    groupsEmpty: 'Das Brautpaar hat noch keine Gruppenfotos eingetragen.',
    call: 'Aufrufen',
    callAgain: 'Nochmals aufrufen',
    calledAt: (time: string) => `Aufgerufen um ${time}`,
    newspaper: 'Hochzeitszeitung',
    newspaperHint:
        'Die Geschichten der Gäste. Entfernt, was nicht passt, und druckt die Zeitung.',
    newspaperEmpty: 'Noch keine Geschichten.',
    newspaperPrint: 'Zeitung drucken',
    remove: 'Entfernen',
    removeConfirm: 'Diese Geschichte wirklich entfernen?',
    by: (name: string) => `von ${name}`,
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
