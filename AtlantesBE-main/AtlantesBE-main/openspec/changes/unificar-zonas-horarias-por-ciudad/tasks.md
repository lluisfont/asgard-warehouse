# Tasks

## 1. Modelo de datos
- [x] 1.1 Agregar `timezone_name` y `utc_offset_minutos` a `t_ciudad`.
- [x] 1.2 Poblar Bolivia con `America/La_Paz` y `-240`.
- [x] 1.3 Poblar Perú con `America/Lima` y `-300`.
- [ ] 1.4 Clasificar columnas `datetime` críticas como fecha local de negocio, fecha calendario o instante auditable.
- [ ] 1.5 Agregar columnas UTC solo en tablas donde se necesite trazabilidad técnica.

## 2. Autenticación y contexto
- [x] 2.1 Actualizar login en `app/routes/usuarios.php` para unir `t_ciudad`.
- [x] 2.2 Incluir `timezone_name` y `utc_offset_minutos` en el payload JWT.
- [x] 2.3 Ajustar endpoint de refresco/cambio de ciudad del JWT si aplica.
- [x] 2.4 Opcional: agregar middleware para exponer claims decodificados y zona horaria como atributos del request.

## 3. Servicio fecha/hora
- [x] 3.1 Crear `app/services/DateTimeService.php`.
- [x] 3.2 Agregar métodos para `nowLocal`, `nowUtc`, formato MySQL y conversión local/UTC.
- [x] 3.3 Cargar el servicio desde bootstrap/autoload del backend.

## 4. Rutas y SQL
- [ ] 4.1 Reemplazar usos directos de `date()` relacionados con negocio por el servicio central.
- [ ] 4.2 Reemplazar `new DateTime()` sin zona por `DateTimeImmutable` con zona del usuario.
- [ ] 4.3 Revisar `CURRENT_TIMESTAMP()` y `NOW()` en SQL embebido y migrarlos a valores calculados o `UTC_TIMESTAMP()` según semántica.
- [x] 4.4 Actualizar `/ciudades` GET/POST/PUT para exponer y persistir los nuevos campos.
- [ ] 4.5 Revisar integraciones OVP/SIAT para no romper fechas regulatorias.

## 5. Validación
- [ ] 5.1 Probar login con usuario Bolivia y usuario Perú en el mismo servidor.
- [ ] 5.2 Probar creación de ingreso/salida/factura/cobro con ambos usuarios y comparar hora guardada.
- [ ] 5.3 Probar reportes con filtros de fecha alrededor de medianoche.
- [ ] 5.4 Ejecutar pruebas PHP disponibles o validación manual de endpoints críticos.
- [ ] 5.5 Validar artefactos OpenSpec con `openspec validate unificar-zonas-horarias-por-ciudad` si el CLI está instalado.
