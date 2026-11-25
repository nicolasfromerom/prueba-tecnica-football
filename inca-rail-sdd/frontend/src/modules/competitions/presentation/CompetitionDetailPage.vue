<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { footballApi } from '../infrastructure/footballApi'
import type { Competition } from '../domain/Competition'
import { useSession } from '../../auth/presentation/session'
import { can } from '../../auth/application/can'
import { ApiError } from '../../../shared/infrastructure/http'
import StatePanel from '../../../shared/presentation/StatePanel.vue'

const route = useRoute(), session = useSession()
const item = ref<Competition | null>(null), loading = ref(false), stale = ref(false), error = ref('')
async function load() {
  if (!session.token) return
  loading.value = true; error.value = ''
  try { const result = await footballApi.competition(Number(route.params.id), session.token); item.value = result.data; stale.value = result.meta.stale === true }
  catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudo cargar la competición.' }
  finally { loading.value = false }
}
onMounted(load)
</script>
<template>
  <div class="page-heading"><div><span class="eyebrow">FÚTBOL / COMPETICIÓN</span><h1>{{ item?.name || 'Competición' }}</h1><p>Equipos y plantilla guardados en el catálogo.</p></div><v-btn v-if="can(session.profile, 'import', 'Competition')" color="primary" :loading="loading" @click="load">Importar / actualizar</v-btn></div>
  <v-alert v-if="stale" type="warning" variant="tonal" class="mb-5">Datos guardados. La información puede estar desactualizada.</v-alert>
  <StatePanel v-if="loading" kind="loading" title="Cargando competición" message="Consultando datos." />
  <StatePanel v-else-if="error" kind="error" title="No se pudo cargar" :message="error" />
  <template v-else-if="item"><div class="detail-card mb-5"><img v-if="item.emblem" :src="item.emblem" :alt="item.name" class="entity-logo" /><dl class="detail-list"><div><dt>Código</dt><dd>{{ item.code || 'Sin información' }}</dd></div><div><dt>Última importación</dt><dd>{{ item.synced_at || 'Sin información' }}</dd></div><div><dt>Equipos</dt><dd>{{ item.teams?.length ?? 0 }}</dd></div></dl></div><div v-if="item.teams?.length" class="data-grid"><RouterLink v-for="team in item.teams" :key="team.id" :to="`/teams/${team.external_id}`" class="data-card"><img v-if="team.crest" :src="team.crest" :alt="team.name" class="entity-logo" /><h2>{{ team.name }}</h2><span>{{ team.players?.length ?? 0 }} jugadores · Ver equipo →</span></RouterLink></div><StatePanel v-else kind="empty" title="Sin equipos" message="Esta competición no tiene equipos guardados." /></template>
</template>
