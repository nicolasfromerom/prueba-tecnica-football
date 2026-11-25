<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { adminApi } from '../../users/infrastructure/adminApi'
import type { Permission } from '../../users/domain/Admin'
import { useSession } from '../../auth/presentation/session'
import { ApiError } from '../../../shared/infrastructure/http'
const props = defineProps<{ id: number }>()
const session = useSession(), items = ref<Permission[]>([]), selected = ref<number[]>([]), loading = ref(false), saving = ref(false), error = ref(''), success = ref(''), protectedRole = ref(false)
async function load() { if (!session.token) return; loading.value = true; error.value = ''; try { const [catalog, role] = await Promise.all([adminApi.permissions(session.token), adminApi.role(props.id, session.token)]); items.value = catalog.data; selected.value = role.data.permissions.map(item => item.id); protectedRole.value = role.data.name === 'admin' } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudieron cargar los permisos.' } finally { loading.value = false } }
async function save() { if (!session.token) return; saving.value = true; error.value = ''; success.value = ''; try { await adminApi.setPermissions(props.id, selected.value, session.token); success.value = 'Permisos actualizados.' } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudieron asignar los permisos.' } finally { saving.value = false } }
onMounted(load)
</script>
<template>
  <div class="detail-card mt-5"><h2>Permisos del rol</h2><p v-if="protectedRole">Los permisos del rol administrador son fijos.</p><p v-else>Selecciona las acciones disponibles para este rol.</p><v-alert v-if="error" type="error" variant="tonal" class="mb-4">{{ error }}</v-alert><v-alert v-if="success" type="success" variant="tonal" class="mb-4">{{ success }}</v-alert><v-progress-linear v-if="loading" indeterminate /><template v-else><v-checkbox v-for="item in items" :key="item.id" v-model="selected" :value="item.id" :label="`${item.subject} · ${item.action}`" :disabled="protectedRole" hide-details /><v-btn v-if="!protectedRole" color="primary" class="mt-4" :loading="saving" @click="save">Guardar permisos</v-btn></template></div>
</template>
