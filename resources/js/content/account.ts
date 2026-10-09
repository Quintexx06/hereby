/** Copy for the couple's account pages (Swiss German, "ihr"). */
export const account = {
    title: 'Konto',
    lede: 'Wer sich anmeldet und wie.',
    nav: {
        profile: 'Profil',
        security: 'Sicherheit',
    },
    navLabel: 'Kontobereiche',
    save: 'Speichern',
    saved: 'Gespeichert',
};

export const profileCopy = {
    title: 'Profil',
    lede: 'So sprechen wir euch in E-Mails an.',
    name: 'Name',
    email: 'E-Mail-Adresse',
    unverified: 'Diese E-Mail-Adresse ist noch nicht bestätigt.',
    resend: 'Bestätigungslink erneut senden',
    resent: 'Wir haben euch einen neuen Link geschickt.',
};

export const deleteCopy = {
    title: 'Konto löschen',
    lede: 'Löscht euer Konto, eure Hochzeit, alle Gäste und ihre Antworten. Das lässt sich nicht rückgängig machen.',
    start: 'Konto löschen',
    password: 'Passwort zur Bestätigung',
    confirm: 'Endgültig löschen',
    cancel: 'Abbrechen',
};

export const userMenu = { settings: 'Konto', logout: 'Abmelden' };

export const passwordCopy = {
    title: 'Passwort ändern',
    lede: 'Ein langes Passwort, das ihr nirgends sonst verwendet.',
    current: 'Aktuelles Passwort',
    next: 'Neues Passwort',
    confirm: 'Neues Passwort wiederholen',
};

export const accountPanel = {
    since: (date: string) => `Bei Hereby seit ${date}`,
    data: 'Eure Daten',
    onlyYours: 'Nur für eure Hochzeit',
    onlyYoursHint:
        'Keine Werbung, kein Weiterverkauf, keine Anbieterangebote an eure Gäste.',
    deletes: (date: string) => `Gelöscht am ${date}`,
    deletesHint:
        'Hochzeit, Gäste und Antworten verschwinden automatisch, ohne dass ihr daran denken müsst.',
    deletesOpen: 'Gelöscht nach der Hochzeit',
    allergies: 'Allergien verschlüsselt',
    allergiesHint: (guests: number) =>
        guests === 1
            ? 'Für euren 1 Gast nur lesbar für euch und die Küche.'
            : `Für eure ${guests} Gäste nur lesbar für euch und die Küche.`,
    signIn: 'Anmeldung',
    twoFactorOn: 'Zwei-Faktor aktiv',
    twoFactorOff: 'Zwei-Faktor aus',
    passkeys: (count: number) =>
        count === 0
            ? 'Kein Passkey'
            : count === 1
              ? '1 Passkey'
              : `${count} Passkeys`,
    privacy: 'Datenschutz lesen',
};
