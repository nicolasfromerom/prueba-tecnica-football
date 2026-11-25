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
