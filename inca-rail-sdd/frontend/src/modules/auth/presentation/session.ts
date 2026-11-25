import { reactive } from 'vue'
import type { Profile } from '../domain/Auth'

const session = reactive<{ token: string | null; profile: Profile | null }>({
  token: null,
  profile: null,
})

export function useSession() {
  return session
}

export function setSession(token: string, profile: Profile): void {
  session.token = token
  session.profile = profile
}

export function clearSession(): void {
  session.token = null
  session.profile = null
}
