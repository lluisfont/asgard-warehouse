# Delta for Fecha/Hora UI

## ADDED Requirements

### Requirement: Contexto horario del usuario
La aplicación SHALL obtener `timezone_name` y `utc_offset_minutos` desde el JWT decodificado o desde el perfil del usuario autenticado.

#### Scenario: Usuario autenticado en Bolivia
- GIVEN un JWT con `timezone_name = "America/La_Paz"`
- WHEN la aplicación carga el contexto de usuario
- THEN SHALL exponer esa zona horaria a componentes y servicios que formatean fechas.

#### Scenario: Usuario autenticado en Perú
- GIVEN un JWT con `timezone_name = "America/Lima"`
- WHEN un componente muestra una fecha/hora recibida del backend
- THEN la fecha/hora SHALL mostrarse conforme a la zona horaria del usuario.

### Requirement: Administración de zona horaria en ciudades
La pantalla de ciudades SHALL permitir consultar, crear y editar `timezone_name` y `utc_offset_minutos`.

#### Scenario: Crear ciudad Perú
- GIVEN el administrador abre nueva ciudad
- WHEN selecciona Perú o Lima
- THEN puede registrar `America/Lima` y `-300`.

### Requirement: Envío explícito de filtros de fecha
Los filtros por rango de fecha SHALL enviarse al backend como fechas calendario (`YYYY-MM-DD`) sin conversión automática por zona del navegador.

#### Scenario: Reporte por día
- GIVEN un usuario selecciona `2026-06-24`
- WHEN se consulta un reporte por fecha
- THEN el frontend SHALL enviar `2026-06-24` y SHALL NOT convertirlo a ISO UTC con desplazamiento de día.

## MODIFIED Requirements

### Requirement: Uso de Date en Angular
Los componentes SHALL evitar `new Date(string)` para fechas de negocio cuando pueda producir conversiones implícitas del navegador.
(Previously: algunos componentes podían depender de `Date` o inputs HTML sin normalización central.)

#### Scenario: Mostrar fecha recibida como string local
- GIVEN el backend retorna `2026-06-24 08:30:00` como hora local de negocio
- WHEN el componente la muestra
- THEN SHALL usar un helper de formato que preserve la zona de usuario.
