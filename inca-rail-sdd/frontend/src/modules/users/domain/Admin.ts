export interface Permission { id: number; action: string; subject: string }
export interface Role { id: number; name: string; permissions: Permission[] }
export interface User { id: number; name: string; email: string; is_active: boolean; roles: Role[] }
