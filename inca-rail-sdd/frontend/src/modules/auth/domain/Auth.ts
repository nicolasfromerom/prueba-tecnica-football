export interface Rule {
  action: string
  subject: string
}

export interface Profile {
  id: number
  name: string
  email: string
  is_active: boolean
  roles: string[]
  rules: Rule[]
}

export interface LoginResult {
  token: string
  token_type: 'Bearer'
}

export interface AuthPort {
  login(email: string, password: string): Promise<LoginResult>
  profile(token: string): Promise<Profile>
  logout(token: string): Promise<void>
}
