# Known operational problems

Estado: candidate_reconstruction  
Confianza: media

| Problema candidato | Impacto |
|---|---|
| Reglas dispersas entre PHP, SQL y JavaScript | Dificulta mantenimiento y pruebas |
| Tablas temporales/vistas en reporteria | Riesgo de datos inconsistentes |
| Validaciones no centralizadas | Errores y mensajes divergentes |
| Documentos en rutas historicas | Riesgo de perdida/acceso indebido |
| Integraciones sin contratos visibles | Fallos dificiles de diagnosticar |
| Variantes por cliente no documentadas | Riesgo al unificar/refactorizar |
| Procesos batch no confirmados | Riesgo de omitir automatizaciones productivas |
