import { useDebounceFn } from '@vueuse/core';
import { ref } from 'vue';
import { search } from '@/routes/addresses';
import type { SwissAddress } from '@/types/setup';

/**
 * Debounced Swiss address lookup through our own endpoint (ADR 0010).
 * Older requests are cancelled, so results never arrive out of order.
 */
export function useAddressSearch() {
    const results = ref<SwissAddress[]>([]);
    const loading = ref(false);
    let controller: AbortController | null = null;

    const run = useDebounceFn(async (query: string) => {
        controller?.abort();

        if (query.trim().length < 3) {
            results.value = [];
            loading.value = false;

            return;
        }

        controller = new AbortController();

        try {
            const response = await fetch(search.url({ query: { q: query } }), {
                headers: { Accept: 'application/json' },
                signal: controller.signal,
            });
            const body = (await response.json()) as {
                results?: SwissAddress[];
            };
            results.value = body.results ?? [];
        } catch (error) {
            if ((error as Error).name !== 'AbortError') {
                results.value = [];
            }
        } finally {
            loading.value = false;
        }
    }, 250);

    function lookup(query: string): void {
        loading.value = query.trim().length >= 3;
        void run(query);
    }

    return { results, loading, lookup };
}
