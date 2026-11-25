import type { Profile } from '../domain/Auth'

export function can(profile: Profile | null, action: string, subject: string): boolean {
  return profile?.rules.some((rule) => rule.action === action && rule.subject === subject) ?? false
}
