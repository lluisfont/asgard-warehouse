# Bulk Shipment Quotation Import - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo A - Cargar Excel logistico

1. El usuario selecciona archivo de carga masiva desde Logistica.
2. ASGARD guarda el archivo con `GlobalClass::guardarArchivo`.
3. ASGARD lee el Excel con PHPExcel.
4. ASGARD recorre filas desde la 2.
5. Por fila, ASGARD obtiene linea, pedido, orden compra, cantidad, tipo bulto, peso neto, proveedor y descripcion.
6. ASGARD resuelve linea por los primeros tres caracteres y cliente.
7. ASGARD resuelve proveedor exacto por nombre.
8. ASGARD resuelve tipo de bulto por codigo de unidad.

## Flujo B - Crear cotizaciones/embarques por fila

1. ASGARD combina datos de fila con datos comunes del formulario.
2. ASGARD construye magnitudes de bulto/cantidad/peso/volumen/contenedor.
3. ASGARD toma tramos/rutas desde POST `tramo`.
4. ASGARD toma operador desde POST `idoperador`.
5. ASGARD llama `CotizacionClass::guardarCotizacionCliente`.
6. ASGARD acumula ids devueltos.
7. ASGARD devuelve JSON con `idembarque`.

## Flujo C - Mostrar resultado

1. El frontend recibe respuesta JSON.
2. Si hay mensaje de observaciones, ASGARD muestra tabla de resultados.
3. El modulo muestra estado de carga correcta aunque existan advertencias segun flujo observado.
