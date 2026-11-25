<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { footballApi } from '../../competitions/infrastructure/footballApi'
import type { FootballTeam } from '../../competitions/domain/Competition'
import { useSession } from '../../auth/presentation/session'
import { ApiError } from '../../../shared/infrastructure/http'
import StatePanel from '../../../shared/presentation/StatePanel.vue'
const route = useRoute(), session = useSession(), item = ref<FootballTeam | null>(null), loading = ref(false), error = ref('')
async function load() { if (!session.token) return; loading.value = true; error.value = ''; try { item.value = (await footballApi.team(Number(route.params.id), session.token)).data } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudo cargar el equipo.' } finally { loading.value = false } }
onMounted(load)
</script>
<template>
  <div class="page-heading"><div><span class="eyebrow">FÚTBOL / EQUIPO</span><h1>{{ item?.name || 'Equipo' }}</h1><p>Detalle y plantilla del equipo.</p></div><v-btn variant="tonal" :loading="loading" @click="load">Actualizar</v-btn></div>
  <StatePanel v-if="loading" kind="loading" title="Cargando equipo" message="Consultando datos locales." />
  <StatePanel v-else-if="error" kind="error" title="No se pudo cargar el equipo" :message="error" />
  <template v-else-if="item"><div class="detail-card mb-5"><img v-if="item.crest" :src="item.crest" :alt="item.name" class="entity-logo" /><dl class="detail-list"><div><dt>Nombre corto</dt><dd>{{ item.short_name || 'Sin información' }}</dd></div><div><dt>Jugadores</dt><dd>{{ item.players?.length ?? 0 }}</dd></div></dl></div><div v-if="item.players?.length" class="data-table"><v-table><thead><tr><th>Jugador</th><th>Posición</th><th>Camiseta</th></tr></thead><tbody><tr v-for="player in item.players" :key="player.id"><td><RouterLink :to="`/players/${player.external_id}`">{{ player.name }}</RouterLink></td><td>{{ player.position || 'Sin información' }}</td><td>{{ player.shirt_number ?? 'Sin información' }}</td></tr></tbody></v-table></div><StatePanel v-else kind="empty" title="Sin plantilla" message="Todavía no hay jugadores guardados para este equipo." /></template>
</template>
