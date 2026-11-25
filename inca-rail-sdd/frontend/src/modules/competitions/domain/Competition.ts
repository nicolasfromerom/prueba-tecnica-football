export interface FootballPlayer { id: number; external_id: number; name: string; position: string | null; shirt_number: number | null; nationality: string | null; date_of_birth: string | null; team?: FootballTeam }
export interface FootballTeam { id: number; external_id: number; name: string; short_name: string | null; crest: string | null; players?: FootballPlayer[] }
export interface Competition { id: number; external_id: number; name: string; code: string | null; emblem: string | null; synced_at: string | null; teams?: FootballTeam[] }
