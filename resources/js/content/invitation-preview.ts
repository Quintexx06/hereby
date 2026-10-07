/**
 * Demonstration data for the phone preview: the demo wedding from
 * DemoWeddingSeeder, with copy taken from lang/{locale}/invitation.php.
 * Illustrative, not a customer.
 */
export type PreviewEvent = { time: string; name: string; place: string };

export type PreviewHousehold = {
    locale: 'de' | 'fr' | 'en';
    greeting: string;
    title: string;
    date: string;
    heading: string;
    events: PreviewEvent[];
    action: string;
    deadline: string;
};

export const previewHouseholds: PreviewHousehold[] = [
    {
        locale: 'de',
        greeting: 'Für Heidi, Peter und Lina',
        title: 'Anna & Luca heiraten',
        date: '19. Juni 2027',
        heading: 'Eure Einladung',
        events: [
            { time: '15:30', name: 'Apéro', place: 'Seeterrasse, Vitznau' },
            { time: '18:30', name: 'Abendessen', place: 'Grand Salon' },
            { time: '22:00', name: 'Fest', place: 'Bootshaus' },
        ],
        action: 'Jetzt antworten',
        deadline: 'Bitte antwortet bis 1. Mai 2027',
    },
    {
        locale: 'fr',
        greeting: 'Pour Camille et Marco',
        title: 'Anna & Luca se marient',
        date: '19 juin 2027',
        heading: 'Votre invitation',
        events: [
            { time: '15:30', name: 'Apéritif', place: 'Seeterrasse, Vitznau' },
            { time: '18:30', name: 'Dîner', place: 'Grand Salon' },
            { time: '22:00', name: 'Fête', place: 'Bootshaus' },
        ],
        action: 'Répondre',
        deadline: 'Merci de répondre avant le 1 mai 2027',
    },
    {
        locale: 'en',
        greeting: 'For Emma and James',
        title: 'Anna & Luca are getting married',
        date: 'June 19, 2027',
        heading: 'Your invitation',
        events: [{ time: '22:00', name: 'Party', place: 'Bootshaus, Vitznau' }],
        action: 'Reply now',
        deadline: 'Please reply by May 1, 2027',
    },
];
