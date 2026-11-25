# Pruebas: football

Precondiciones: entorno de prueba, datos controlados y permisos correspondientes.

## FOOTBALL-01
- Escenario y resultado esperado: Listar e importar liga como admin; ver detalle, equipos y jugadores; lector consulta copia local.
- Estado: PENDING
- Evidencia/commit: pendiente

## FOOTBALL-02
- Escenario y resultado esperado: Listas vacÃ­as y campos ausentes se muestran claramente; 404 tiene mensaje.
- Estado: PENDING
- Evidencia/commit: pendiente

Evidencia parcial 2026-11-25: /api/competitions devolvió 13 registros; detalle de competición devolvió 18 equipos (stale=false); /api/team devolvió 18 equipos y /api/players 522 jugadores. Flujo visual y lector pendientes.
