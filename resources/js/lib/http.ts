/**
 * JSON requests outside Inertia visits (e.g. the import preview). Sends
 * Laravel's XSRF token from its cookie, like Inertia does.
 */
function xsrfToken(): string {
    const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);

    return match ? decodeURIComponent(match[1]) : '';
}

export class HttpError extends Error {
    constructor(
        message: string,
        public readonly errors: Record<string, string[]> = {},
    ) {
        super(message);
    }
}

export async function postForm<T>(url: string, body: FormData): Promise<T> {
    const response = await fetch(url, {
        method: 'POST',
        body,
        credentials: 'same-origin',
        headers: { Accept: 'application/json', 'X-XSRF-TOKEN': xsrfToken() },
    });
    const data = await response.json().catch(() => ({}));

    if (!response.ok) {
        throw new HttpError(
            data.message ??
                'Etwas ist schiefgelaufen. Versucht es noch einmal.',
            data.errors,
        );
    }

    return data as T;
}
