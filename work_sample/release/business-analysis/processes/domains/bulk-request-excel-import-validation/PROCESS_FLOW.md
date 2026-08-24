# Bulk Request Excel Import Validation - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo A - Descargar formato

1. El usuario abre Nueva Solicitud.
2. Si el cliente es 560 o 755, ASGARD muestra Carga Masiva.
3. El usuario descarga el formato Excel.
4. ASGARD carga `FormatoSolicitudMasiva.xlsx`.
5. ASGARD rellena hojas auxiliares de proveedores, operadores y servicios adicionales.
6. ASGARD aplica validaciones/listas nombradas en columnas del formato.
7. ASGARD entrega el Excel.

## Flujo B - Importar archivo

1. El usuario selecciona archivo `adjunto`.
2. ASGARD guarda el archivo en carpeta por cliente.
3. ASGARD lee el Excel con PHPExcel.
4. ASGARD recorre filas desde la 2 hasta la ultima fila observada.
5. ASGARD transforma fechas Excel a `Y-m-d`.
6. ASGARD inserta las filas en `dav_solicitudesprevias` para cliente/usuario.

## Flujo C - Validar filas

1. ASGARD valida cada campo contra catalogos.
2. ASGARD convierte valores textuales a ids.
3. ASGARD marca `error=1` y acumula `mensajeerror` si falta un maestro obligatorio.
4. ASGARD marca observaciones no bloqueantes para transportista o linea opcional.
5. ASGARD devuelve tabla HTML con resultado por fila.

## Flujo D - Crear solicitudes

1. ASGARD cuenta filas con error.
2. Si hay algun error, ASGARD rechaza el lote completo.
3. Si no hay errores, ASGARD crea `dav_casosprevios` por fila.
4. ASGARD crea documentos previos por modo de transporte.
5. ASGARD crea tramite inicial si la fila trae servicio adicional valido.
6. ASGARD devuelve estado de carga exitosa.
