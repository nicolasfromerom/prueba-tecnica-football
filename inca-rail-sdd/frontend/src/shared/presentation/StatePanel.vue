<script setup lang="ts">
defineProps<{
  kind: 'loading' | 'empty' | 'error'
  title?: string
  message?: string
}>()
</script>

<template>
  <div class="state-panel" role="status">
    <v-progress-circular v-if="kind === 'loading'" indeterminate color="primary" size="34" />
    <span v-else class="state-panel__symbol" aria-hidden="true">{{ kind === 'error' ? '!' : '—' }}</span>
    <h3>{{ title ?? (kind === 'loading' ? 'Cargando información' : kind === 'error' ? 'No pudimos cargar estos datos' : 'Todavía no hay datos') }}</h3>
    <p v-if="message">{{ message }}</p>
    <slot />
  </div>
</template>
