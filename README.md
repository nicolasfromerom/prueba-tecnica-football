# Inca Rail

Monorepositorio de una aplicación para consultar competiciones, equipos y jugadores de fútbol, y administrar usuarios, roles y permisos. El entorno de desarrollo se ejecuta con Docker Compose.

## Estructura

| Ruta | Contenido |
| --- | --- |
| [`compose.yml`](compose.yml) | Servicios de desarrollo: PostgreSQL, API y aplicación web. |
| [`inca-rail-sdd/backend/`](inca-rail-sdd/backend/) | API Laravel, autenticación con Passport, reglas de acceso, integración con football-data.org, migraciones y pruebas. |
| [`inca-rail-sdd/frontend/`](inca-rail-sdd/frontend/) | Aplicación Vue 3 con TypeScript, Vue Router y Vuetify; consume la API mediante el proxy `/api` de Vite. |
| `inca-rail-sdd/specs/` | Especificaciones y registros locales de pruebas del proyecto. |
| `inca-rail-sdd/.specify/` | Criterios técnicos del proyecto. |

Cada aplicación tiene instrucciones de instalación y arranque en su propio README:

- [Levantar y configurar el backend](inca-rail-sdd/backend/README.md)
- [Levantar el frontend](inca-rail-sdd/frontend/README.md)

Los comandos de ambos README se ejecutan **desde esta raíz**. Antes de iniciar los servicios, configura el `.env` de la raíz y el de `inca-rail-sdd/backend/` siguiendo las instrucciones del backend. La API queda disponible en `http://127.0.0.1:8000` y el frontend en `http://127.0.0.1:5173`.

## Límite de consultas al servicio externo

La aplicación permite **como máximo 10 intentos de consulta a football-data.org por cada ventana móvil de 60 segundos y por token del proveedor**. Es un presupuesto local de la integración: evita que varios usuarios o importaciones simultáneas consuman rápidamente el cupo del mismo token y reduce las respuestas de límite del proveedor. No limita las lecturas locales de equipos y jugadores.

Antes de cada llamada externa, el backend busca una respuesta en caché. Si existe, la reutiliza sin gastar un intento. Si falta, reserva un lugar en el presupuesto mediante un bloqueo de caché para que las solicitudes concurrentes no superen el límite. Guarda las marcas de tiempo de los intentos de los últimos 60 segundos; al llegar a diez, responde `429` con `Retry-After` para indicar cuándo se puede reintentar. Un intento reservado cuenta aunque la llamada externa falle.

Si football-data.org devuelve `429`, el backend respeta su `Retry-After` y suspende temporalmente nuevas llamadas con ese token. El catálogo y las competiciones importadas se reutilizan durante el tiempo definido por `FOOTBALL_CACHE_TTL` (24 horas por defecto). Si una actualización falla y ya existe una copia completa, se entrega esa copia con `meta.stale=true`. Al importar una competición grande, las respuestas de equipos obtenidas se conservan en caché para que un reintento no repita esas llamadas; la importación local se marca como completa solo cuando termina toda la plantilla.
