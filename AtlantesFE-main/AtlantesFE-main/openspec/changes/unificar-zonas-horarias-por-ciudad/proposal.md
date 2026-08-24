# Proposal: Soporte frontend para fecha/hora por ciudad

## Intent
Permitir que una sola aplicación Angular 17 funcione para usuarios de Bolivia y Perú sin depender de la zona horaria del navegador ni de servidores separados.

## Scope
- Leer `timezone_name` y `utc_offset_minutos` desde el JWT o desde el endpoint de perfil.
- Mostrar fechas de negocio en la zona horaria del usuario autenticado.
- Enviar al backend fechas de filtros y formularios de forma explícita y consistente.
- Agregar campos de zona horaria en la pantalla de ciudades.

## Out of Scope
- Implementar librerías externas nuevas salvo que el equipo decida usar una librería de fechas.
- Cambiar reglas funcionales de reportes o inventario.
- Convertir todos los componentes en una sola entrega; se priorizan formularios y reportes con fechas.

## Approach
El frontend tratará la zona horaria como parte del contexto del usuario. Los campos tipo fecha de negocio se enviarán como `YYYY-MM-DD` cuando sean filtros por día, y los campos fecha/hora se enviarán como strings explícitos que el backend interpretará en la zona del usuario. La UI de ciudades permitirá configurar `timezone_name` y `utc_offset_minutos`.

## Risks and Open Questions
- JavaScript `Date` convierte automáticamente usando la zona del navegador; esto puede alterar fechas si se usa sin cuidado.
- Hay muchos componentes que consumen `getTokenDetalle()`; conviene centralizar helpers en un servicio.
- Confirmar si los usuarios pueden cambiar ciudad activa durante sesión y si el token se refresca.
