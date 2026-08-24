# Alicorp OCR Bulk Shipment Intake - State Model

## Estados candidatos por archivo

| Estado | Condicion observada | Resultado |
| --- | --- | --- |
| Archivo recibido | Usuario envia PDF/ZIP. | El backend intenta copiar y extraer. |
| Archivo invalido | Extension distinta de PDF/ZIP o error de copia/extraccion. | Error general. |
| OCR con error | `respuestaOCR.error` verdadero. | Se informa error por archivo. |
| OCR persistido | OCR devuelve datos y `idocr_alicorp`. | Puede crear embarque. |
| Embarque creado con faltantes | Embarque creado pero `sinData` verdadero. | Queda para completar manualmente. |
| Embarque y GA creados | Embarque creado y `sinData` falso. | Se crea solicitud de Gestion Aduanera. |
| Documentos/exchange asociados | UI completa exchange y adjuntos. | El proceso queda enlazado con intercambio documental. |

## Transiciones candidatas

| Desde | Evento | Hacia |
| --- | --- | --- |
| Archivo recibido | Copia/extraccion falla | Archivo invalido |
| Archivo recibido | OCR falla | OCR con error |
| Archivo recibido | OCR exitoso | OCR persistido |
| OCR persistido | Crear embarque falla | OCR persistido con error de creacion |
| OCR persistido | Crear embarque y faltan datos | Embarque creado con faltantes |
| OCR persistido | Crear embarque y datos completos | Embarque y GA creados |
| Embarque y GA creados | UI crea/mergea exchanges | Documentos/exchange asociados |

## Estados auxiliares

- `sinData`: bandera runtime para decidir si crear GA automatica.
- `cargar_servicios`: lista runtime de servicios adicionales candidatos.
- `idocr_alicorp`: vinculo persistido entre OCR y embarque.
- `idExchange`: vinculos posteriores de intercambio documental.
