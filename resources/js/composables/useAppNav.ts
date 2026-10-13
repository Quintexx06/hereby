import { router } from '@inertiajs/vue3';
import type { InjectionKey, Ref } from 'vue';
import { inject, onBeforeUnmount, onMounted, provide, ref } from 'vue';

/** The cookie the server reads for the first paint (HandleInertiaRequests). */
const COOKIE = 'sidebar_state';
const MAX_AGE = 60 * 60 * 24 * 365;

type AppNav = {
    /** Desktop preference: full nav (true) or the icon rail (false). */
    expanded: Ref<boolean>;
    /** The nav laid over the page, on tablets and phones. */
    drawer: Ref<boolean>;
    toggle: () => void;
    close: () => void;
};

const key: InjectionKey<AppNav> = Symbol('app-nav');

/**
 * State for the app's own nav. Which layout it takes (full, rail, drawer)
 * is decided by CSS media queries alone, so zooming or resizing never
 * remounts it or swaps components; JS only asks the width when the toggle
 * is pressed, to know whether it means "collapse" or "open over the page".
 */
export function provideAppNav(defaultExpanded: boolean): AppNav {
    const expanded = ref(defaultExpanded);
    const drawer = ref(false);

    const close = () => (drawer.value = false);
    const toggle = () => {
        if (window.matchMedia('(max-width: 1023.98px)').matches) {
            drawer.value = !drawer.value;

            return;
        }

        expanded.value = !expanded.value;
        document.cookie = `${COOKIE}=${expanded.value}; path=/; max-age=${MAX_AGE}`;
    };

    const onKey = (event: KeyboardEvent) => {
        if (event.key === 'Escape') {
            close();
        } else if (event.key === 'b' && (event.metaKey || event.ctrlKey)) {
            event.preventDefault();
            toggle();
        }
    };

    let stopNavigate: (() => void) | undefined;
    onMounted(() => {
        window.addEventListener('keydown', onKey);
        stopNavigate = router.on('navigate', close);
    });
    onBeforeUnmount(() => {
        window.removeEventListener('keydown', onKey);
        stopNavigate?.();
    });

    const nav = { expanded, drawer, toggle, close };
    provide(key, nav);

    return nav;
}

export function useAppNav(): AppNav {
    const nav = inject(key);
    if (!nav) {
        throw new Error('useAppNav() needs provideAppNav() above it.');
    }

    return nav;
}
