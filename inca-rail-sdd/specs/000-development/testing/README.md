# Pruebas de infraestructura
Todos los escenarios comienzan PENDING. Registrar fecha, PASS/FAIL/BLOCKED, evidencia local y commit relacionado.
| ID | Acción | Esperado | Estado |
|---|---|---|---|
| DEV-T01 | docker compose config | Configuración válida | PASS — 2026-11-24; `docker compose config --quiet`; commit `ddce4cb` |
| DEV-T02 | Ejecutar php -v y composer --version en backend | PHP 8.5 y Composer 2 | PASS — 2026-11-24; imagen `inca-rail-backend-dev`, PHP 8.5.13, Composer 2.11.3, extensiones incluidas pdo_pgsql e intl; commits `c8d5789`, `858e9cc` |
| DEV-T03 | Ejecutar node -v y npm -v en frontend | Node 24 y npm operativo | PASS — 2026-11-24; imagen `inca-rail-frontend-dev`, Node v24.22.3 y npm 11.19.1; commit `1669ba3` |
| DEV-T04 | Arrancar db y verificar healthcheck y conexión desde backend | PostgreSQL disponible | PASS — 2026-11-24; `docker compose ps` healthy y PDO devolvió `DB_OK`; commit `ddce4cb` |
| DEV-T05 | Instalar dependencias, migrar y seed según README | Sin errores | PASS — 2026-11-24; Composer, migraciones, seed y cliente Passport ejecutados en backend; prueba aislada de PostgreSQL conservó 1 admin, 18 equipos y 522 jugadores |
| DEV-T06 | Levantar SPA y API, probar login vía proxy | API accesible y token válido | PENDING |
| DEV-T07 | Editar componente Vue | HMR sin reconstruir imagen | PENDING |
| DEV-T08 | Reiniciar servicios sin borrar volúmenes | Datos conservados | PENDING |
| DEV-T09 | Ejecutar npm run build dentro del frontend | Build correcto | PENDING |
| DEV-T10 | Revisar archivos rastreados y configuración | Sin secretos ni planificación local | PENDING |
