<script setup lang="ts">
import { ref } from 'vue'
import { adminApi } from '../infrastructure/adminApi'
import { useSession } from '../../auth/presentation/session'
import { ApiError } from '../../../shared/infrastructure/http'
const props = defineProps<{ id: number; active: boolean }>()
const emit = defineEmits<{ changed: [active: boolean] }>()
const session = useSession(), confirming = ref(false), saving = ref(false), error = ref('')
async function change() { if (!session.token) return; saving.value = true; error.value = ''; try { const next = !props.active; await adminApi.setStatus(props.id, next, session.token); emit('changed', next); confirming.value = false } catch (cause) { error.value = cause instanceof ApiError ? cause.message : 'No se pudo cambiar el estado.' } finally { saving.value = false } }
</script>
<template>
  <div class="detail-card mt-5"><h2>Estado de la cuenta</h2><p>{{ active ? 'La cuenta está activa.' : 'La cuenta está desactivada.' }}</p><v-alert v-if="error" type="error" variant="tonal" class="mb-4">{{ error }}</v-alert><v-btn v-if="!confirming" :color="active ? 'warning' : 'success'" variant="tonal" @click="confirming = true">{{ active ? 'Desactivar' : 'Activar' }}</v-btn><div v-else><p>¿Confirmas {{ active ? 'desactivar' : 'activar' }} esta cuenta?</p><div class="d-flex ga-3"><v-btn :color="active ? 'warning' : 'success'" :loading="saving" @click="change">Confirmar</v-btn><v-btn variant="text" @click="confirming = false">Cancelar</v-btn></div></div></div>
</template>
