<script setup lang="ts">
import { useRoute } from 'vue-router'
import AppShell from './app/AppShell.vue'

const route = useRoute()
</script>

<template>
  <v-app>
    <RouterView v-if="route.path === '/login'" />
    <AppShell v-else />
  </v-app>
</template>
