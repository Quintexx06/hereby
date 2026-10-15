/** Props the landing page receives from WelcomeController (lang/de_CH/landing.php). */
export type FaqItem = {
    question: string;
    answer: string;
};

export type LandingSeo = {
    title: string;
    description: string;
    image_alt: string;
};

/** A topic page (lang/de_CH/pages.php), rendered by pages/topics/Show.vue. */
export type TopicSection = {
    title: string;
    body?: string[];
    points?: { title: string; body: string }[];
};

export type TopicShot = {
    src: string;
    width: number;
    height: number;
    frame: 'phone' | 'browser';
    alt: string;
    caption: string;
};

export type TopicPage = {
    photo: string;
    shot: TopicShot;
    label: string;
    h1: string;
    lede: string;
    sections: TopicSection[];
};

export type RelatedTopic = {
    slug: string;
    label: string;
    description: string;
};
