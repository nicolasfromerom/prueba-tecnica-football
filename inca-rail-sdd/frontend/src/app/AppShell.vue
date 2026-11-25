<script setup lang="ts">
import { computed, ref } from 'vue'
import { useDisplay } from 'vuetify'
import { can } from '../modules/auth/application/can'
import { useSession } from '../modules/auth/presentation/session'

const { smAndDown } = useDisplay()
const drawer = ref(true)
const mobile = computed(() => smAndDown.value)
const session = useSession()

const sections = [
  { title: 'Competiciones', to: '/competitions', mark: '01', subject: 'Competition' },
  { title: 'Equipos', to: '/teams', mark: '02', subject: 'Team' },
  { title: 'Jugadores', to: '/players', mark: '03', subject: 'Player' },
  { title: 'Usuarios', to: '/users', mark: '04', subject: 'User' },
  { title: 'Roles', to: '/roles', mark: '05', subject: 'Role' },
  { title: 'Permisos', to: '/permissions', mark: '06', subject: 'Permission' },
]
const visibleSections = computed(() => sections.filter((item) => can(session.profile, 'read', item.subject)))
</script>

<template>
  <v-navigation-drawer
    v-model="drawer"
    :permanent="!mobile"
    :temporary="mobile"
    width="272"
    class="app-sidebar"
  >
    <div class="sidebar-brand">
      <span class="brand-emblem">IR</span>
      <span><strong>Inca Rail</strong><small>Football desk</small></span>
    </div>
    <div class="sidebar-caption">EXPLORAR</div>
    <v-list nav density="comfortable" class="sidebar-nav">
      <v-list-item v-for="item in visibleSections" :key="item.to" :to="item.to" rounded="lg">
        <template #prepend><span class="nav-mark">{{ item.mark }}</span></template>
        <v-list-item-title>{{ item.title }}</v-list-item-title>
      </v-list-item>
    </v-list>
    <div class="sidebar-foot">Datos de fútbol en un solo lugar.</div>
  </v-navigation-drawer>

  <v-app-bar flat class="app-topbar" height="70">
    <v-btn v-if="mobile" variant="text" aria-label="Abrir menú" @click="drawer = !drawer">☰</v-btn>
    <span class="topbar-context">INCA RAIL <span>/</span> PANEL DE FÚTBOL</span>
    <v-spacer />
    <span class="topbar-tag">TEMPORADA ACTUAL</span>
  </v-app-bar>

  <v-main class="app-main">
    <div class="page-container">
      <v-alert v-if="session.notice" type="warning" variant="tonal" closable class="mb-5" @click:close="session.notice = ''">{{ session.notice }}</v-alert>
      <RouterView />
    </div>
  </v-main>
</template>
