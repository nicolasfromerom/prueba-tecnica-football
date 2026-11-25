# Tareas de infraestructura
- [x] DEV-01 — Configurar exclusiones locales y revisar Git. Sin commit (configuración local). Los archivos internos ya estaban rastreados; se informó y se continuó por indicación del usuario.
- [x] DEV-02 — Dockerfile PHP y Composer, extensiones y .dockerignore. Commit: `build(backend-core): configure php development image`. Validado PHP 8.5.13, Composer 2.11.3 y extensiones en `c8d5789`.
- [x] DEV-03 — Dockerfile Node y .dockerignore. Commit: `build(frontend-core): configure node development image`. Validado Node v24.22.3 y npm 11.19.1 en `1669ba3`.
- [x] DEV-04 — Compose con tres servicios, red, volúmenes, healthcheck y variables de ejemplo. Commit: `ddce4cb`. Validados `docker compose config`, imágenes, healthcheck y conexión PostgreSQL desde backend.
- [ ] DEV-05 — Tras BACKEND-01, FRONTEND-01 y configuración Passport/migraciones, ejecutar pruebas de integración del entorno. Registrar evidencia local; correcciones con commits propios, sin commit vacío.
