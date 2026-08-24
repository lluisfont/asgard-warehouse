# Legacy evidence map

Estado: candidate_reconstruction

| Evidencia | Uso en baseline |
|---|---|
| Inventario determinista | Alcance de ficheros, componentes y rutas |
| `.data_base/asgard.sql` | Diccionario fisico, tablas, columnas, catalogos y relaciones candidatas |
| `EVIDENCE_INDEX.jsonl` | Referencias PHP-directas a tablas, endpoints y mutaciones |
| Graphify | Grafo de codigo, comunidades, dependencias y cobertura estructural |
| Dominios reconstruidos | Procesos, reglas, datos usados, flujos y estados candidatos |
| Auditorias de cobertura | Residuales de componentes, SQL writes y tablas |
| Informes de seguridad/arquitectura/datos | Contexto tecnico AS-IS para cambios OpenSpec |
| Segunda pasada semantica por flujos | Cruce dominio-tabla-campo-regla-evidencia para validar uso funcional real |

## Regla de evidencia

Toda afirmacion funcional inferida permanece pendiente de validar hasta confirmacion humana o prueba de caracterizacion.
