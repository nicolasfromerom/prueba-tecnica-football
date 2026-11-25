# Pruebas: errors

Precondiciones: entorno de prueba, datos controlados y permisos correspondientes.

## ERRORS-01
- Escenario y resultado esperado: Verificar JSON uniforme para 401,403,404,422,429,502/503; sin stack trace ni secretos.
- Estado: PASS — 2026-11-24
- Evidencia/commit: 401, 403, 404, 422 y 503 verificados manualmente; 429 con Retry-After y 502 vía HTTP con proveedor simulado, 2 pruebas y 11 aserciones. `f1abc46`, `c324ba7`.
