<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { footballApi } from '../../competitions/infrastructure/footballApi'
import type { FootballTeam } from '../../competitions/domain/Competition'
import { useSession } from '../../auth/presentation/session'
import { ApiError } from '../../../shared/infrastructure/http'
import StatePanel from '../../../shared/presentation/StatePanel.vue'
const session = useSession(), items = ref<FootballTeam[]>([]), loading = ref(false), error = ref('')
async function load() { if (!session.token) return; loading.value = true; error.value = ''; try { items.value = (await footballApi.teams(session.token)).data } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudieron cargar los equipos.' } finally { loading.value = false } }
onMounted(load)
</script>
<template>
  <div class="page-heading"><div><span class="eyebrow">FÚTBOL / 02</span><h1>Equipos</h1><p>Equipos guardados de las competiciones importadas.</p></div><v-btn color="primary" variant="tonal" :loading="loading" @click="load">Actualizar</v-btn></div>
  <StatePanel v-if="loading" kind="loading" title="Cargando equipos" message="Consultando datos locales." />
  <StatePanel v-else-if="error" kind="error" title="No se pudieron cargar los equipos" :message="error" />
  <StatePanel v-else-if="!items.length" kind="empty" title="Sin equipos" message="Importa una competición para ver sus equipos." />
  <div v-else class="data-grid"><RouterLink v-for="item in items" :key="item.id" :to="`/teams/${item.external_id}`" class="data-card"><img v-if="item.crest" :src="item.crest" :alt="item.name" class="entity-logo" /><span class="eyebrow">{{ item.short_name || 'EQUIPO' }}</span><h2>{{ item.name }}</h2><span>Ver plantilla →</span></RouterLink></div>
</template>
