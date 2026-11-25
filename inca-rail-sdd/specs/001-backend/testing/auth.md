# Pruebas: auth

Precondiciones: entorno de prueba, datos controlados y permisos correspondientes.

## AUTH-01
- Escenario y resultado esperado: Login válido → token; inválido → 401; contraseña almacenada con hash.
- Estado: PASS — 2026-11-24
- Evidencia/commit: login válido emitió token; credenciales inválidas devolvieron 401; contraseña con cast hashed. `825a529`.

## AUTH-02
- Escenario y resultado esperado: Sin token → 401; logout invalida token; usuario desactivado no accede incluso con token previo.
- Estado: PASS — 2026-11-24
- Evidencia/commit: sin token 401, logout 200 y token reutilizado 401; desactivación revocó token y `/me` devolvió 401. `69df9af`, `ac2af83`.
