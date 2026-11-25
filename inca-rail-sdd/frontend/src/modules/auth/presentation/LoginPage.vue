<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { login } from '../application/login'
import { httpAuthPort } from '../infrastructure/httpAuthPort'
import { setSession } from './session'
import { ApiError } from '../../../shared/infrastructure/http'

const router = useRouter()
const email = ref('')
const password = ref('')
const loading = ref(false)
const error = ref('')

async function submit() {
  error.value = ''
  if (!email.value.trim() || !password.value) {
    error.value = 'Escribe tu correo y contraseña.'
    return
  }
  loading.value = true
  try {
    const result = await login(httpAuthPort, email.value, password.value)
    setSession(result.token, result.profile)
    await router.push('/competitions')
  } catch (cause) {
    error.value = cause instanceof ApiError && cause.status === 401
      ? 'Correo o contraseña incorrectos.'
      : cause instanceof Error ? cause.message : 'No se pudo iniciar sesión.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <main class="login-screen">
    <section class="login-story" aria-hidden="true">
      <div class="login-story__top"><span class="brand-emblem">IR</span><span>INCA RAIL<br />FOOTBALL DESK</span></div>
      <div class="login-story__content">
        <span class="eyebrow">DATOS QUE CONECTAN</span>
        <h1>El fútbol,<br /><em>más cerca.</em></h1>
        <p>Competiciones, equipos y jugadores en una experiencia pensada para explorar cada detalle.</p>
        <div class="login-story__rule"></div>
        <span class="login-story__note">UNA NUEVA FORMA DE EXPLORAR EL JUEGO</span>
      </div>
      <div class="login-story__decoration">01 — 90</div>
    </section>

    <section class="login-form-wrap">
      <div class="login-form">
        <span class="eyebrow">BIENVENIDO DE NUEVO</span>
        <h2>Ingresa a tu cuenta</h2>
        <p>Usa tus credenciales para continuar al panel de fútbol.</p>
        <v-alert v-if="error" type="error" variant="tonal" class="mb-5" role="alert">{{ error }}</v-alert>
        <v-form @submit.prevent="submit">
          <label class="field-label" for="email">CORREO ELECTRÓNICO</label>
          <v-text-field id="email" v-model="email" type="email" autocomplete="username" placeholder="tu@correo.com" variant="outlined" density="comfortable" class="mb-3" hide-details />
          <label class="field-label" for="password">CONTRASEÑA</label>
          <v-text-field id="password" v-model="password" type="password" autocomplete="current-password" placeholder="Tu contraseña" variant="outlined" density="comfortable" class="mb-6" hide-details />
          <v-btn type="submit" color="primary" size="large" block :loading="loading" class="login-submit">Entrar al panel</v-btn>
        </v-form>
        <div class="login-form__footer">INCA RAIL · PANEL DE FÚTBOL</div>
      </div>
    </section>
  </main>
</template>
