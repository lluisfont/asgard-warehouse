# Runtime Topology

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

```mermaid
flowchart TB
  Browser["Browser / Mobile legacy"] --> PHP["PHP runtime"]
  PHP --> MySQL["MySQL"]
  PHP --> Files["Shared files"]
  PHP --> Cron["Cron/scripts"]
  PHP --> External["External services"]
  Cron --> MySQL
  Cron --> Files
```

## Notas

- El runtime mezcla requests interactivos, reportes pesados y operaciones documentales.
- Algunas APIs legacy Android acceden directo a PHP.
- No se observa separacion de workers para OCR/Excel/PDF.
