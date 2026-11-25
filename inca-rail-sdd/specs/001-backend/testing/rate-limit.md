# Pruebas: rate-limit

Precondiciones: entorno de prueba, datos controlados y permisos correspondientes.

## RATE-LIMIT-01
- Escenario y resultado esperado: Con reloj y proveedor controlados, 11 solicitudes distintas en 60s: máximo 10 llamadas externas; concurrencia no excede cupo.
- Estado: PENDING
- Evidencia/commit: pendiente

## RATE-LIMIT-02
- Escenario y resultado esperado: Cupo agotado sin copia → 429; con copia → 200 stale; fallo externo sin copia → 503, con copia → 200 stale.
- Estado: PENDING
- Evidencia/commit: pendiente

## RATE-LIMIT-03
- Escenario y resultado esperado: Importaciones simultáneas de misma liga no duplican llamadas ni registros; Retry-After respetado.
- Estado: PENDING
- Evidencia/commit: pendiente
