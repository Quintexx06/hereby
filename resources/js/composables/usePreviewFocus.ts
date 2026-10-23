import { nextTick, onBeforeUnmount, onMounted, ref } from 'vue';
import type { Ref } from 'vue';

/**
 * Which part of the invitation the couple is working on, for the preview to
 * follow: the editor's sections carry `data-preview-target` (a block's
 * `block-<id>`, `top` for the photos, `footer` for the contact). The one
 * crossing the upper middle of the screen wins, and a field that takes the
 * focus wins at once. Call `refresh` after sections were added or moved.
 */
export function usePreviewFocus(root: Ref<HTMLElement | null>) {
    const target = ref<string | null>(null);
    let observer: IntersectionObserver | undefined;

    const pick = (element: Element | null): void => {
        const section = element?.closest<HTMLElement>('[data-preview-target]');
        if (section?.dataset.previewTarget) {
            target.value = section.dataset.previewTarget;
        }
    };

    const onFocus = (event: FocusEvent): void =>
        pick(event.target as Element | null);

    const refresh = (): void => {
        void nextTick(() => {
            observer?.disconnect();
            root.value
                ?.querySelectorAll('[data-preview-target]')
                .forEach((section) => observer?.observe(section));
        });
    };

    onMounted(() => {
        observer = new IntersectionObserver(
            (entries) =>
                entries
                    .filter((entry) => entry.isIntersecting)
                    .forEach((entry) => pick(entry.target)),
            { rootMargin: '-30% 0px -60% 0px' },
        );
        refresh();
        root.value?.addEventListener('focusin', onFocus);
    });

    onBeforeUnmount(() => {
        observer?.disconnect();
        root.value?.removeEventListener('focusin', onFocus);
    });

    return { target, refresh };
}
