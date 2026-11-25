<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { footballApi } from '../../competitions/infrastructure/footballApi'
import type { FootballPlayer } from '../../competitions/domain/Competition'
import { useSession } from '../../auth/presentation/session'
import { ApiError } from '../../../shared/infrastructure/http'
import StatePanel from '../../../shared/presentation/StatePanel.vue'
const session = useSession(), items = ref<FootballPlayer[]>([]), loading = ref(false), error = ref('')
async function load() { if (!session.token) return; loading.value = true; error.value = ''; try { items.value = (await footballApi.players(session.token)).data } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudieron cargar los jugadores.' } finally { loading.value = false } }
onMounted(load)
</script>
<template>
  <div class="page-heading"><div><span class="eyebrow">FÚTBOL / 03</span><h1>Jugadores</h1><p>Plantilla de los equipos importados.</p></div><v-btn variant="tonal" :loading="loading" @click="load">Actualizar</v-btn></div>
  <StatePanel v-if="loading" kind="loading" title="Cargando jugadores" message="Consultando datos locales." />
  <StatePanel v-else-if="error" kind="error" title="No se pudieron cargar los jugadores" :message="error" />
  <StatePanel v-else-if="!items.length" kind="empty" title="Sin jugadores" message="Importa una competición para ver jugadores." />
  <div v-else class="data-table"><v-table><thead><tr><th>Jugador</th><th>Equipo</th><th>Posición</th><th>Camiseta</th></tr></thead><tbody><tr v-for="item in items" :key="item.id"><td><RouterLink :to="`/players/${item.external_id}`">{{ item.name }}</RouterLink></td><td>{{ item.team?.name || 'Sin información' }}</td><td>{{ item.position || 'Sin información' }}</td><td>{{ item.shirt_number ?? 'Sin información' }}</td></tr></tbody></v-table></div>
</template>
