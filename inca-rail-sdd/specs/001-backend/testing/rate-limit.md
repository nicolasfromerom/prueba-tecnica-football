# Pruebas: rate-limit

Precondiciones: entorno de prueba, datos controlados y permisos correspondientes.

## RATE-LIMIT-01
- Escenario y resultado esperado: Con reloj y proveedor controlados, 11 solicitudes distintas en 60s: máximo 10 llamadas externas; concurrencia no excede cupo.
- Estado: PASS — 2026-11-24
- Evidencia/commit: once reservas secuenciales y once concurrentes con token ficticio; 10 permitidas y 1 bloqueada. `5ce6291`.

## RATE-LIMIT-02
- Escenario y resultado esperado: Cupo agotado sin copia → 429; con copia → 200 stale; fallo externo sin copia → 503, con copia → 200 stale.
- Estado: PASS — 2026-11-24
- Evidencia/commit: 429 sin copia por HTTP; copia stale tras fallo; 503 sin copia; proveedor simulado con Retry-After impidió segunda llamada. `639e17e`.

## RATE-LIMIT-03
- Escenario y resultado esperado: Importaciones simultáneas de misma liga no duplican llamadas ni registros; Retry-After respetado.
- Estado: PARTIAL — 2026-11-24
- Evidencia/commit: importación real de Bundesliga devolvió 429 con Retry-After y terminó en reintento; sin duplicados. No se ejecutaron dos importaciones simultáneas de la misma liga.
