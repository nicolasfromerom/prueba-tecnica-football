import { apiRequest } from '../../../shared/infrastructure/http'
import type { Competition, FootballPlayer, FootballTeam } from '../domain/Competition'

export const footballApi = {
  competitions: (token: string) => apiRequest<Competition[]>('/competitions', {}, token),
  competition: (id: number, token: string) => apiRequest<Competition>(`/competitions/${id}`, {}, token),
  teams: (token: string) => apiRequest<FootballTeam[]>('/team', {}, token),
  team: (id: number, token: string) => apiRequest<FootballTeam>(`/team/${id}`, {}, token),
  players: (token: string) => apiRequest<FootballPlayer[]>('/players', {}, token),
  player: (id: number, token: string) => apiRequest<FootballPlayer>(`/players/${id}`, {}, token),
}
