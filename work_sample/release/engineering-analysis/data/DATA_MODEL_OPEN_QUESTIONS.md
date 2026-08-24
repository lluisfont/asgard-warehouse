# Data Model Open Questions

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

- Catalogo oficial de `idestado*`, `idtipo*`, permisos y clientes especiales.
- Uso real de tablas SQL-only (`con_*`, `serv_*`, `cn_*`, `bot_*`).
- Politica de retencion de documentos/OCR/logs.
- Ownership de tablas `pbi_*`, temporales y vistas/materializaciones.
- Relacion exacta entre `dav_edp` y `logis_edp`.
- Fuente de verdad para DB vs filesystem.
- Confirmacion/correccion de inferencias semanticas para tablas y campos, especialmente campos `id*`, estados, importes, documentos, tokens, datos personales y tablas `tmp_*`.
