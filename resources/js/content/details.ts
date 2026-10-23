/** «Eckdaten»: the setup questions for a wedding that is already live. */
export const details = {
    title: 'Eckdaten',
    lede: 'Namen, Datum, Ort, Ablauf, Sprachen und Look. Was ihr hier ändert, sehen eure Gäste beim nächsten Besuch, und abonnierte Kalender holen es sich selbst.',
    tabs: 'Bereiche',
    save: 'Speichern',
    saved: 'Gespeichert',
    unsaved: 'Noch nicht gespeichert',
    saveDesign: (name: string) => `${name} übernehmen`,
    design: (name: string) => `Euer Entwurf: ${name}`,
    designUnsaved: (name: string) => `${name} gewählt, noch nicht gespeichert`,
    programmeNote:
        'Antworten bleiben erhalten, wenn ihr Zeiten oder Namen ändert. Ein neuer Teil geht an alle Haushalte (auf «Gäste» passt ihr das an); ein entfernter Teil nimmt seine Antworten mit.',
    dateNote:
        'Verschiebt ihr das Datum, wandert der ganze Ablauf mit, zur selben Uhrzeit.',
};
