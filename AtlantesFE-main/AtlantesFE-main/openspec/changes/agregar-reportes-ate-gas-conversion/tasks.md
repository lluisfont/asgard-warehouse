# Tasks

## 1. Servicios backend existentes
- [ ] 1.1 En `AlmacenesService`, agregar `reporteategasstatus()` apuntando a `ate-gas/reporte-status/{idcliente}/{fecha_inicial}/{fecha_final}`.
- [ ] 1.2 En `AlmacenesService`, agregar `reporteategasingresos()` apuntando a `ate-gas/reporte-ingresos/{idcliente}/{fecha_inicial}/{fecha_final}`.
- [ ] 1.3 En `AlmacenesService`, agregar `reporteategassalidas()` apuntando a `ate-gas/reporte-salidas/{idcliente}/{fecha_inicial}/{fecha_final}`.

## 2. Componentes de reportes
- [ ] 2.1 Crear `src/app/reporte-ate-gas-status/` basado en `reporte-ate-gas-demanda/`.
- [ ] 2.2 Crear `ReporteAteGasStatusComponent` con permiso `108`, título `Reporte de Status` y llamada a `reporteategasstatus()`.
- [ ] 2.3 Crear `src/app/reporte-ate-gas-ingresos/` basado en `reporte-ate-gas-demanda/`.
- [ ] 2.4 Crear `ReporteAteGasIngresosComponent` con permiso `106`, título `Reporte de Ingresos` y llamada a `reporteategasingresos()`.
- [ ] 2.5 Crear `src/app/reporte-ate-gas-salidas/` basado en `reporte-ate-gas-demanda/`.
- [ ] 2.6 Crear `ReporteAteGasSalidasComponent` con permiso `107`, título `Reporte de Salidas` y llamada a `reporteategassalidas()`.
- [ ] 2.7 Implementar columnas dinámicas desde la respuesta backend para tabla y Excel.

## 3. Routing y módulo Angular
- [ ] 3.1 Importar los tres componentes nuevos en `src/app/app.routing.ts`.
- [ ] 3.2 Registrar rutas `/reporte-ate-gas-status`, `/reporte-ate-gas-ingresos` y `/reporte-ate-gas-salidas`.
- [ ] 3.3 Importar los tres componentes nuevos en `src/app/app.module.ts`.
- [ ] 3.4 Declarar los tres componentes nuevos en `declarations`.

## 4. Menú lateral y permisos
- [ ] 4.1 En `menulateral.component.ts`, agregar flags de permiso para `ver_reporte_ate_gas_status`, `ver_reporte_ate_gas_ingresos` y `ver_reporte_ate_gas_salidas`.
- [ ] 4.2 Asociar los flags con los IDs de módulo `108`, `106` y `107` respectivamente.
- [ ] 4.3 En `menulateral.component.html`, agregar los enlaces dentro de `reportesAteGasSubmenu`.
- [ ] 4.4 Ajustar el estado expandido/activo del submenú para contemplar los nuevos `menu_item`.

## 5. Validación funcional
- [ ] 5.1 Ejecutar build del frontend Angular.
- [ ] 5.2 Confirmar que Reporte Demanda sigue funcionando igual.
- [ ] 5.3 Confirmar que cada reporte nuevo valida cliente y fechas antes de consultar.
- [ ] 5.4 Confirmar que cada reporte nuevo consulta el endpoint correcto.
- [ ] 5.5 Confirmar que cada reporte nuevo renderiza columnas según la respuesta backend.
- [ ] 5.6 Confirmar que la exportación Excel contiene título, cabeceras y datos correctos.
- [ ] 5.7 Confirmar visibilidad de enlaces con usuario administrador y con usuario normal según permisos.

## 6. OpenSpec
- [ ] 6.1 Revisar que `proposal.md`, `design.md`, `tasks.md` y `spec.md` coincidan con la implementación.
- [ ] 6.2 Ejecutar `openspec validate agregar-reportes-ate-gas-conversion` si el CLI está disponible.
