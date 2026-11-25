# Constitución
1. Monorepo con un solo Git; backend Laravel 13, PHP 8.5, Passport, Eloquent y PostgreSQL; frontend Vue 3, Vue Router, Composition API, TypeScript estricto y Vuetify. TypeScript exclusivamente en frontend.
2. Clean Architecture en ambas aplicaciones: Domain sin frameworks; Application orquesta casos de uso; Infrastructure implementa puertos; Presentation gestiona HTTP o UI. Evitar abstracciones sin utilidad.
3. Todas las rutas API salvo login requieren autenticación y usuario activo; autorización siempre en servidor. Hash de contraseñas; revocar tokens al cerrar sesión y desactivar usuario. No exponer secretos.
4. Cinco rutas de fútbol exactas; persistencia Eloquent sin SQL manual; importación idempotente; máximo 10 llamadas externas por ventana móvil de 60 segundos por token, con reserva atómica y fallback local.
5. Conventional Commits: type(scope): description en inglés; scopes backend-<module>, frontend-<module>, docs, monorepo. Un commit por tarea funcional, con cuerpo Refs: B1/F1/etc. No squash global. Incluir cambios de implementación pertinentes; estado de tarea y evidencia exclusivamente locales.
6. No agregar opcionales salvo Clean Architecture, Vuetify, Composition API, TypeScript frontend y Docker Compose para desarrollo. Pruebas manuales documentadas; sin frameworks adicionales. PostgreSQL es una decisión propuesta, no exigencia del PDF.
7. Definition of Done: criterios satisfechos, permisos verificados, sin secretos, diff revisado, resultado de pruebas registrado, commit propio. Si no se puede validar, declarar pendiente y no simular éxito.

8. Docker: PHP 8.5 CLI, Composer 2, Node 24 Alpine, PostgreSQL 17 Alpine. Sin Redis, Nginx ni infraestructura productiva. Configuración reproducible versionada; planificación excluida mediante .git/info/exclude. No incluir secretos en imágenes ni argumentos de build.
