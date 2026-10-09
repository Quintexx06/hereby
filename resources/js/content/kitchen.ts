/** Copy for "Küche & Service" (Swiss German, "ihr"). Spec: 2026-10-09-kitchen-sheets-design. */
export const kitchenPage = {
    title: 'Küche & Service',
    lede: 'Die Zahlen für eure Location: wer zu welchem Teil kommt, welche Menüs, wie viele Kinder. Druckt das Blatt aus oder schickt die Liste als Excel.',
    print: 'Küchenblatt öffnen',
    printHint: 'Mit allen Allergien, zum Drucken oder als PDF.',
    csv: 'Gästeliste für Excel',
    csvHint: 'Eine Zeile pro Person, mit Antworten, Menüs und Allergien.',
    attending: (count: number) =>
        count === 1 ? '1 Person' : `${count} Personen`,
    children: (count: number) =>
        count === 1 ? 'davon 1 Kind' : `davon ${count} Kinder`,
    pending: (count: number) =>
        count === 1 ? '1 Antwort offen' : `${count} Antworten offen`,
    allergies: (count: number) =>
        count === 0
            ? 'Bisher keine Allergien angegeben.'
            : count === 1
              ? '1 Person hat Allergien angegeben. Die Details stehen im Küchenblatt.'
              : `${count} Personen haben Allergien angegeben. Die Details stehen im Küchenblatt.`,
    service: 'Service',
    shuttle: 'Plätze im Shuttle',
    stays: 'Haushalte mit Übernachtung',
    songs: 'Liederwünsche',
    empty: 'Noch keine Teile im Ablauf.',
};
