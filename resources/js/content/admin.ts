/** Copy for the team's admin (Swiss German). Roadmap 1.1. */
export const adminCopy = {
    title: 'Hochzeiten',
    lede: 'Alle Hochzeiten auf Hereby. Öffnet eine, um sie mit den Seiten des Paars einzurichten.',
    newCouple: 'Hochzeit für ein Paar anlegen',
    newCoupleHint:
        'Legt Konto und Entwurf an. Das Paar bekommt einen Link, um sein Passwort zu wählen.',
    email: 'E-Mail des Paars',
    partnerOne: 'Vorname',
    partnerTwo: 'Vorname',
    create: 'Anlegen und einladen',
    draft: (step: string) => `Entwurf, bei «${step}»`,
    active: 'Aktiv',
    households: (count: number) =>
        count === 1 ? '1 Haushalt' : `${count} Haushalte`,
    answered: (count: number) => `${count} geantwortet`,
    open: 'Einrichtung',
    guests: 'Gäste',
    content: 'Inhalte',
    preview: 'Vorschau',
    empty: 'Noch keine Hochzeiten.',
};
