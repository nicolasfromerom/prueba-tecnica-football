<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { adminApi } from '../infrastructure/adminApi'
import { useSession } from '../../auth/presentation/session'
import { ApiError } from '../../../shared/infrastructure/http'
const router = useRouter(), session = useSession()
const name = ref(''), email = ref(''), password = ref(''), saving = ref(false), error = ref(''), details = ref<Record<string, string[]>>({})
async function save() { if (!session.token || saving.value) return; saving.value = true; error.value = ''; details.value = {}; try { await adminApi.createUser({ name: name.value.trim(), email: email.value.trim(), password: password.value }, session.token); await router.push('/users') } catch (cause) { if (cause instanceof ApiError) { error.value = cause.message; details.value = cause.details } else error.value = 'No se pudo crear el usuario.' } finally { saving.value = false } }
</script>
<template>
  <div class="page-heading"><div><span class="eyebrow">ADMINISTRACIÓN / USUARIOS</span><h1>Nuevo usuario</h1><p>Registra una cuenta para el panel.</p></div></div>
  <form class="form-card" @submit.prevent="save"><v-alert v-if="error" type="error" variant="tonal" class="mb-5">{{ error }}</v-alert><v-text-field v-model="name" label="Nombre" autocomplete="name" :error-messages="details.name" required maxlength="255" /><v-text-field v-model="email" label="Correo electrónico" type="email" autocomplete="email" :error-messages="details.email" required /><v-text-field v-model="password" label="Contraseña" type="password" autocomplete="new-password" :error-messages="details.password" required minlength="8" /><div class="d-flex ga-3"><v-btn type="submit" color="primary" :loading="saving">Crear usuario</v-btn><v-btn to="/users" variant="text">Cancelar</v-btn></div></form>
</template>
