# Document access rules

Estado: candidate_reconstruction  
Confianza: media

## Reglas candidatas

- El acceso documental depende de sesion autenticada.
- El cliente/tenant condiciona que documentos son visibles.
- Roles/permisos limitan descarga, carga, aprobacion o observacion.
- El estado del caso/documento puede habilitar o bloquear acciones.
- Los tokens de terceros deben limitar alcance, vigencia y documentos accesibles.
- Las descargas genericas deben validar ruta, ownership y tipo documental.

## Pendiente de validar

- Matriz exacta por rol, cliente y tipo documental.
- Reglas para documentos historicos.
- Comportamiento ante links caducados o tokens revocados.
