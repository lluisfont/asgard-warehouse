# Module Dependency Map

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

## Dependencias dominantes

- Casi todos los modulos PHP dependen de `cnfdb105.php` para DB, constantes, autoload y Pusher.
- Pantallas protegidas dependen de `permisos.php`.
- Vistas/PHP legacy incluyen menus/layouts y librerias UI.
- Controladores de negocio acceden directamente a MySQL y filesystem.
- OCR/documentos dependen de `MailClass.php`, `OCRClass.php`, constantes OCR y rutas de ficheros.
- Logistica y operativos comparten tablas `dav_*`, `logis_*`, `tck_*`, `ages_*` y reportes.

## Grafo Graphify

| Metrica | Valor |
| --- | ---: |
| Nodos | 32896 |
| Aristas | 53235 |
| Comunidades | 4161 |
| Extraccion | 99% extracted |
| Commit grafo | `cad2cda9` |

## Riesgo estructural

La arquitectura tiene acoplamiento fuerte por includes globales, SQL directo y tablas compartidas. El limite de modulo es organizativo, no una barrera tecnica fuerte.
