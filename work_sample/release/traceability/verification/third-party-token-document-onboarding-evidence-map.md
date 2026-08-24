# Evidence Map - third-party-token-document-onboarding

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

| Afirmacion | Evidencia | Confianza |
| --- | --- | --- |
| Agente aduana mantiene maestro, relacion cliente, contactos, documentos y token. | `AgenteAduanaClass.php:318-471` | High |
| Agente seguro mantiene maestro, relacion cliente, contactos, documentos y token. | `AgenteSeguroClass.php:291-440` | High |
| Gestor de transporte mantiene maestro, relacion cliente, contactos, documentos y token. | `GestorTransporteClass.php:294-440` | High |
| Gestor transporte envia correo con enlace a `formInfo.php?q={token}`. | `gestorTransporte/ajax/enviarCorreo.php:15-24`, `:386-404`, `:451-472` | High |
| Formulario externo carga gestor y documentos por token. | `gestorTransporte/formInfo.php:7-15`, `GestorTransporteClass.php:147-269` | High |
| Agente tracking es CRUD simple de agente/url por cliente y tipo de embarque. | `agenteTrackingClass.php:61-106` | Medium |

## Riesgos candidatos

- Tokens en URL sin expiracion visible.
- SQL interpolado en consultas por token y mutaciones de datos/contactos.
- Algunas bajas usan delete fisico de relacion, otras `deleted_at`, creando semantica inconsistente.
- Documentos adjuntos dependen de rutas construidas desde identificadores de tercero.
