<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { footballApi } from '../../competitions/infrastructure/footballApi'
import type { FootballPlayer } from '../../competitions/domain/Competition'
import { useSession } from '../../auth/presentation/session'
import { ApiError } from '../../../shared/infrastructure/http'
import StatePanel from '../../../shared/presentation/StatePanel.vue'
const route = useRoute(), session = useSession(), item = ref<FootballPlayer | null>(null), loading = ref(false), error = ref('')
async function load() { if (!session.token) return; loading.value = true; error.value = ''; try { item.value = (await footballApi.player(Number(route.params.id), session.token)).data } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudo cargar el jugador.' } finally { loading.value = false } }
onMounted(load)
</script>
<template>
  <div class="page-heading"><div><span class="eyebrow">FÚTBOL / JUGADOR</span><h1>{{ item?.name || 'Jugador' }}</h1><p>Ficha del jugador.</p></div><v-btn variant="tonal" :loading="loading" @click="load">Actualizar</v-btn></div>
  <StatePanel v-if="loading" kind="loading" title="Cargando jugador" message="Consultando datos locales." />
  <StatePanel v-else-if="error" kind="error" title="No se pudo cargar el jugador" :message="error" />
  <div v-else-if="item" class="detail-card"><dl class="detail-list"><div><dt>Equipo</dt><dd><RouterLink v-if="item.team" :to="`/teams/${item.team.external_id}`">{{ item.team.name }}</RouterLink><span v-else>Sin información</span></dd></div><div><dt>Posición</dt><dd>{{ item.position || 'Sin información' }}</dd></div><div><dt>Camiseta</dt><dd>{{ item.shirt_number ?? 'Sin información' }}</dd></div><div><dt>Nacionalidad</dt><dd>{{ item.nationality || 'Sin información' }}</dd></div><div><dt>Fecha de nacimiento</dt><dd>{{ item.date_of_birth || 'Sin información' }}</dd></div></dl></div>
</template>
