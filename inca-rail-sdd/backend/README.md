# Inca Rail API

Backend Laravel 13 con PostgreSQL. La aplicación se ejecuta desde la raíz del repositorio con Docker Compose.

```sh
docker compose up -d db backend
docker compose run --rm backend php artisan migrate --force
```

La API escucha en `http://127.0.0.1:8000`. Laravel expone `/up` para comprobar que el servidor responde. Copie `.env.example` a `.env` y genere `APP_KEY` con `docker compose run --rm backend php artisan key:generate` si inicia un entorno nuevo. Los valores de base de datos de Compose son exclusivamente para desarrollo local.

Los comandos de PHP y Composer deben ejecutarse dentro del servicio `backend`.
