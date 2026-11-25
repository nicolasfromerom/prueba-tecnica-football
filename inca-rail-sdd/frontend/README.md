# Inca Rail Frontend

SPA Vue 3 con TypeScript, Vue Router y Vuetify. Desde la raíz del repositorio:

```sh
docker compose up -d --build frontend
docker compose exec -T frontend npm run build
```

La aplicación se abre en `http://127.0.0.1:5173`. Vite envía `/api` al servicio backend y elimina ese prefijo antes de llegar a Laravel. Las dependencias se instalan dentro del contenedor con `docker compose run --rm --no-deps frontend npm ci` cuando se crea un volumen nuevo.

El token de sesión permanece en memoria; al recargar la página hay que iniciar sesión de nuevo.
