<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { adminApi } from '../infrastructure/adminApi'
import type { User } from '../domain/Admin'
import { useSession } from '../../auth/presentation/session'
import { can } from '../../auth/application/can'
import { ApiError } from '../../../shared/infrastructure/http'
import StatePanel from '../../../shared/presentation/StatePanel.vue'
const session = useSession(), items = ref<User[]>([]), loading = ref(false), error = ref('')
async function load() { if (!session.token) return; loading.value = true; error.value = ''; try { items.value = (await adminApi.users(session.token)).data } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudieron cargar los usuarios.' } finally { loading.value = false } }
onMounted(load)
</script>
<template>
  <div class="page-heading"><div><span class="eyebrow">ADMINISTRACIÓN / 01</span><h1>Usuarios</h1><p>Acceso y estado de las cuentas.</p></div><v-btn v-if="can(session.profile, 'create', 'User')" color="primary" to="/users/new">Nuevo usuario</v-btn></div>
  <StatePanel v-if="loading" kind="loading" title="Cargando usuarios" message="Consultando cuentas." />
  <StatePanel v-else-if="error" kind="error" title="No se pudieron cargar los usuarios" :message="error" />
  <StatePanel v-else-if="!items.length" kind="empty" title="Sin usuarios" message="No hay usuarios registrados." />
  <div v-else class="data-table"><v-table><thead><tr><th>Nombre</th><th>Correo</th><th>Roles</th><th>Estado</th><th></th></tr></thead><tbody><tr v-for="item in items" :key="item.id"><td>{{ item.name }}</td><td>{{ item.email }}</td><td>{{ item.roles?.map(role => role.name).join(', ') || 'Sin roles' }}</td><td><v-chip :color="item.is_active ? 'success' : 'warning'" size="small">{{ item.is_active ? 'Activo' : 'Inactivo' }}</v-chip></td><td><RouterLink v-if="can(session.profile, 'update', 'User') || can(session.profile, 'assign', 'UserRole') || can(session.profile, 'status', 'User')" :to="`/users/${item.id}/edit`">Administrar</RouterLink></td></tr></tbody></v-table></div>
</template>
