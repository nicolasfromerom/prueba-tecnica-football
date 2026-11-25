<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { adminApi } from '../../users/infrastructure/adminApi'
import { useSession } from '../../auth/presentation/session'
import { ApiError } from '../../../shared/infrastructure/http'
const router = useRouter(), session = useSession(), name = ref(''), saving = ref(false), error = ref(''), details = ref<Record<string, string[]>>({})
async function save() { if (!session.token) return; saving.value = true; error.value = ''; details.value = {}; try { await adminApi.createRole(name.value.trim(), session.token); await router.push('/roles') } catch (cause) { if (cause instanceof ApiError) { error.value = cause.message; details.value = cause.details } else error.value = 'No se pudo crear el rol.' } finally { saving.value = false } }
</script>
<template>
  <div class="page-heading"><div><span class="eyebrow">ADMINISTRACIÓN / ROLES</span><h1>Nuevo rol</h1><p>Crea un grupo de capacidades.</p></div></div>
  <form class="form-card" @submit.prevent="save"><v-alert v-if="error" type="error" variant="tonal" class="mb-5">{{ error }}</v-alert><v-text-field v-model="name" label="Nombre del rol" :error-messages="details.name" required maxlength="100" /><div class="d-flex ga-3"><v-btn type="submit" color="primary" :loading="saving">Crear rol</v-btn><v-btn to="/roles" variant="text">Cancelar</v-btn></div></form>
</template>
