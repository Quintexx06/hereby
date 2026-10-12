/** Copy for «Erinnerungen» (Swiss German, "ihr"). Spec: 2026-10-12-paywall-and-memories-design. */
export const memoriesPage = {
    title: 'Erinnerungen',
    lede: 'Die Fotos eurer Gäste, an einem Ort. Teilt den Link, legt ihn als QR-Code auf die Tische, und alle schicken ihre Bilder vom Tag: ohne App, ohne Konto.',
    locked: 'Fotos von allen gehören zu Atelier. Eure Gäste schicken ihre Bilder über einen Link, ihr seht sie hier in einer Galerie.',
    unlock: 'Mit Atelier freischalten',
    linkLabel: 'Euer Foto-Link für alle Gäste',
    linkHint:
        'Jeder mit diesem Link kann Fotos schicken, aber keine ansehen. Die Galerie seht nur ihr.',
    copy: 'Link kopieren',
    copied: 'Kopiert',
    count: (total: number) =>
        total === 1 ? '1 Foto' : `${total.toLocaleString('de-CH')} Fotos`,
    empty: 'Noch keine Fotos. Sobald jemand eines schickt, erscheint es hier.',
    open: 'Foto in voller Grösse öffnen',
    remove: 'Löschen',
    removeConfirm: 'Dieses Foto für immer löschen?',
    newer: 'Neuere',
    older: 'Ältere',
};
