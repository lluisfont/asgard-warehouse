# Document Service Plan OCR Capture - Process Flow

1. El usuario solicita OCR del documento.
2. ASGARD envia la URL a Azure Read API.
3. ASGARD consulta el resultado hasta `succeeded` o timeout.
4. ASGARD recorre lineas para extraer numero, BL, monto y fechas.
5. Si los campos minimos existen, marca lecturas anteriores como eliminadas.
6. ASGARD inserta la nueva lectura en `dav_planillasdp`.

```mermaid
flowchart TD
  A["Documento intercambio"] --> B["Azure Read OCR"]
  B --> C["Extraer lineas clave"]
  C --> D{"Campos minimos?"}
  D -->|No| E["Documento diferente/no aceptado"]
  D -->|Si| F["Soft delete lectura previa"]
  F --> G["Insertar dav_planillasdp"]
```
