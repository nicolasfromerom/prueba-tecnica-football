# Pruebas: football

Precondiciones: entorno de prueba, datos controlados y permisos correspondientes.

## FOOTBALL-01
- Escenario y resultado esperado: GET /competitions lista catálogo; detalle importa equipos y jugadores; repetir no duplica.
- Estado: PASS — 2026-11-24
- Evidencia/commit: catálogo real con 13 competiciones; Bundesliga importada tras 429/Retry-After, 18 equipos y 522 jugadores; dos lecturas conservaron el conteo. HTTP simulado repitió importación sin duplicados. `6e1f2ea`.

## FOOTBALL-02
- Escenario y resultado esperado: GET /team y /players devuelven solo datos locales; detalles correctos; ID inexistente → 404.
- Estado: PASS — 2026-11-24
- Evidencia/commit: `/team`, `/team/{id}`, `/players`, `/players/{id}` consultaron datos locales; detalle real de Bundesliga correcto; detalles inexistentes devolvieron 404. `372e666` a `474634b`.

## FOOTBALL-03
- Escenario y resultado esperado: Jugador sin camiseta externa conserva null; no inventar datos; fallo parcial no marca importación completa.
- Estado: PASS — 2026-11-24
- Evidencia/commit: proveedor simulado con `shirtNumber=null` persistió `shirt_number=null`; fallo parcial simulado no creó competición completa. `6e1f2ea`, `639e17e`.
