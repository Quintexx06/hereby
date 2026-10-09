import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { HttpError, postForm } from '@/lib/http';
import { preview as previewRoute } from '@/routes/weddings/guests';
import { store } from '@/routes/weddings/households';
import type { ImportHousehold } from '@/types';

/**
 * Read → check → save. Reading never saves; the couple unticks what they
 * don't want, then everything ticked is saved in one request.
 */
export function useGuestImport(weddingId: number) {
    const households = ref<ImportHousehold[] | null>(null);
    const selected = ref<Set<number>>(new Set());
    const reading = ref(false);
    const saving = ref(false);
    const error = ref<string | null>(null);

    async function read(source: { text?: string; file?: File }): Promise<void> {
        const body = new FormData();
        if (source.file) {
            body.append('file', source.file);
        } else {
            body.append('text', source.text ?? '');
        }

        reading.value = true;
        error.value = null;

        try {
            const data = await postForm<{ households: ImportHousehold[] }>(
                previewRoute.url(weddingId),
                body,
            );
            households.value = data.households;
            // Everything new is ticked; likely duplicates start unticked.
            selected.value = new Set(
                data.households.flatMap((household, index) =>
                    household.guests.every((guest) => guest.duplicate)
                        ? []
                        : [index],
                ),
            );
        } catch (caught) {
            const failure = caught as HttpError;
            error.value =
                Object.values(failure.errors ?? {})[0]?.[0] ?? failure.message;
        } finally {
            reading.value = false;
        }
    }

    const chosen = computed(() =>
        (households.value ?? []).flatMap((household, index) =>
            selected.value.has(index) ? [household] : [],
        ),
    );

    function toggle(index: number): void {
        const next = new Set(selected.value);
        if (next.has(index)) {
            next.delete(index);
        } else {
            next.add(index);
        }
        selected.value = next;
    }

    function save(onSuccess: () => void): void {
        saving.value = true;
        router.post(
            store.url(weddingId),
            {
                households: chosen.value.map(({ guests, ...household }) => ({
                    ...household,
                    guests: guests.map((guest) => ({
                        first_name: guest.first_name,
                        last_name: guest.last_name,
                        is_child: guest.is_child,
                    })),
                })),
            },
            { onSuccess, onFinish: () => (saving.value = false) },
        );
    }

    function reset(): void {
        households.value = null;
        error.value = null;
    }

    return {
        households,
        selected,
        chosen,
        reading,
        saving,
        error,
        read,
        toggle,
        save,
        reset,
    };
}
