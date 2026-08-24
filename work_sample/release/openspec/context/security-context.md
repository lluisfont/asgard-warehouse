# Security context

Estado: candidate_reconstruction  
Fuente: engineering-analysis/security

Riesgos principales identificados:

- SQL directo con interpolacion en rutas legacy.
- Secretos hardcodeados o no gestionados centralmente.
- MFA/token payloads que requieren revisar firma, vigencia y binding a usuario.
- Descargas/cargas documentales sensibles.
- Autorizacion distribuida por sesion, permisos, cliente y SQL manual.
- Datos personales en usuarios/contactos/terceros.
- Integraciones externas con credenciales y fallback no formalizados.

## Regla OpenSpec

Los cambios de seguridad deben preservar compatibilidad funcional mientras cierran bypasses de permisos, tenant isolation y exposicion documental.
