<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { adminApi } from '../infrastructure/adminApi'
import { useSession } from '../../auth/presentation/session'
import { ApiError } from '../../../shared/infrastructure/http'
const router = useRouter(), route = useRoute(), session = useSession()
const editing = computed(() => route.params.id !== undefined)
const name = ref(''), email = ref(''), password = ref(''), saving = ref(false), loading = ref(false), error = ref(''), details = ref<Record<string, string[]>>({})
async function load() { if (!editing.value || !session.token) return; loading.value = true; try { const user = (await adminApi.user(Number(route.params.id), session.token)).data; name.value = user.name; email.value = user.email } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudo cargar el usuario.' } finally { loading.value = false } }
async function save() { if (!session.token || saving.value) return; saving.value = true; error.value = ''; details.value = {}; try { if (editing.value) await adminApi.updateUser(Number(route.params.id), { name: name.value.trim(), email: email.value.trim(), ...(password.value ? { password: password.value } : {}) }, session.token); else await adminApi.createUser({ name: name.value.trim(), email: email.value.trim(), password: password.value }, session.token); await router.push('/users') } catch (cause) { if (cause instanceof ApiError) { error.value = cause.message; details.value = cause.details } else error.value = 'No se pudo guardar el usuario.' } finally { saving.value = false } }
onMounted(load)
</script>
<template>
  <div class="page-heading"><div><span class="eyebrow">ADMINISTRACIÓN / USUARIOS</span><h1>{{ editing ? 'Editar usuario' : 'Nuevo usuario' }}</h1><p>{{ editing ? 'Actualiza los datos de la cuenta.' : 'Registra una cuenta para el panel.' }}</p></div></div>
  <form class="form-card" @submit.prevent="save"><v-alert v-if="error" type="error" variant="tonal" class="mb-5">{{ error }}</v-alert><v-progress-linear v-if="loading" indeterminate class="mb-5" /><v-text-field v-model="name" label="Nombre" autocomplete="name" :error-messages="details.name" required maxlength="255" /><v-text-field v-model="email" label="Correo electrónico" type="email" autocomplete="email" :error-messages="details.email" required /><v-text-field v-model="password" :label="editing ? 'Nueva contraseña (opcional)' : 'Contraseña'" type="password" autocomplete="new-password" :error-messages="details.password" :required="!editing" :minlength="password ? 8 : undefined" /><div class="d-flex ga-3"><v-btn type="submit" color="primary" :loading="saving" :disabled="loading">{{ editing ? 'Guardar cambios' : 'Crear usuario' }}</v-btn><v-btn to="/users" variant="text">Cancelar</v-btn></div></form>
</template>
