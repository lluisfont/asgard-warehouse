# Executive PowerBI Dashboard Portal - Process Flow

## Estado

INFERRED_DRAFT_REVIEW_REQUIRED

## Flujo A - Abrir dashboard embebido

1. El usuario selecciona un dashboard desde el menu ASGARD.
2. ASGARD carga configuracion, sesion y permisos.
3. ASGARD renderiza breadcrumb y contenedor de pantalla.
4. ASGARD inserta iframe Power BI correspondiente.
5. Power BI carga el reporte publicado o embebido.
6. El usuario consulta el tablero dentro de ASGARD.

## Flujo B - Consultar dashboard por cliente/tema

1. El usuario abre una variante especifica de dashboard.
2. ASGARD carga la URL fija asociada a ese archivo.
3. Power BI muestra el reporte del cliente o tema.
4. El usuario navega/interactua con el tablero segun capacidades Power BI.

## Flujo C - Consultar indicadores locales relacionados

1. El usuario abre reporte local de indicadores.
2. ASGARD presenta filtros y tabla detallada.
3. ASGARD calcula/consulta indicadores operativos locales.
4. El usuario exporta o revisa detalle, fuera del dashboard Power BI.
