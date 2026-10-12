import { router } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted } from 'vue';

/* Every form on the page that may hold unsaved edits; one guard for all. */
const forms = new Set<() => boolean>();
let stopGuard: (() => void) | null = null;

const anyDirty = (): boolean => [...forms].some((isDirty) => isDirty());

function warnOnUnload(event: BeforeUnloadEvent): void {
    if (anyDirty()) {
        event.preventDefault();
    }
}

function startGuard(question: string): () => void {
    const stopRouter = router.on('before', (event) => {
        const { method, url } = event.detail.visit;
        const leaves =
            method === 'get' && url.pathname !== window.location.pathname;

        if (leaves && anyDirty() && !window.confirm(question)) {
            event.preventDefault();
        }
    });
    window.addEventListener('beforeunload', warnOnUnload);

    return () => {
        stopRouter();
        window.removeEventListener('beforeunload', warnOnUnload);
    };
}

/**
 * Asks before the couple leaves a page with unsaved text, however many
 * forms it holds. Saving, reordering and other visits that stay on the
 * page pass without a question.
 */
export function useUnsavedChanges(isDirty: () => boolean, question: string) {
    onMounted(() => {
        forms.add(isDirty);
        stopGuard ??= startGuard(question);
    });

    onBeforeUnmount(() => {
        forms.delete(isDirty);

        if (forms.size === 0) {
            stopGuard?.();
            stopGuard = null;
        }
    });
}
