# Plan de infraestructura
## Exclusiones locales antes del primer commit
Inicializar Git solo en raíz si no existe. Añadir a `.git/info/exclude` (no al .gitignore versionado):
```gitignore
/.specify/
/specs/
/AGENTS.md
/.codex/
/.agents/
/sdd/
```
Verificar con `git check-ignore -v AGENTS.md specs/ .specify/` y `git ls-files -- AGENTS.md specs/ .specify/`. Si ya están rastreados, informar antes de modificar el historial. El README de este paquete es local hasta sustituirlo por documentación de aplicación.
## Servicios
- backend: php:8.5-cli, Composer copiado de composer:2, extensiones pdo_pgsql, mbstring, xml, curl, zip y otras exigidas por Composer; servidor Artisan en 0.0.0.0:8000.
- frontend: node:24-alpine, Vite en 0.0.0.0:5173; npm ci tras existir lockfile.
- db: postgres:17-alpine, healthcheck pg_isready, volumen de datos; sin puerto público por defecto.
- compose.yml en raíz; Dockerfiles y .dockerignore por aplicación; bind mounts de código y volúmenes separados para vendor y node_modules. Publicar puertos de desarrollo en loopback.
- Variables locales sin secretos versionados; DB_HOST=db. Cache database y migraciones de locks. Proxy Vite /api hacia backend:8000, quitar prefijo.
## Secuencia
Crear infraestructura inicial antes de scaffold. Usar docker compose run --rm --no-deps con comandos explícitos para generar Laravel y Vue en directorios montados vacíos; no depender de un servidor que aún no existe. Tras scaffold, instalar dependencias en volúmenes, generar APP_KEY, migrar/seed y configurar Passport según tareas backend. Arrancar con docker compose up -d --build. Verificar permisos de escritura y HMR. Documentar comandos reales y evitar docker compose down -v salvo reset explícito.
