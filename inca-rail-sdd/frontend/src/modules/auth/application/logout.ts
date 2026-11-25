import type { AuthPort } from '../domain/Auth'

export async function logout(port: AuthPort, token: string): Promise<void> {
  await port.logout(token)
}
