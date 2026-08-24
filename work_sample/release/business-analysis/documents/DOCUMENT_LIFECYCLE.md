# Document lifecycle

Estado: candidate_reconstruction  
Confianza: media

```mermaid
stateDiagram-v2
  [*] --> Requerido
  Requerido --> Cargado
  Cargado --> EnOCR
  EnOCR --> Validado
  EnOCR --> Observado
  Cargado --> Validado
  Validado --> Aprobado
  Observado --> Corregido
  Corregido --> Validado
  Aprobado --> Enviado
  Enviado --> Archivado
```

## Variantes

Algunos documentos se generan internamente, otros se cargan por usuario/tercero y otros se derivan de OCR o descarga masiva. El ciclo exacto depende del dominio.
