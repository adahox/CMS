import type { ApiResponse } from '@/types/cms';

function csrfToken(): string {
    const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);

    return match ? decodeURIComponent(match[1]) : '';
}

let csrfReady = false;

async function ensureCsrf(): Promise<void> {
    if (csrfReady) {
        return;
    }

    await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
    csrfReady = true;
}

export async function apiRequest<T>(
    path: string,
    options: RequestInit = {},
): Promise<T> {
    const method = options.method ?? 'GET';

    if (method !== 'GET' && method !== 'HEAD') {
        await ensureCsrf();
    }

    const response = await fetch(`/api${path}`, {
        ...options,
        credentials: 'include',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'X-XSRF-TOKEN': csrfToken(),
            ...options.headers,
        },
    });

    const body = (await response.json()) as ApiResponse<T> & { message?: string };

    if (!response.ok || !body.status) {
        throw new Error(body.response ?? body.message ?? 'Erro na requisição.');
    }

    return body.data;
}
