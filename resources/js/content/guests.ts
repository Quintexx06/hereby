/** Copy for the guest list and the import (Swiss German, "ihr"). */
export const guestsPage = {
    title: 'Gäste',
    add: 'Gäste hinzufügen',
    search: 'Name suchen',
    all: 'Alle',
    child: 'Kind',
    plusOne: '+1',
    copy: 'Link kopieren',
    copied: 'Kopiert',
    open: 'Einladung öffnen',
    noMatches: 'Niemand passt zu dieser Suche.',
    emptyTitle: 'Wer feiert mit euch?',
    emptyLede:
        'Bringt eure Liste so, wie ihr sie habt. Wir lesen sie, ihr prüft alles, dann erst speichern wir.',
};

export const importCopy = {
    title: 'Gäste hinzufügen',
    modes: { paste: 'Einfügen', file: 'Datei', manual: 'Einzeln' },
    pasteLabel: 'Liste oder Tabellenzellen',
    pasteHint:
        'Kopiert Zellen aus Excel, Numbers oder Google Sheets, oder schreibt einen Haushalt pro Zeile: «Familie Meier: Heidi, Peter, Lina (Kind)».',
    pastePlaceholder:
        'Familie Meier: Heidi, Peter, Lina (Kind)\nCamille et Marco Rossi\nGiulia Bernasconi',
    fileLabel: 'Datei wählen oder hierher ziehen',
    fileHint:
        'Excel (.xlsx), CSV oder Kontakte (.vcf) vom iPhone, Android oder Outlook. Bis 2 MB.',
    read: 'Liste lesen',
    reading: 'Wird gelesen…',
    previewTitle: (count: number) =>
        count === 1 ? '1 Haushalt gefunden' : `${count} Haushalte gefunden`,
    previewHint:
        'Prüft die Liste. Abgewählte Haushalte werden nicht importiert.',
    duplicate: 'Schon auf der Liste',
    importCta: (count: number) =>
        count === 1
            ? '1 Haushalt importieren'
            : `${count} Haushalte importieren`,
    startOver: 'Andere Liste',
    nothingFound:
        'Wir haben keine Namen gefunden. Prüft das Format oder fügt die Zellen direkt ein.',
};

export const manualCopy = {
    household: 'Haushalt',
    householdHint: 'Leer lassen, dann nennen wir ihn nach den Personen.',
    householdPlaceholder: 'z. B. Familie Meier',
    firstName: 'Vorname',
    lastName: 'Nachname',
    child: 'Kind',
    addPerson: 'Person hinzufügen',
    removePerson: 'Person entfernen',
    email: 'E-Mail (für Erinnerungen)',
    language: 'Sprache',
    plusOne: 'Darf eine Begleitung mitbringen',
    save: 'Haushalt speichern',
};
