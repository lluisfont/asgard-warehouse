# Shared Infrastructure Coverage

Estado: IN_PROGRESS
Idioma: Spanish

Este artefacto separa ficheros con comportamiento tecnico transversal que aparecen en barridos por SQL, correo, filesystem, OCR o exportacion, pero que no representan por si mismos un dominio de negocio independiente.

## Infraestructura de reportes

| Fichero | Clasificacion | Motivo |
| --- | --- | --- |
| `index_archivos/contador.php` | Infraestructura de reporting | Ejecuta queries de reporte y prepara datos de grilla/exportacion. |
| `index_archivos/contadordetalle.php` | Infraestructura de reporting | Variante de conteo/detalle para reportes. |
| `index_archivos/reporteexcel/reporteexcel.php` | Infraestructura de exportacion | Generador Excel generico usado por multiples reportes. |
| `index_archivos/reporteexcel/reporteexcelNew.php` | Infraestructura de exportacion | Variante nueva del generador Excel generico. |
| `index_archivos/data.php` | Infraestructura de reporting | Convierte resultados en datos de grilla. |
| `index_archivos/datajson.php` | Infraestructura de reporting | Variante JSON para datos de grilla. |
| `index_archivos/dataReporteSemanal.php` | Infraestructura de reporting | Preparacion de datos para reporte semanal. |
| `index_archivos/excel.php` | Infraestructura de exportacion | Helper legacy de exportacion. |

## Infraestructura de formularios

| Fichero | Clasificacion | Motivo |
| --- | --- | --- |
| `index_archivos/js/formularios.php` | Helper UI legacy | Funciones PHP de renderizado/validacion de formularios. |
| `index_archivos/js/formulariosA.php` | Helper UI legacy | Variante de funciones de formularios. |
| `index_archivos/js/formulariosB.php` | Helper UI legacy | Variante de funciones de formularios. |
| `index_archivos/js/formulariosR.php` | Helper UI legacy | Variante de funciones de formularios. |
| `index_archivos/js/jquery.js` | Libreria UI | Dependencia JavaScript de terceros, no dominio funcional. |
| `index_archivos/js/ext-all-debug.js` | Libreria UI | Dependencia JavaScript de terceros, no dominio funcional. |
| `index_archivos/js/kendo.web.min.js` | Libreria UI | Dependencia JavaScript de terceros, no dominio funcional. |
| `index_archivos/js/scripts/knockout-2.2.1.js` | Libreria UI | Dependencia JavaScript de terceros, no dominio funcional. |
| `index_archivos/js/axios.min.js` | Libreria UI | Dependencia JavaScript de terceros, no dominio funcional. |
| `index_archivos/js/jquery.easyui.min.js` | Libreria UI | Dependencia JavaScript de terceros, no dominio funcional. |
| `index_archivos/js/ux/ajax/Simlet.js` | Libreria UI | Simulador/helper Ajax de ExtJS, no dominio funcional. |
| `index_archivos/js/ux/ajax/SimManager.js` | Libreria UI | Simulador/helper Ajax de ExtJS, no dominio funcional. |
| `index_archivos/resetpassword/js/vue.js` | Libreria UI | Dependencia JavaScript de terceros para reset de password. |
| `index_archivos/resetpassword/js/vee-validate.js` | Libreria UI | Dependencia JavaScript de terceros para validacion UI. |
| `index_archivos/tracking/js/magnific-popup.js` | Libreria UI | Dependencia JavaScript de terceros para modal/popup. |

## Infraestructura transversal

| Fichero | Clasificacion | Motivo |
| --- | --- | --- |
| `index_archivos/cnfdb105.php` | Configuracion/conexion | Bootstrap de base de datos y constantes. |
| `index_archivos/cnfdb105F.php` | Configuracion/conexion | Variante de conexion/configuracion. |
| `index_archivos/cnfdbimcruz.php` | Configuracion/conexion | Variante de conexion/configuracion Imcruz. |
| `index_archivos/MailClass.php` | Helper de correo | Servicio transversal usado por flujos documentales/notificaciones. |
| `index_archivos/OCRClass.php` | Helper OCR | Servicio transversal usado por lecturas OCR especificas. |
| `index_archivos/servicioNotificaciones/index.php` | Bootstrap/modulo UI | Entrada del modulo de notificaciones cubierto por `realtime-notification-center`. |
| `index_archivos/download.php` | Helper de descarga | Descarga archivo desde `FILES_PATH` y parametros de ruta/nombre. |
| `index_archivos/email.php` | Helper/test de correo | Envio SMTP legacy/hardcoded, no dominio independiente. |
| `index_archivos/logsAsgard.php` | Helper de auditoria | Soporte transversal de logs. |
| `index_archivos/MenuClass.php` | Helper de menu/permisos | Construccion de navegacion ASGARD. |
| `dav_clientereportescliente` | Tabla de permisos/reportes | Relaciona reportes habilitados por cliente; soporte transversal de menu/permisos. |
| `index_archivos/menu.php` | Layout/navegacion | Menu legacy. |
| `index_archivos/menu_b4.php` | Layout/navegacion | Menu Bootstrap 4. |
| `index_archivos/menubs4.php` | Layout/navegacion | Menu Bootstrap 4 alternativo. |
| `index_archivos/principal.php` | Landing operativa | Pantalla principal post-login. |
| `index_archivos/permisos.php` | Control de sesion/permisos | Bootstrap transversal de permisos. |
| `index_archivos/logout.php` | Control de sesion | Cierre de sesion. |
| `index_archivos/samesesion.php` | Control de sesion | Verificacion de sesion activa. |
| `index_archivos/encodeUser.php` | Helper de identidad | Codificacion de datos de usuario. |
| `index_archivos/php-jwt-5.2.0/src/JWT.php` | Libreria de identidad | Dependencia JWT de terceros usada por autenticacion. |

## Catalogos y filtros raiz

| Fichero | Clasificacion | Motivo |
| --- | --- | --- |
| `index_archivos/aduanas.php` | Catalogo/filtro | Selector de aduanas. |
| `index_archivos/agenciasaduana.php` | Catalogo/filtro | Selector de agencias/agentes. |
| `index_archivos/ciudades.php` | Catalogo/filtro | Selector de ciudades. |
| `index_archivos/coordinadores.php` | Catalogo/filtro | Selector de coordinadores. |
| `index_archivos/empresas.php` | Catalogo/filtro | Selector de empresas. |
| `index_archivos/entidadtramites.php` | Catalogo/filtro | Selector de entidades/tramites. |
| `index_archivos/estadosedp.php` | Catalogo/filtro | Selector de estados EDP. |
| `index_archivos/estadosform1edp.php` | Catalogo/filtro | Selector de estados Form1 EDP. |
| `index_archivos/etapastramites.php` | Catalogo/filtro | Selector de etapas de tramite. |
| `index_archivos/lineas.php` | Catalogo/filtro | Selector de lineas cliente. |
| `index_archivos/logis_estados.php` | Catalogo/filtro | Selector de estados logisticos. |
| `index_archivos/operadores.php` | Catalogo/filtro | Selector de operadores. |
| `index_archivos/productoproveedor_json.php` | Catalogo/filtro | Endpoint JSON de producto/proveedor. |
| `index_archivos/proveedores.php` | Catalogo/filtro | Selector de proveedores. |
| `index_archivos/proveedorporcliente_json.php` | Catalogo/filtro | Endpoint JSON de proveedor por cliente. |
| `index_archivos/regimenes.php` | Catalogo/filtro | Selector de regimenes. |
| `index_archivos/parametros/tiemposTeoricos/ProveedorMercancia.php` | Catalogo/filtro | Helper de paises/localidades para parametros de tiempos teoricos. |
| `index_archivos/parametros/usuarioFirmante/ajax/codigo-paises.php` | Catalogo/filtro | Endpoint auxiliar de codigos/paises para usuario firmante. |
| `dav_paises_codigo_telefonico` | Catalogo/filtro | Catalogo de codigos telefonicos por pais para usuario firmante/contacto. |
| `index_archivos/boton.php` | Helper UI | Boton comun de reportes/formularios. |
| `index_archivos/fecha.php` | Helper UI | Selector fecha. |
| `index_archivos/fechas.php` | Helper UI | Selector rango de fechas. |
| `index_archivos/fechas2.php` | Helper UI | Variante selector rango de fechas. |
| `index_archivos/fechasabierto.php` | Helper UI | Variante selector fechas abiertas. |
| `index_archivos/fechasretro.php` | Helper UI | Selector fechas retrospectivo. |
| `index_archivos/fechasretrolimit.php` | Helper UI | Selector fechas retrospectivo limitado. |
| `index_archivos/footer.php` | Layout | Pie de pagina legacy. |
| `index_archivos/footerbs4.php` | Layout | Pie de pagina Bootstrap 4. |

## Rutas funcionales enlazadas a dominios

| Fichero | Dominio |
| --- | --- |
| `index_archivos/asesoria-gestion/views/solicitud.php` | `advisory-management-services` |
| `index_archivos/parametros/agenteAduana/ajax/enviarCorreo.php` | `third-party-token-document-onboarding` |
| `index_archivos/parametros/control_certificaciones/ajax/control-certificado.php` | `certification-expiry-control` |
| `index_archivos/intercambioDocumental/js/datosIntercambio.js` | `document-exchange-ocr` |
| `index_archivos/intercambioDocumentalV2/js/commons.js` | `document-exchange-ocr` |
| `index_archivos/parametros/control_certificaciones/js/index.js` | `certification-expiry-control` |
