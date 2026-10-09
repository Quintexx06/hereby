export type AppVariant = 'header' | 'sidebar';

/** The decorative 3D scenes a page masthead can carry (lib/three). */
export type MastheadSceneName =
    | 'roses'
    | 'rings'
    | 'petals'
    | 'flutes'
    | 'ribbon';

export type FlashToast = {
    type: 'success' | 'info' | 'warning' | 'error';
    message: string;
};
