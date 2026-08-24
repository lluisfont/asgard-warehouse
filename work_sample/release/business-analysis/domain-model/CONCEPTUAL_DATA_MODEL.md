# Conceptual data model

Estado: candidate_reconstruction  
Confianza: media

```mermaid
flowchart LR
  Cliente --> Usuario
  Cliente --> Solicitud
  Solicitud --> Caso
  Caso --> Embarque
  Embarque --> Mercancia
  Mercancia --> Partida
  Caso --> Documento
  Documento --> OCR
  Caso --> Estado
  Caso --> Costo
  Costo --> Factura
  Factura --> Pago
  Caso --> Reporte
  Proveedor --> Caso
  AgenteAduana --> Caso
  RutaViaje --> Embarque
```

## Lectura

El modelo conceptual se ha inferido desde nombres de tablas, dominios reconstruidos y endpoints. Las relaciones reales pueden estar implementadas mediante claves no declaradas, campos historicos o consultas manuales.
