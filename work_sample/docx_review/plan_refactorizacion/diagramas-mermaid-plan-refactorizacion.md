# Diagramas Mermaid - Plan de Refactorizacion de Asgard

Estado: BORRADOR DE APOYO, NO CANONICO.  
Fuente: `2026-08-10 Plan de Refactorizacion.docx`.  
Convencion: `❓` identifica decisiones pendientes de aprobacion.

## 1. Estrategia general de modernizacion

Ubicacion sugerida: despues del titulo o de la introduccion.  
Evidencia: PLAN-DOC-001, paginas 1, 4-6 y 9.

```mermaid
flowchart TB
    A["Asgard operativo<br/>Desarrollo normal continua"]

    subgraph F1["Fase 1 - Reducir riesgo tecnologico"]
        B["Crear entorno de refactorizacion"]
        C["Caracterizar comportamiento y dependencias"]
        D["Migrar incrementalmente<br/>PHP 5.x a PHP 8.5"]
        E["Validar, desplegar y preparar rollback"]
        B --> C --> D --> E
    end

    subgraph F2["Fase 2 - Reducir riesgo arquitectonico"]
        F["Separar backend y frontend"]
        G["Completar API de Clientes"]
        H["Evolucionar a monolito modular"]
        I["Evaluar dominios reales"]
        J["Extraer microservicios<br/>solo cuando aporten valor"]
        F --> G --> H --> I --> J
    end

    A --> B
    E --> F

    X["Asgard Cortex<br/>conocimiento vivo"]
    A -. "documenta" .-> X
    F1 -. "actualiza" .-> X
    F2 -. "actualiza" .-> X
```

## 2. Entorno independiente de refactorizacion

Ubicacion sugerida: seccion 1, "Crear un entorno especifico".  
Evidencia: PLAN-DOC-002, pagina 1.

```mermaid
flowchart LR
    DEV["Desarrollo habitual"] --> MAIN["main"]
    MAIN --> STG["Staging habitual"]
    STG --> PROD["Produccion"]

    PROD -. "copia controlada" .-> REF["Staging de refactorizacion"]

    REF --> DB{"Base de datos de pruebas ❓"}
    DB --> DB1["Actualizar copia existente"]
    DB --> DB2["Crear instancia independiente"]

    REF --> WH["Warehouse de pruebas<br/>runtime moderno"]
    REF --> API["APIs existentes<br/>runtime moderno"]
    REF --> ID["Intercambio Documental"]

    ID --> C["Validar nombre y aislamiento<br/>de su base de datos"]
```

## 3. Desarrollo continuo mediante PR pequenos

Ubicacion sugerida: secciones 2, 5 y 6.  
Evidencia: PLAN-DOC-003, paginas 1-3.

```mermaid
flowchart LR
    A["main operativo"] --> B["Seleccionar cambio pequeno"]
    B --> C["Crear rama corta"]
    C --> D["Aplicar compatibilidad<br/>sin cambiar negocio"]
    D --> E["CI: guardarrailes y pruebas"]
    E --> F{"Resultado"}

    F -->|"FAIL"| C
    F -->|"PASS"| G["Revision y PR"]
    G --> H["Merge a main"]
    H --> I["Despliegue en entorno<br/>de refactorizacion"]
    I --> J["Validacion funcional"]
    J --> B

    H --> K["Desarrollo y releases<br/>habituales continuan"]
```

## 4. Piloto de compatibilidad PHP 8.5

Ubicacion sugerida: seccion 7, "Realizar primero un piloto".  
Evidencia: PLAN-DOC-004, paginas 2-4. El bloque piloto sigue pendiente.

```mermaid
flowchart TB
    A["Elegir perimetro piloto ❓"] --> B["Inventariar entradas, salidas,<br/>datos e integraciones"]
    B --> C["Crear pruebas de caracterizacion"]
    C --> D["Activar guardarrailes"]

    D --> E["Corregir short tags"]
    E --> F["Encapsular o sustituir mysql_*"]
    F --> G["Preservar cifrado legacy con fixtures"]
    G --> H["Actualizar dependencias e incompatibilidades"]

    H --> I["Ejecutar pruebas en runtime actual"]
    I --> J["Ejecutar pruebas en PHP 8.5"]
    J --> K["Validar Warehouse, Documents y APIs"]
    K --> L{"Paridad demostrada"}

    L -->|"No"| C
    L -->|"Si"| M["PR pequeno a main"]
    M --> N["Despliegue piloto PHP 8.5"]
    N --> O["Lecciones y patron repetible"]
```

## 5. Promocion del runtime y rollback

Ubicacion sugerida: seccion 8, "Hacer el cambio de runtime".  
Evidencia: PLAN-DOC-005, pagina 4.

```mermaid
flowchart LR
    A["Entorno de refactorizacion<br/>PHP 8.5"] --> B{"Pruebas tecnicas,<br/>funcionales e integraciones"}
    B -->|"FAIL"| A
    B -->|"PASS"| C["Staging completo<br/>PHP 8.5"]

    C --> D{"Pruebas integrales<br/>y ensayo de rollback"}
    D -->|"FAIL"| C
    D -->|"PASS"| E["Produccion<br/>PHP 8.5"]

    E --> F{"Observabilidad estable"}
    F -->|"Si"| G["Cambio consolidado"]
    F -->|"Problema grave"| H["Rollback a imagen y<br/>configuracion anterior"]
    H --> I["Analizar causa y corregir"]
    I --> C
```

## 6. Coexistencia de legacy, backend y frontend modernos

Ubicacion sugerida: inicio de la segunda gran fase.  
Evidencia: PLAN-DOC-006, paginas 4-5. Laravel y Vue son candidatos razonables, pero la seleccion formal sigue pendiente.

```mermaid
flowchart LR
    U["Usuario"] --> G["Gateway<br/>mismo dominio"]

    G -->|"Rutas no migradas"| L["Legacy PHP 8.5"]
    G -->|"/app/*"| V["Frontend moderno<br/>Vue / React / Angular ❓"]
    G -->|"/api/v1/*"| A["Backend API<br/>Laravel / API Clientes ❓"]

    V --> A
    L --> DB[("Base de datos actual")]
    A --> DB

    L --> FS[("Archivos compartidos")]
    A --> FS

    L --> EXT["Warehouse, Documents,<br/>OCR, correo y notificaciones"]
    A --> EXT

    AUTH["Identidad y permisos"] --> L
    AUTH --> A
```

## 7. Modernizacion modulo por modulo

Ubicacion sugerida: seccion 9, sustituyendo la lista vertical actual.  
Evidencia: PLAN-DOC-007, pagina 5.

```mermaid
flowchart TB
    A["Seleccionar un modulo o flujo"] --> B["Reconstruir AS-IS<br/>reglas, datos y dependencias"]
    B --> C["Crear pruebas de caracterizacion"]
    C --> D["Definir contrato API"]
    D --> E["Separar logica en backend"]
    E --> F["Construir frontend desacoplado"]
    F --> G["Ejecutar legacy y nuevo flujo<br/>en paralelo controlado"]
    G --> H{"Paridad y aceptacion"}

    H -->|"No"| B
    H -->|"Si"| I["Activar mediante feature flag"]
    I --> J["Asignar un unico escritor<br/>por caso de uso"]
    J --> K["Retirar gradualmente<br/>la implementacion legacy"]
    K --> L["Modulo dentro del<br/>monolito modular"]
    L --> M["Seleccionar siguiente modulo"]
    M --> A
```

## 8. Decision posterior sobre microservicios

Ubicacion sugerida: seccion 10, "Microservicios quedan para despues".  
Evidencia: PLAN-DOC-008, paginas 5-6. Los criterios de decision son TO-BE y requieren aprobacion.

```mermaid
flowchart TB
    A["Capacidad dentro del<br/>monolito modular"] --> B{"Limite de dominio<br/>estable y validado"}
    B -->|"No"| Z["Mantener como modulo"]
    B -->|"Si"| C{"Necesita despliegue o<br/>escalado independiente"}
    C -->|"No"| Z
    C -->|"Si"| D{"Puede controlar sus datos<br/>y contrato de integracion"}
    D -->|"No"| Z
    D -->|"Si"| E{"Beneficio supera complejidad<br/>operativa y distribuida"}
    E -->|"No"| Z
    E -->|"Si"| F{"Equipo y observabilidad<br/>preparados"}
    F -->|"No"| Z
    F -->|"Si"| G["Proponer extraccion<br/>como microservicio"]
    G --> H["Piloto, medicion y<br/>aprobacion humana"]
```

## 9. Asgard Cortex como ciclo continuo

Ubicacion sugerida: seccion 11, "Crear y mantener vivo Asgard Cortex".  
Evidencia: PLAN-DOC-009, paginas 6-7.

```mermaid
flowchart LR
    A["Codigo y esquema"] --> B["Extraccion de evidencia"]
    C["Procesos y reglas"] --> B
    D["APIs e integraciones"] --> B

    B --> E["Artefactos candidatos<br/>AS-IS y OpenSpec"]
    E --> F{"Verificacion y<br/>revision humana"}
    F -->|"FAIL"| B
    F -->|"PASS aprobado"| G["Asgard Cortex"]

    G --> H["Desarrolladores"]
    G --> I["Agentes de IA"]
    H --> J["Cambio mediante PR"]
    I --> J
    J --> A
    J --> C
    J --> D
```

## Matriz de trazabilidad

| ID | Contenido respaldado | Fuente |
| --- | --- | --- |
| PLAN-DOC-001 | Dos fases, PHP 8.5 antes de modularizacion y microservicios, Cortex paralelo | Documento, paginas 1, 4-6 y 9 |
| PLAN-DOC-002 | Entorno separado, alternativas de BD y dependencias externas | Documento, pagina 1 |
| PLAN-DOC-003 | Main activo, PR pequenos, guardarrailes y bloques controlados | Documento, paginas 1-3 |
| PLAN-DOC-004 | Piloto, caracterizacion, compatibilidad e integraciones | Documento, paginas 2-4 |
| PLAN-DOC-005 | Promocion refactorizacion, staging, produccion y rollback | Documento, pagina 4 |
| PLAN-DOC-006 | Backend API, frontend independiente y tecnologias candidatas | Documento, paginas 4-5 |
| PLAN-DOC-007 | Modernizacion progresiva modulo por modulo | Documento, pagina 5 |
| PLAN-DOC-008 | Microservicios diferidos hasta validar dominios | Documento, paginas 5-6 |
| PLAN-DOC-009 | Asgard Cortex como repositorio vivo y actualizado por incremento | Documento, paginas 6-7 |

