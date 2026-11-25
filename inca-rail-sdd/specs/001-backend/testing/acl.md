# Pruebas: acl

Precondiciones: entorno de prueba, datos controlados y permisos correspondientes.

## ACL-01
- Escenario y resultado esperado: Admin crea y edita usuario, asigna rol y desactiva; datos inválidos → 422.
- Estado: PASS — 2026-11-24
- Evidencia/commit: admin creó, editó, asignó rol y cambió estado de usuario; validación 422 observada. `68b3159` a `e8693f2`.

## ACL-02
- Escenario y resultado esperado: Admin crea y edita rol, asigna permisos; /me devuelve action/subject.
- Estado: PASS — 2026-11-24
- Evidencia/commit: admin creó/editó rol y asignó permiso; `/me` devolvió 14 reglas `{action, subject}`. `a379bd7`, `cdba206`, `49412ee`.

## ACL-03
- Escenario y resultado esperado: Consulta intenta cada operación administrativa → 403; impedir escalamiento y pérdida del último admin.
- Estado: PARTIAL — 2026-11-24
- Evidencia/commit: lector obtuvo 403 en `/users`; último admin protegido con 422 y rol admin inmutable con 403. No se probaron todas las operaciones administrativas ni concurrencia. `a032729`.
