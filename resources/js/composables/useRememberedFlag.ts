import { onMounted, ref } from 'vue';

/**
 * A per-browser yes/no (a dismissed checklist, a tour already seen). Starts
 * false until mounted so SSR and private windows simply show the default.
 */
export function useRememberedFlag(key: string) {
    const isSet = ref(false);

    onMounted(() => {
        try {
            isSet.value = window.localStorage.getItem(key) === '1';
        } catch {
            isSet.value = false;
        }
    });

    function set(value: boolean): void {
        isSet.value = value;
        try {
            if (value) {
                window.localStorage.setItem(key, '1');
            } else {
                window.localStorage.removeItem(key);
            }
        } catch {
            /* Storage blocked: the flag lasts for this visit only. */
        }
    }

    return { isSet, set };
}
