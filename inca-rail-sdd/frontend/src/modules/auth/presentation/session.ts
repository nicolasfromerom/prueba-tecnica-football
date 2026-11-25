import { reactive } from 'vue'
import type { Profile } from '../domain/Auth'

const session = reactive<{ token: string | null; profile: Profile | null; notice: string }>({
  token: null,
  profile: null,
  notice: '',
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
  session.notice = ''
}
