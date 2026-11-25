import type { AuthPort, LoginResult, Profile } from '../domain/Auth'
import { apiRequest } from '../../../shared/infrastructure/http'

export const httpAuthPort: AuthPort = {
  async login(email, password) {
    const response = await apiRequest<LoginResult>('/login', {
      method: 'POST',
      body: JSON.stringify({ email, password }),
    })
    return response.data
  },
  async profile(token) {
    const response = await apiRequest<Profile>('/me', {}, token)
    return response.data
  },
  async logout(token) {
    await apiRequest<null>('/logout', { method: 'POST' }, token)
  },
}
