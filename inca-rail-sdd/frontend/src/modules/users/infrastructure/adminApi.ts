import { apiRequest } from '../../../shared/infrastructure/http'
import type { Permission, Role, User } from '../domain/Admin'
const json = (value: unknown) => JSON.stringify(value)
export const adminApi = {
  users: (token: string) => apiRequest<User[]>('/users', {}, token),
  user: (id: number, token: string) => apiRequest<User>(`/users/${id}`, {}, token),
  createUser: (value: { name: string; email: string; password: string }, token: string) => apiRequest<User>('/users', { method: 'POST', body: json(value) }, token),
  updateUser: (id: number, value: { name: string; email: string; password?: string }, token: string) => apiRequest<User>(`/users/${id}`, { method: 'PUT', body: json(value) }, token),
  setStatus: (id: number, is_active: boolean, token: string) => apiRequest<User>(`/users/${id}/status`, { method: 'PATCH', body: json({ is_active }) }, token),
  setRoles: (id: number, role_ids: number[], token: string) => apiRequest<User>(`/users/${id}/roles`, { method: 'PUT', body: json({ role_ids }) }, token),
  roles: (token: string) => apiRequest<Role[]>('/roles', {}, token),
  role: (id: number, token: string) => apiRequest<Role>(`/roles/${id}`, {}, token),
  createRole: (name: string, token: string) => apiRequest<Role>('/roles', { method: 'POST', body: json({ name }) }, token),
  updateRole: (id: number, name: string, token: string) => apiRequest<Role>(`/roles/${id}`, { method: 'PUT', body: json({ name }) }, token),
  permissions: (token: string) => apiRequest<Permission[]>('/permissions', {}, token),
  setPermissions: (id: number, permission_ids: number[], token: string) => apiRequest<Role>(`/roles/${id}/permissions`, { method: 'PUT', body: json({ permission_ids }) }, token),
}
