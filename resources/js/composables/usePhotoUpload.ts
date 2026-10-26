import { useHttp } from '@inertiajs/vue3';
import { ref } from 'vue';

/**
 * Sends a guest's photos one at a time, so a weak connection at the venue
 * loses one photo and not the whole selection. Photos that did not arrive
 * or were refused are counted as failed and kept for another try.
 */
export function usePhotoUpload(url: string) {
    const http = useHttp<{ photo: File | null }>({ photo: null });
    const sending = ref(false);
    const total = ref(0);
    const done = ref(0);
    const sent = ref(0);
    const failed = ref<File[]>([]);

    async function send(files: File[]): Promise<void> {
        if (sending.value || files.length === 0) {
            return;
        }

        sending.value = true;
        total.value = files.length;
        done.value = 0;
        failed.value = [];

        for (const file of files) {
            http.photo = file;

            try {
                // A refused photo (not an image, too large) answers 422,
                // which leaves errors on the form instead of throwing.
                await http.post(url);

                if (http.hasErrors) {
                    http.clearErrors();
                    failed.value.push(file);
                } else {
                    sent.value++;
                }
            } catch {
                failed.value.push(file);
            }

            done.value++;
        }

        http.photo = null;
        sending.value = false;
    }

    return {
        sending,
        total,
        done,
        sent,
        failed,
        send,
        retry: () => send([...failed.value]),
    };
}
