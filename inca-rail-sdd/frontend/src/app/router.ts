import { createRouter, createWebHistory } from 'vue-router'
import { can } from '../modules/auth/application/can'
import { clearSession, useSession } from '../modules/auth/presentation/session'
import { onForbidden, onUnauthorized } from '../shared/infrastructure/http'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/login' },
    { path: '/login', component: () => import('../modules/auth/presentation/LoginPage.vue') },
    { path: '/competitions', component: () => import('../modules/competitions/presentation/CompetitionsPage.vue'), meta: { permission: ['read', 'Competition'] } },
    { path: '/competitions/:id', component: () => import('../modules/competitions/presentation/CompetitionDetailPage.vue'), meta: { permission: ['read', 'Competition'] } },
    { path: '/teams', component: () => import('../modules/teams/presentation/TeamsPage.vue'), meta: { permission: ['read', 'Team'] } },
    { path: '/teams/:id', component: () => import('../modules/teams/presentation/TeamDetailPage.vue'), meta: { permission: ['read', 'Team'] } },
    { path: '/players', component: () => import('../modules/players/presentation/PlayersPage.vue'), meta: { permission: ['read', 'Player'] } },
    { path: '/players/:id', component: () => import('../modules/players/presentation/PlayerDetailPage.vue'), meta: { permission: ['read', 'Player'] } },
    { path: '/users', component: () => import('../modules/users/presentation/UsersPage.vue'), meta: { permission: ['read', 'User'] } },
    { path: '/users/new', component: () => import('../modules/users/presentation/UserFormPage.vue'), meta: { permission: ['create', 'User'] } },
    { path: '/users/:id/edit', component: () => import('../modules/users/presentation/UserFormPage.vue'), meta: { permission: ['read', 'User'] } },
    { path: '/roles', component: () => import('../modules/roles/presentation/RolesPage.vue'), meta: { permission: ['read', 'Role'] } },
    { path: '/roles/new', component: () => import('../modules/roles/presentation/RoleFormPage.vue'), meta: { permission: ['create', 'Role'] } },
    { path: '/forbidden', component: () => import('../modules/auth/presentation/ForbiddenPage.vue') },
  ],
})

router.beforeEach((to) => {
  const session = useSession()
  if (to.path === '/login') {
    return session.token ? '/competitions' : true
  }
  if (!session.token) {
    return '/login'
  }
  const permission = to.meta.permission as [string, string] | undefined
  if (permission && !can(session.profile, permission[0], permission[1])) {
    return '/forbidden'
  }
  return true
})

onUnauthorized(() => {
  clearSession()
  void router.push('/login')
})

onForbidden(() => {
  useSession().notice = 'No tienes permiso para realizar esta acción.'
})

export default router
