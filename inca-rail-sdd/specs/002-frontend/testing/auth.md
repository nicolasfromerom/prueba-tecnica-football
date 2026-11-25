# Pruebas: auth

Precondiciones: entorno de prueba, datos controlados y permisos correspondientes.

## AUTH-01
- Escenario y resultado esperado: Login válido abre aplicación; inválido muestra error; logout limpia sesión.
- Estado: PENDING
- Evidencia/commit: pendiente

## AUTH-02
- Escenario y resultado esperado: 401 de ruta protegida redirige; 403 informa; recarga exige login.
- Estado: PENDING
- Evidencia/commit: pendiente

Evidencia parcial 2026-11-25: proxy /api/login v�lido y /api/logout exitosos; credencial inv�lida y /api/me sin token devolvieron 401. Interfaz y recarga pendientes.
