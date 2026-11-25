# Inca Rail API

API Laravel 13 con PostgreSQL y tokens personales de Passport. Todos los comandos PHP y Composer se ejecutan dentro de Docker. Desde la raíz del repositorio:

```sh
docker compose build backend
docker compose up -d db
docker compose run --rm --no-deps backend sh -lc 'test -f .env || cp .env.example .env; composer install --no-interaction; php artisan key:generate; php artisan migrate --force; php artisan passport:keys; php artisan passport:client --personal --name="Inca Rail Personal Access" --no-interaction'
```

Antes de `db:seed`, configure `ADMIN_EMAIL` y `ADMIN_PASSWORD` en `inca-rail-sdd/backend/.env` (archivo local ignorado). Después ejecute:

```sh
docker compose run --rm backend php artisan db:seed --force
docker compose up -d backend
docker compose exec -T backend php artisan test
```

La API escucha en `http://127.0.0.1:8000`; `/up` es el chequeo de salud. `POST /login` recibe `email` y `password`, devuelve un Bearer token; `POST /logout` lo revoca. Las demás rutas requieren token y usuario activo. `GET /me` devuelve los permisos `{action, subject}` vigentes.

Rutas administrativas: `GET/POST /users`, `GET/PUT /users/{id}`, `PATCH /users/{id}/status`, `PUT /users/{id}/roles`, `GET/POST /roles`, `GET/PUT /roles/{id}`, `GET /permissions` y `PUT /roles/{id}/permissions`. El rol `admin` conserva sus permisos y no se puede desactivar ni quitar al último administrador activo.

Rutas de fútbol: `GET /competitions`, `GET /competitions/{competition_id}`, `GET /team`, `GET /team/{team_id}`, `GET /players` y `GET /players/{player_id}`. Los identificadores de estas rutas son los `external_id` de football-data.org. La consulta de detalle de competición puede importar datos y responde con `Cache-Control: no-store`; el rol `consulta` solo ve una copia local completa. Las rutas de equipos y jugadores no llaman al proveedor externo.

Configure `FOOTBALL_DATA_TOKEN` en el `.env` local para consultar [football-data.org v4](https://docs.football-data.org/general/v4/). Sin token, las solicitudes que requieren proveedor devuelven 503 si no existe copia local. El catálogo y las competiciones tienen TTL configurable con `FOOTBALL_CACHE_TTL` (86400 segundos por defecto). El límite local es de diez intentos externos por ventana móvil de 60 segundos y token. La importación de una competición grande puede requerir reintentar tras el período indicado por `Retry-After`; las respuestas de equipos ya recibidas se conservan temporalmente y la base local solo se marca como completa al finalizar toda la plantilla. Una copia previa se devuelve con `meta.stale=true` si el proveedor falla o se agota el cupo.

Las respuestas incluyen `success`, `message`, `data` y `meta`; los errores agregan `error.code` y `error.details`. El valor de `shirt_number` permanece `null` cuando el proveedor no informa camiseta.
