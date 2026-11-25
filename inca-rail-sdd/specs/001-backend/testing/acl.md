# Pruebas: acl

Precondiciones: entorno de prueba, datos controlados y permisos correspondientes.

## ACL-01
- Escenario y resultado esperado: Admin crea y edita usuario, asigna rol y desactiva; datos inválidos → 422.
- Estado: PENDING
- Evidencia/commit: pendiente

## ACL-02
- Escenario y resultado esperado: Admin crea y edita rol, asigna permisos; /me devuelve action/subject.
- Estado: PENDING
- Evidencia/commit: pendiente

## ACL-03
- Escenario y resultado esperado: Consulta intenta cada operación administrativa → 403; impedir escalamiento y pérdida del último admin.
- Estado: PENDING
- Evidencia/commit: pendiente
