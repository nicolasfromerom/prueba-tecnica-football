<script setup lang="ts">
import { computed, ref } from 'vue'
import { useDisplay } from 'vuetify'

const { smAndDown } = useDisplay()
const drawer = ref(true)
const mobile = computed(() => smAndDown.value)

const sections = [
  { title: 'Competiciones', to: '/competitions', mark: '01' },
  { title: 'Equipos', to: '/teams', mark: '02' },
  { title: 'Jugadores', to: '/players', mark: '03' },
  { title: 'Usuarios', to: '/users', mark: '04' },
  { title: 'Roles', to: '/roles', mark: '05' },
  { title: 'Permisos', to: '/permissions', mark: '06' },
]
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
      <v-list-item v-for="item in sections" :key="item.to" :to="item.to" rounded="lg">
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
    <div class="page-container"><RouterView /></div>
  </v-main>
</template>
