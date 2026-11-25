<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { footballApi } from '../infrastructure/footballApi'
import type { Competition } from '../domain/Competition'
import { useSession } from '../../auth/presentation/session'
import StatePanel from '../../../shared/presentation/StatePanel.vue'
import { ApiError } from '../../../shared/infrastructure/http'

const session = useSession()
const items = ref<Competition[]>([])
const loading = ref(false)
const error = ref('')
const stale = ref(false)
async function load() {
  if (!session.token) return
  loading.value = true; error.value = ''
  try { const result = await footballApi.competitions(session.token); items.value = result.data; stale.value = result.meta.stale === true }
  catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudo cargar el catálogo.' }
  finally { loading.value = false }
}
onMounted(load)
</script>

<template>
  <div class="page-heading"><div><span class="eyebrow">FÚTBOL / 01</span><h1>Competiciones</h1><p>Explora las ligas disponibles y sus equipos.</p></div><v-btn color="primary" variant="tonal" :loading="loading" @click="load">Actualizar</v-btn></div>
  <v-alert v-if="stale" type="warning" variant="tonal" class="mb-5">Mostramos datos guardados. La fuente externa no respondió.</v-alert>
  <StatePanel v-if="loading" kind="loading" title="Cargando competiciones" message="Consultando el catálogo." />
  <StatePanel v-else-if="error" kind="error" title="No se pudieron cargar las competiciones" :message="error" />
  <StatePanel v-else-if="!items.length" kind="empty" title="Sin competiciones" message="Todavía no hay competiciones disponibles." />
  <div v-else class="data-grid"><RouterLink v-for="item in items" :key="item.id" :to="`/competitions/${item.external_id}`" class="data-card"><img v-if="item.emblem" :src="item.emblem" :alt="item.name" class="entity-logo" /><span class="eyebrow">{{ item.code || 'COMPETICIÓN' }}</span><h2>{{ item.name }}</h2><span>Ver equipos →</span></RouterLink></div>
</template>
