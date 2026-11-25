<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { adminApi } from '../../users/infrastructure/adminApi'
import type { Role } from '../../users/domain/Admin'
import { useSession } from '../../auth/presentation/session'
import { can } from '../../auth/application/can'
import { ApiError } from '../../../shared/infrastructure/http'
import StatePanel from '../../../shared/presentation/StatePanel.vue'
const session = useSession(), items = ref<Role[]>([]), loading = ref(false), error = ref('')
async function load() { if (!session.token) return; loading.value = true; error.value = ''; try { items.value = (await adminApi.roles(session.token)).data } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudieron cargar los roles.' } finally { loading.value = false } }
onMounted(load)
</script>
<template>
  <div class="page-heading"><div><span class="eyebrow">ADMINISTRACIÓN / 02</span><h1>Roles</h1><p>Grupos y capacidades del panel.</p></div><v-btn v-if="can(session.profile, 'create', 'Role')" color="primary" to="/roles/new">Nuevo rol</v-btn></div>
  <StatePanel v-if="loading" kind="loading" title="Cargando roles" message="Consultando datos." />
  <StatePanel v-else-if="error" kind="error" title="No se pudieron cargar los roles" :message="error" />
  <StatePanel v-else-if="!items.length" kind="empty" title="Sin roles" message="No hay roles registrados." />
  <div v-else class="data-table"><v-table><thead><tr><th>Rol</th><th>Permisos</th><th></th></tr></thead><tbody><tr v-for="item in items" :key="item.id"><td>{{ item.name }}</td><td>{{ item.permissions?.length ?? 0 }}</td><td><RouterLink v-if="can(session.profile, 'update', 'Role') || can(session.profile, 'assign', 'RolePermission')" :to="`/roles/${item.id}/edit`">Administrar</RouterLink></td></tr></tbody></v-table></div>
</template>
