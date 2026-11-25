<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { adminApi } from '../../users/infrastructure/adminApi'
import { useSession } from '../../auth/presentation/session'
import { ApiError } from '../../../shared/infrastructure/http'
const router = useRouter(), route = useRoute(), session = useSession(), name = ref(''), saving = ref(false), loading = ref(false), error = ref(''), details = ref<Record<string, string[]>>({})
const editing = computed(() => route.params.id !== undefined)
async function load() { if (!editing.value || !session.token) return; loading.value = true; try { name.value = (await adminApi.role(Number(route.params.id), session.token)).data.name } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudo cargar el rol.' } finally { loading.value = false } }
async function save() { if (!session.token) return; saving.value = true; error.value = ''; details.value = {}; try { if (editing.value) await adminApi.updateRole(Number(route.params.id), name.value.trim(), session.token); else await adminApi.createRole(name.value.trim(), session.token); await router.push('/roles') } catch (cause) { if (cause instanceof ApiError) { error.value = cause.message; details.value = cause.details } else error.value = 'No se pudo guardar el rol.' } finally { saving.value = false } }
onMounted(load)
</script>
<template>
  <div class="page-heading"><div><span class="eyebrow">ADMINISTRACIÓN / ROLES</span><h1>{{ editing ? 'Editar rol' : 'Nuevo rol' }}</h1><p>Grupo de capacidades.</p></div></div>
  <form class="form-card" @submit.prevent="save"><v-alert v-if="error" type="error" variant="tonal" class="mb-5">{{ error }}</v-alert><v-progress-linear v-if="loading" indeterminate class="mb-5" /><v-text-field v-model="name" label="Nombre del rol" :error-messages="details.name" required maxlength="100" /><div class="d-flex ga-3"><v-btn type="submit" color="primary" :loading="saving" :disabled="loading">{{ editing ? 'Guardar cambios' : 'Crear rol' }}</v-btn><v-btn to="/roles" variant="text">Cancelar</v-btn></div></form>
</template>
