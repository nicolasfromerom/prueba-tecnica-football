# Pruebas de infraestructura
Todos los escenarios comienzan PENDING. Registrar fecha, PASS/FAIL/BLOCKED, evidencia local y commit relacionado.
| ID | Acción | Esperado | Estado |
|---|---|---|---|
| DEV-T01 | docker compose config | Configuración válida | PENDING |
| DEV-T02 | Ejecutar php -v y composer --version en backend | PHP 8.5 y Composer 2 | PENDING |
| DEV-T03 | Ejecutar node -v y npm -v en frontend | Node 24 y npm operativo | PENDING |
| DEV-T04 | Arrancar db y verificar healthcheck y conexión desde backend | PostgreSQL disponible | PENDING |
| DEV-T05 | Instalar dependencias, migrar y seed según README | Sin errores | PENDING |
| DEV-T06 | Levantar SPA y API, probar login vía proxy | API accesible y token válido | PENDING |
| DEV-T07 | Editar componente Vue | HMR sin reconstruir imagen | PENDING |
| DEV-T08 | Reiniciar servicios sin borrar volúmenes | Datos conservados | PENDING |
| DEV-T09 | Ejecutar npm run build dentro del frontend | Build correcto | PENDING |
| DEV-T10 | Revisar archivos rastreados y configuración | Sin secretos ni planificación local | PENDING |
