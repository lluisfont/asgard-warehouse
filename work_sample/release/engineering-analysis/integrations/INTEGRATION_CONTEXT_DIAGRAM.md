# Integration Context Diagram

Estado: INFERRED_DRAFT_REVIEW_REQUIRED
Idioma: Spanish

```mermaid
flowchart LR
  ASGARD["ASGARD PHP"] --> MAIL["SendGrid / SMTP"]
  ASGARD --> PUSH["Pusher"]
  ASGARD --> OCR["OCR / Form Recognizer"]
  ASGARD --> SSH["SFTP / SSH"]
  ASGARD --> API["ASESORIA_GESTION_API / Atlantes"]
  Browser["Browser"] --> PBI["Power BI"]
  Browser --> FSVC["Freshservice"]
  ASGARD --> GEO["ip-api.com"]
```
