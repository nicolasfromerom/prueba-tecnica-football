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
