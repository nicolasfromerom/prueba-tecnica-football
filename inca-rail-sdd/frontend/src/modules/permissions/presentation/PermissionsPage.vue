<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { adminApi } from '../../users/infrastructure/adminApi'
import type { Permission } from '../../users/domain/Admin'
import { useSession } from '../../auth/presentation/session'
import { ApiError } from '../../../shared/infrastructure/http'
import StatePanel from '../../../shared/presentation/StatePanel.vue'
const session = useSession(), items = ref<Permission[]>([]), loading = ref(false), error = ref('')
async function load() { if (!session.token) return; loading.value = true; error.value = ''; try { items.value = (await adminApi.permissions(session.token)).data } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudieron cargar los permisos.' } finally { loading.value = false } }
onMounted(load)
</script>
<template>
  <div class="page-heading"><div><span class="eyebrow">ADMINISTRACIÓN / 03</span><h1>Permisos</h1><p>Catálogo de acciones y recursos.</p></div><v-btn variant="tonal" :loading="loading" @click="load">Actualizar</v-btn></div>
  <StatePanel v-if="loading" kind="loading" title="Cargando permisos" message="Consultando catálogo." />
  <StatePanel v-else-if="error" kind="error" title="No se pudieron cargar los permisos" :message="error" />
  <StatePanel v-else-if="!items.length" kind="empty" title="Sin permisos" message="No hay permisos registrados." />
  <div v-else class="data-table"><v-table><thead><tr><th>Recurso</th><th>Acción</th></tr></thead><tbody><tr v-for="item in items" :key="item.id"><td>{{ item.subject }}</td><td>{{ item.action }}</td></tr></tbody></v-table></div>
</template>
