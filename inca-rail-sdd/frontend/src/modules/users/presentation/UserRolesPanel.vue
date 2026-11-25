<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { adminApi } from '../infrastructure/adminApi'
import type { Role } from '../domain/Admin'
import { useSession } from '../../auth/presentation/session'
import { ApiError } from '../../../shared/infrastructure/http'
import { httpAuthPort } from '../../auth/infrastructure/httpAuthPort'
const props = defineProps<{ id: number }>()
const session = useSession(), roles = ref<Role[]>([]), selected = ref<number[]>([]), loading = ref(false), saving = ref(false), error = ref(''), success = ref('')
async function load() { if (!session.token) return; loading.value = true; error.value = ''; try { const [all, user] = await Promise.all([adminApi.roles(session.token), adminApi.user(props.id, session.token)]); roles.value = all.data; selected.value = user.data.roles.map(role => role.id) } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudieron cargar los roles.' } finally { loading.value = false } }
async function save() { if (!session.token) return; saving.value = true; error.value = ''; success.value = ''; try { await adminApi.setRoles(props.id, selected.value, session.token); session.profile = await httpAuthPort.profile(session.token); success.value = 'Roles actualizados.' } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudieron asignar los roles.' } finally { saving.value = false } }
onMounted(load)
</script>
<template>
  <div class="detail-card mt-5"><h2>Roles</h2><p>Define las capacidades de esta cuenta.</p><v-alert v-if="error" type="error" variant="tonal" class="mb-4">{{ error }}</v-alert><v-alert v-if="success" type="success" variant="tonal" class="mb-4">{{ success }}</v-alert><v-progress-linear v-if="loading" indeterminate /><v-select v-else v-model="selected" :items="roles" item-title="name" item-value="id" label="Roles asignados" multiple chips /><v-btn color="primary" :disabled="loading" :loading="saving" @click="save">Guardar roles</v-btn></div>
</template>
