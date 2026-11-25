export interface ApiEnvelope<T> {
  success: boolean
  message: string
  data: T
  meta: Record<string, unknown>
  error?: { code: string; details: Record<string, string[]> }
}

export class ApiError extends Error {
  readonly status: number
  readonly code: string
  readonly details: Record<string, string[]>
  readonly retryAfter: number | null

  constructor(
    status: number,
    code: string,
    message: string,
    details: Record<string, string[]> = {},
    retryAfter: number | null = null,
  ) {
    super(message)
    this.status = status
    this.code = code
    this.details = details
    this.retryAfter = retryAfter
  }
}

let unauthorizedHandler: (() => void) | null = null

export function onUnauthorized(handler: () => void): void {
  unauthorizedHandler = handler
}

export async function apiRequest<T>(
  path: string,
  options: RequestInit = {},
  token: string | null = null,
): Promise<ApiEnvelope<T>> {
  const baseUrl = import.meta.env.VITE_API_BASE_URL ?? '/api'
  let response: Response
  try {
    response = await fetch(`${baseUrl}${path}`, {
      ...options,
      headers: {
        Accept: 'application/json',
        ...(options.body ? { 'Content-Type': 'application/json' } : {}),
        ...(token ? { Authorization: `Bearer ${token}` } : {}),
        ...options.headers,
      },
    })
  } catch {
    throw new ApiError(0, 'NETWORK_ERROR', 'No se pudo conectar con el servidor.')
  }

  const body = (await response.json().catch(() => null)) as ApiEnvelope<T> | null
  if (response.status === 401 && path !== '/login') {
    unauthorizedHandler?.()
  }
  if (!response.ok || !body?.success) {
    throw new ApiError(
      response.status,
      body?.error?.code ?? 'HTTP_ERROR',
      body?.message ?? 'No se pudo completar la solicitud.',
      body?.error?.details ?? {},
      response.headers.get('Retry-After') ? Number(response.headers.get('Retry-After')) : null,
    )
  }
  return body
}
