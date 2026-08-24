# Inventory VIN Billing Control - Process Definition

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Objetivo de negocio

Controlar la facturacion mensual por VIN/chasis de inventario, precalculando unidades internacionales, nacionales/locales, VIN unicos y VIN facturables para un periodo operativo, permitiendo confirmar facturacion y exportar el detalle mensual.

## Alcance observado

- Modulo contable `Facturacion por VIN`.
- Periodo por defecto del dia 21 del mes anterior al dia 20 del mes actual.
- Restriccion de fecha inicio al dia 21 y maximo permitido segun fecha actual.
- Precalculo mediante API `inventario/reportes/facturacion-chasis`.
- KPIs: VIN internacional, VIN nacional, VIN unicos y VIN para facturar.
- Confirmacion de facturacion mediante API `confirmar-facturacion-chasis`.
- Consolidado mensual con gestion, mes, unidades, tarifa USD, total USD y total Bs.
- Exportacion Excel por periodo confirmado/listado.
- Tablas `inventario_facturacion_periodo` e `inventario_facturacion_chasis`.

## Fuera de alcance observado

- Formula interna de facturabilidad dentro del API `url_pedidos`.
- Emision de factura fiscal o documento contable final.
- Configuracion de tarifa USD.
- Conciliacion con cuentas por cobrar posterior.

## Actores

| Actor | Rol observado |
| --- | --- |
| Usuario contable/operativo | Precalcula, confirma y exporta facturacion por VIN. |
| ASGARD | Presenta UI, periodo, KPIs y llamadas API. |
| API `url_pedidos` | Calcula VIN facturables, confirma periodos y genera Excel. |
| Inventario | Fuente de VIN/chasis internacionales y nacionales/locales. |

## Entradas

- Fecha inicio.
- Fecha fin calculada.
- Token JWT de sesion.
- Datos de inventario por chasis.
- Tarifa por unidad.

## Salidas

- KPIs de precalculo.
- Periodo de facturacion identificado.
- Facturacion confirmada.
- Consolidado mensual.
- Excel de detalle.

## Evidencia principal

| Evidencia | Observacion |
| --- | --- |
| `index_archivos/contables/facturacion-inventario.php` | UI y flujo completo de precalculo/confirmacion/exportacion. |
| `.data_base/asgard.sql` | Tablas `inventario_facturacion_periodo`, `inventario_facturacion_chasis`. |
| `url_pedidos` endpoints | Contrato externo observado para calculo y confirmacion. |

## Criterios de aceptacion candidatos

- El periodo inicia siempre en dia 21 y finaliza el dia 20 del mes siguiente.
- La fecha inicio no puede ser posterior al maximo permitido.
- El precalculo muestra KPIs antes de confirmar.
- La confirmacion se ejecuta contra el mismo rango de fechas.
- El consolidado mensual lista periodos con unidades, tarifa y totales.
- El Excel se descarga solo si el API devuelve base64 valido.
