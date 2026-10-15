/** Copy for the team's «Protokoll». German only, like the rest of /admin. */
export const adminLog = {
    title: 'Protokoll',
    lede: 'Warnungen und Fehler aus der laufenden App, ohne Personendaten. Bei einem Fehler kommt eine Mail, höchstens eine pro Stunde und Problem. Einträge verschwinden 30 Tage nach dem letzten Auftreten.',
    filters: { offen: 'Offen', fehler: 'Nur Fehler', erledigt: 'Erledigt' },
    levels: {
        warning: 'Warnung',
        error: 'Fehler',
        critical: 'Kritisch',
        alert: 'Alarm',
        emergency: 'Notfall',
    } as Record<string, string>,
    times: (count: number) => (count === 1 ? 'einmal' : `${count}×`),
    lastSeen: (when: string) => `zuletzt ${when}`,
    firstSeen: (when: string) => `seit ${when}`,
    trace: 'Ablauf im Code',
    resolve: 'Erledigt',
    reopen: 'Wieder öffnen',
    clear: 'Erledigte löschen',
    empty: {
        offen: 'Alles ruhig. Keine offenen Warnungen oder Fehler.',
        fehler: 'Keine offenen Fehler.',
        erledigt: 'Noch nichts als erledigt markiert.',
    } as Record<string, string>,
};
