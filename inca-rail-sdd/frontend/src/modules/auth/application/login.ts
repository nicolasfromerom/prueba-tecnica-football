import type { AuthPort, Profile } from '../domain/Auth'

export async function login(
  port: AuthPort,
  email: string,
  password: string,
): Promise<{ token: string; profile: Profile }> {
  const result = await port.login(email.trim(), password)
  const profile = await port.profile(result.token)
  return { token: result.token, profile }
}
