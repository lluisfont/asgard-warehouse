# Evidence Map - vehicle-soat-pdf-ocr-splitting

Estado: INFERRED_DRAFT_REVIEW_REQUIRED

| Afirmacion | Evidencia | Confianza |
| --- | --- | --- |
| El endpoint consulta metadatos de intercambio documental y caso previo. | `dividir-pdf-soat.php:20-34` | High |
| Solo dos ids documentales observados habilitan procesamiento. | `dividir-pdf-soat.php:37` | High |
| Un documento se divide por mitades y otro se copia directo. | `dividir-pdf-soat.php:39-69`, `:105-109` | High |
| OCR se ejecuta con `MODELO_CPBTE_SOAT`. | `dividir-pdf-soat.php:126-127` | High |
| Se extraen campos motor, chasis, comprobante, factura y roseta. | `dividir-pdf-soat.php:71-92`, `:145-160` | High |
| Se generan PDFs individuales en `/datadrive1/temporales/soat/{chasis}.pdf`. | `dividir-pdf-soat.php:186-207` | High |

## Riesgos candidatos

- No se observa persistencia estructurada ni asociacion automatica de PDFs generados a documentos/casos.
- Dependencia de Ghostscript por `exec`.
- Salidas temporales por chasis pueden colisionar si se procesa el mismo chasis en paralelo.
- El parsing OCR asume estructura de tabla/campos concreta.
