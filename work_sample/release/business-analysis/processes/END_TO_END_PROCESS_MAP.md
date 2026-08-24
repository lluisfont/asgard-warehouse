# End-to-end process map

Estado: candidate_reconstruction  
Confianza: media

```mermaid
flowchart LR
  A[Cliente o tercero inicia solicitud] --> B[Validacion de acceso y datos]
  B --> C[Creacion de caso o embarque]
  C --> D[Asignacion operativa]
  D --> E[Gestion aduanera/logistica]
  E --> F[Carga y validacion documental]
  F --> G[OCR o revision manual]
  G --> H[Aprobaciones y estados]
  H --> I[Costos, planillas o facturacion]
  H --> J[Tracking y reporteria]
  I --> K[Conciliacion/cierre]
  J --> K
  K --> L[Archivo y consulta historica]
```

## Excepciones transversales

Documentos faltantes, OCR inconsistente, datos de catalogo incompletos, permiso insuficiente, integracion fallida, duplicidad y estados invalidos.
