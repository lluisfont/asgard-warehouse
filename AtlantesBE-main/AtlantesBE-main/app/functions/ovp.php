<?php
class OVP{
    public function __construct() {
        $this->logOVP = new logOVP();
    }

    public function agregarpago($idpagos,$estado1,$ciudadovp,$clienteovp,$idusuario,$conexion){
        $outNroAsignacion=0;
        $result = $conexion->query("select outNroAsignacion FROM t_facturapago WHERE idfacturapago=$idpagos;");
        while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
            $outNroAsignacion=(int)$row['outNroAsignacion'];
        }
        
        if($outNroAsignacion==0){
            $dt = new DateTime();

            //Desglosado de los costos
            $lista = array();

            $resultPagoDetalle = $conexion->prepare("SELECT 
                        t_costo.idcosto,
                        t_concepto.idconcepto,
                        t_concepto.concepto,
                        t_costo.cantidad,
                        t_costo.notas,
                        ROUND(t_costo.monto*t_tipocambio.tipocambio,2) as monto,
                        ROUND(t_costo.cantidad*t_costo.monto*t_tipocambio.tipocambio,2) as subtotal,
                        ROUND(t_costo.monto*t_tipocambio.tipocambio*(IF(t_facturapago.iddivisa=2,6.96,1)),2) as montobs,
                        ROUND(t_costo.cantidad*t_costo.monto*t_tipocambio.tipocambio*(IF(t_facturapago.iddivisa=2,6.96,1)),2) as subtotalbs,
                        t_facturapago.iddivisa,
                        t_facturapago.idtipofacturapago,
                        t_facturapago.idembarque,
                        t_divisa.codigo as divisa
                        FROM
                        t_costo
                        LEFT JOIN t_concepto ON t_costo.idconcepto=t_concepto.idconcepto
                        LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
                        LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND t_facturapago.iddivisa=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_facturapago.fecha)
                        LEFT JOIN t_divisa ON t_facturapago.iddivisa=t_divisa.iddivisa
                        WHERE IFNULL(t_costo.idtipofacturanotadebito,0)=1
                        AND t_facturapago.idfacturapago=".$idpagos."
                        ORDER BY t_costo.cantidad*t_costo.monto*t_tipocambio.tipocambio DESC;");
            $resultPagoDetalle->execute();
            while ($rs =  $resultPagoDetalle ->fetch(PDO::FETCH_ASSOC)){
                array_push($lista, $rs);
            }
            $idconcepto = $lista[0]["idconcepto"];

            $listakey = 0;
            $DetallepPagos=array();
            foreach ($lista as $row) {
                if ($listakey == 0){
                    $resultExtra = $conexion->prepare("select
                                CONCAT(t_ordenservicioi.numero,'/',t_ordenservicioi.gestion) as numeroi,
                                ROUND(SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio),2) as monto,
                                ROUND(SUM(t_cargo.monto*t_cargo.cantidad*t_tipocambio.tipocambio*(IF(t_cargo.iddivisa=2,6.96,1))),2) as montobs
                                FROM
                                t_ordenservicioi
                                LEFT JOIN t_cargo ON t_ordenservicioi.idordenservicioi=t_cargo.idordenservicioi
                                LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND ".$row["iddivisa"]."=t_tipocambio.iddivisadestino AND t_ordenservicioi.fecha BETWEEN t_tipocambio.fechainicio AND IFNULL(t_tipocambio.fechafin,t_ordenservicioi.fecha)
                                WHERE
                                t_ordenservicioi.idembarque=".$row["idembarque"]."
                                AND t_ordenservicioi.idestado=1
                                GROUP BY
                                t_ordenservicioi.numero,
                                t_ordenservicioi.gestion;");
                    $resultExtra->execute();

                    if (($resultExtra->rowCount()) > 0){
                        $datosExtra = $resultExtra->fetch(PDO::FETCH_ASSOC);
                        $DetallepPagos[]=array(
                            'Descripcion' => $row["concepto"],
                            'Monto' => $row["subtotalbs"]-$datosExtra["montobs"]
                        );
                    }else{
                        $DetallepPagos[]=array(
                            'Descripcion' => $row["concepto"],
                            'Monto' => $row["subtotalbs"]
                        );
                    }


                    $listakey++;
                }else{
                    $DetallepPagos[]=array(
                        'Descripcion' => $row["concepto"],
                        'Monto' => $row["subtotalbs"]
                    );
                }
            }

            $resultPago = $conexion->prepare("SELECT 
                t_facturapago.idfacturapago,
                t_facturapago.idtipofacturapago,
                t_tipofacturapago.tipofacturapago,
                CONCAT(tipofacturapago,' ',t_facturapago.numerofactura,'/',t_facturapago.gestion,' ',t_embarque.embarque) AS Descripcion,
                t_facturapago.fecha,
                CONCAT(t_facturapago.numerofactura,'/',t_facturapago.gestion) as NroOrdenRGI,
                t_embarque.embarque,
                CASE ifnull(t_embarque.idtipoultimoconsignatario,0)
                        WHEN 1 THEN t_clienteconsignatario.idcliente
                        WHEN 2 THEN 0 -- t_proveedorconsignatario.proveedor
                        WHEN 3 THEN 0 -- t_prestadorconsignatario.prestador
                        WHEN 5 THEN 0 -- t_agentecargaconsignatario.agentecarga
                        ELSE 0
                END as idcliente,
                CASE ifnull(t_embarque.idtipoultimoconsignatario,0)
                        WHEN 1 THEN t_clienteconsignatario.id_OVP
                        WHEN 2 THEN 0 -- t_proveedorconsignatario.proveedor
                        WHEN 3 THEN 0 -- t_prestadorconsignatario.prestador
                        WHEN 5 THEN 0 -- t_agentecargaconsignatario.agentecarga
                        ELSE 0
                END as IdCliente_OVP,
                CASE ifnull(t_embarque.idtipoultimoconsignatario,0)
                        WHEN 1 THEN t_clienteconsignatario.cliente
                        WHEN 2 THEN CONCAT(t_proveedorconsignatario.proveedor,' (X)')
                        WHEN 3 THEN CONCAT(t_prestadorconsignatario.prestador,' (X)')
                        WHEN 5 THEN CONCAT(t_agentecargaconsignatario.agentecarga,' (X)')
                        ELSE 'Sin Dato'
                END as consignatario,
                CASE t_facturapago.idpagaratipo
                    WHEN 1 THEN 0 -- porque no es necesario en la validacion
                    WHEN 2 THEN 0
                    WHEN 3 THEN t_prestador.idprestador
                    WHEN 4 THEN 0
                    WHEN 5 THEN 0
                END as idproveedor,
                CASE t_facturapago.idpagaratipo
                    WHEN 1 THEN 0 -- en 0 porque no hay casos en el historico y no hay codigos OVP
                    WHEN 2 THEN t_proveedor.id_OVPProv
                    WHEN 3 THEN t_prestador.id_OVPProv
                    WHEN 4 THEN t_transportista.id_OVPProv
                    WHEN 5 THEN t_agentecarga.id_OVPProv
                END as id_OVPProv,
                t_facturapago.iddivisa,
                CASE t_facturapago.tipoop
                WHEN 1 THEN 'COSTO'
                WHEN 2 THEN 'CARGO'
                END as tipoop,
                t_facturapago.outNroAsignacion,
                t_facturapago.errorOVP,
                t_costo.idcosto,
                t_concepto.concepto,
                t_concepto.id_OVPRef IdReferencia,
                SUM(t_costo.cantidad) AS cantidad,
                SUM(ROUND(t_costo.monto*t_tipocambio.tipocambio,2)) as subtotal,
                SUM(ROUND(t_costo.cantidad*t_costo.monto*t_tipocambio.tipocambio,2)) as Monto,
                SUM(ROUND(t_costo.monto*t_tipocambio.tipocambio*(IF(t_facturapago.iddivisa=2,6.96,1)),2)) as subtotalbs,
                SUM(ROUND(t_costo.cantidad*t_costo.monto*t_tipocambio.tipocambio*(IF(t_facturapago.iddivisa=2,6.96,1)),2)) as Montobs,
                t_divisa.codigo as divisa
                FROM
                t_costo
                LEFT JOIN t_concepto ON ".$idconcepto."=t_concepto.idconcepto
                LEFT JOIN t_facturapago ON t_costo.idfacturanotadebito=t_facturapago.idfacturapago
                LEFT JOIN t_tipofacturapago ON t_facturapago.idtipofacturapago=t_tipofacturapago.idtipofacturapago
                LEFT JOIN t_embarque ON t_facturapago.idembarque=t_embarque.idembarque
                LEFT JOIN t_cliente as t_clienteconsignatario ON t_embarque.idultimoconsignatario=t_clienteconsignatario.idcliente
                LEFT JOIN t_proveedor as t_proveedorconsignatario ON t_embarque.idultimoconsignatario=t_proveedorconsignatario.idproveedor
                LEFT JOIN t_prestador as t_prestadorconsignatario ON t_embarque.idultimoconsignatario=t_prestadorconsignatario.idprestador
                LEFT JOIN t_agentecarga as t_agentecargaconsignatario ON t_embarque.idultimoconsignatario=t_agentecargaconsignatario.idagentecarga
                LEFT JOIN t_cliente as t_clientepagara ON t_facturapago.idpagara=t_clientepagara.idcliente
                LEFT JOIN t_proveedor ON t_facturapago.idpagara=t_proveedor.idproveedor
                LEFT JOIN t_prestador ON t_facturapago.idpagara=t_prestador.idprestador
                LEFT JOIN t_transportista ON t_facturapago.idpagara=t_transportista.idtransportista
                LEFT JOIN t_agentecarga ON t_facturapago.idpagara=t_agentecarga.idagentecarga
                LEFT JOIN t_tipocambio ON t_costo.iddivisa=t_tipocambio.iddivisaorigen AND t_facturapago.iddivisa=t_tipocambio.iddivisadestino AND t_facturapago.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_facturapago.fecha)
                LEFT JOIN t_divisa ON t_facturapago.iddivisa=t_divisa.iddivisa
                WHERE IFNULL(t_costo.idtipofacturanotadebito,0)=1
                AND t_facturapago.idfacturapago=".$idpagos."
                ORDER BY t_costo.cantidad*t_costo.monto*t_tipocambio.tipocambio DESC;");

            $resultPago->execute();

            if (($resultPago->rowCount()) > 0 && $estado1=="ovppago"){
                while ($rowOVP =  $resultPago ->fetch(PDO::FETCH_ASSOC)){
                    //$dt =  new DateTime($rowOVP["fecha"] . " " .date('H:i:s', time()));
                    if((int)ADDSUBTIME == 1){
                        $dt->add(new DateInterval('PT' . OVPTIME . 'M'));
                    }elseif((int)ADDSUBTIME == 0){
                        $dt->sub(new DateInterval('PT' . OVPTIME . 'M'));
                    }else{
                        die('NO MIGRO DATOS, ERROR.');
                    }

                    $numeroPago = $rowOVP["embarque"];

                    $parametros=array();
                    $parametros['CodigoSeguridad']=codigoSeguridadOVP;
                    $parametros['login']=userOVP;
                    $parametros['pwd']=passOVP;
                    $parametros['BaseDeDatos']=$ciudadovp;
                    $parametros['PCTransaccion']=$idusuario;
                    $parametros['Moneda']="Bolivianos";
                    $parametros['Fecha']=$dt->format('c');
                    $parametros['TipoDeCuenta']="Clientes";
                    $parametros['IdEmpleado']=0;
                    $parametros['Descripcion']=$rowOVP["Descripcion"];
                    $parametros['codigoReferencia']=$rowOVP["IdReferencia"];
                    if (!empty($datosExtra)) {
                        $parametros['Monto'] = $rowOVP["Montobs"] - $datosExtra["montobs"]; // USD: 6.96
                    }else{
                        $parametros['Monto'] = $rowOVP["Montobs"]; // USD: 6.96
                    }
                    $parametros['IdTipoValorOrigen']=($rowOVP["idtipofacturapago"]==1 ? 208 : 209); //(ID que inician en 2** (203)) // CTA DE TERCEROS // CAMBIAR EN PROD POR EL QUE CORRESPONDE
                    $parametros['IdTipoValorFondosARendir']=107; //$rowOVP["IdTipoValorFondosARendir"]; // DESPACHOS EN TRANSITO SLG
                    $parametros['Numero']="0";
                    $parametros['Tarjeta']="0";
                    $parametros['Vencimiento']=$dt->format('c');
                    //$parametros['PermiteExceder']="false";
                    $parametros['NroOrdenRGI']=$numeroPago;
                    $parametros['IdCliente']=$rowOVP["IdCliente_OVP"];
                    $parametros['codigoExterno']=$numeroPago;
                    $parametros['usaCtaCteProveedor']="True";//PARA LOS PAGOS QUE SE PLANILLAN ANTES
                    if ($rowOVP["idproveedor"] == 31 && $rowOVP["idcliente"] == 644){
                        $rowOVP["id_OVPProv"] = 50;
                    } elseif ($rowOVP["idproveedor"] == 44 && $rowOVP["idcliente"] == 644){
                        $rowOVP["id_OVPProv"] = 48;
                    }
                    $parametros['IdProveedor']=$rowOVP["id_OVPProv"];//LISTA DE CONCEPTOS QUE SE USAN EN PLANILLAS (EJ: CAINCO, CNC, SLG, ETC.)
                    $parametros['ListaDetalleAsignacion']=$DetallepPagos;

                    $result = $clienteovp->RGI_GrabarAsignacion($parametros);

                    if($result->RGI_GrabarAsignacionResult==1){
                        $outNroAsignacion=$result->respuestaServicio->NroAsignacion;
                        $mensajeerror='';
                        $migrado=true;
                        $query = ("UPDATE t_facturapago SET outNroAsignacion='$outNroAsignacion', errorOVP=NULL WHERE idfacturapago=$idpagos;");
                        $resultupdateOVP = $conexion->exec($query);

                        $datolog=$this->logOVP->saveLog($parametros,$query,"agregarFactPago","t_facturapago","UPDATE",$result,$idusuario,$conexion);
                    }else{
                        $outNroAsignacion='';
                        $migrado=false;
                        $mensajeerror=$result->msgError;
                        $mensajeerror = trim($mensajeerror);
                        $mensajeerror= str_replace("'", "", $mensajeerror);
                        $mensajeerror= str_replace("\"", "", $mensajeerror);
                        //$mensajeerror = stripslashes($mensajeerror);
                        $mensajeerror = htmlspecialchars($mensajeerror);

                        $query = ("UPDATE t_facturapago SET errorOVP='$mensajeerror' WHERE idfacturapago=$idpagos;");
                        $resultupdateOVP = $conexion->exec($query);
                        $datolog=$this->logOVP->saveLog($parametros,$query,"errorAgregarFactPago","t_facturapago","UPDATE",$result,$idusuario,$conexion);
                    }
                }
            }else{
                $outNroAsignacion='';
                $migrado=false;
                $mensajeerror="No hay valores para guardar en la OVP.";
                $datolog='';
            }
        }else{
            $outNroAsignacion='';
            $migrado=false;
            $mensajeerror="La OP ya fue migrada previamente, actualice la pagina para verificar";
            $datolog='';
        }
        
        
            

        $respuesta=array(
                'log'=>$datolog,
                'facturapago'=>$query,
                'outNroAsignacion'=>$outNroAsignacion,
                'mensajeerror'=>$mensajeerror,
                'migrado'=>$migrado
            );

            return $respuesta;

    }



    public function agregarfacturaventa($idfacturaplanilla,$nro,$estado1,$ciudadovp,$clienteovp,$idusuario,$conexion){
        $respuesta=[];


        $dt = new DateTime();

        //START --- DATOS DE LA FACTURA

        $resultOVP = $conexion->prepare("SELECT
	t_factura.idfactura,
	t_factura.idembarque,
	t_cliente.idcliente,
	t_factura.iddosificacion,
	t_factura.nit,
	t_factura.nombre as cliente,
	t_factura.fecha,
	t_factura.nrofactura,
	t_factura.codigocontrol,
	t_factura.idcobraratipo,
	t_factura.idestadofactura,
	t_cargo.idcargo,
	t_cargo.idconcepto,
	t_concepto.id_OVP,
	t_concepto.concepto,
	t_cargo.notas,
	t_cargo.monto*t_tipocambio.tipocambio as monto,
	t_cargo.cantidad*t_cargo.monto*t_tipocambio.tipocambio as subtotal,
	t_cliente.direccion,
	t_cliente.telefono,
	t_cliente.email
FROM
	t_factura
	LEFT JOIN t_cargo ON t_factura.idfactura = t_cargo.idfacturanotadebito
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha)
	LEFT JOIN t_embarque ON t_factura.idembarque = t_embarque.idembarque
	LEFT JOIN t_cliente ON t_embarque.idcliente = t_cliente.idcliente
	LEFT JOIN t_estadofactura ON t_factura.idestadofactura = t_estadofactura.idestadofactura 
WHERE
	t_factura.idfactura = ".$idfacturaplanilla.";");

        $resultOVP->execute();

        $ovpDetalle = $conexion->prepare("SELECT
	t_factura.idfactura,
	t_factura.idembarque,
	t_cliente.idcliente,
	t_factura.iddosificacion,
	t_factura.nit,
	t_factura.nombre as cliente,
	t_factura.fecha,
	t_factura.nrofactura,
	t_factura.codigocontrol,
	t_factura.idcobraratipo,
	t_factura.idestadofactura,
	t_cargo.idcargo,
	t_cargo.idconcepto,
	t_concepto.concepto,
	CASE t_factura.idestadofactura WHEN 2 THEN 0 ELSE valorfacturado ( t_factura.idfactura ) END AS monto,
	t_cliente.direccion,
	t_cliente.telefono,
	t_cliente.email,
	t_cliente.cliente AS nombre,
	t_cliente.id_OVP AS id_OVP
FROM
	t_factura
	LEFT JOIN t_cargo ON t_factura.idfactura = t_cargo.idfacturanotadebito
	LEFT JOIN t_embarque ON t_factura.idembarque = t_embarque.idembarque
	LEFT JOIN t_cliente ON t_embarque.idcliente = t_cliente.idcliente
	LEFT JOIN t_estadofactura ON t_factura.idestadofactura = t_estadofactura.idestadofactura
	LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
	LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha)
WHERE
	t_factura.idfactura = ".$idfacturaplanilla.";");

        $ovpDetalle->execute();

        //START --- ARRAY DE LA FACTURA
        if($estado1 == "ovpfactura"){//verificar si puede guardar en ovp
            if (($resultOVP->rowCount()) > 0){
                $detalles = $ovpDetalle->fetch(PDO::FETCH_ASSOC);

                //$dt =  new DateTime($detalles["fecha"] . " " .date('H:i:s', time()));
                if((int)ADDSUBTIME == 1){
                    $dt->add(new DateInterval('PT' . OVPTIME . 'M'));
                }elseif((int)ADDSUBTIME == 0){
                    $dt->sub(new DateInterval('PT' . OVPTIME . 'M'));
                }else{
                    die('NO MIGRO DATOS, ERROR.');
                }

                //DETALLE productos
                while ($rowOVP =  $resultOVP ->fetch(PDO::FETCH_ASSOC)){
                    $DetalleProductos[]=array(
                        'Cantidad' => 1,
                        //'CantidadDos'=>0,
                        //'CantidadEnvase'=>0,
                        'Descripcion' => $rowOVP['concepto']." ".$rowOVP['notas'],
                        //'Descuento' => 0,
                        'Factor' => 1,
                        //'IdEnvase' => 1,
                        'IdProducto' => $rowOVP['id_OVP'],
                        'Importe' => $rowOVP['subtotal'],
                        //'ImporteDolar' => 11,
                        'PrecioUnitario' => $rowOVP['subtotal'],
                        //'PrecioUnitarioDolar'=>11,
                    );
                }

                $parametros=array();
                $parametros['CodigoSeguridad']=codigoSeguridadOVP;
                $parametros['login']=userOVP;
                $parametros['pwd']=passOVP;
                $parametros['BaseDeDatos']=$ciudadovp;
                $parametros['MONEDA']="Bolivianos";
                $parametros['MaquinaT']=$idusuario;
                $parametros['Datos']=array(
                    "Encabezado" =>  array(
                        array(
                            'AtributosAdcionales'=>'NULL',
                            'CodigoExterno'=>$detalles['nrofactura'],
                            'Contacto'=>$detalles['telefono'],
                            'Direccion'=>$detalles['direccion'],
                            'Fecha'=>$dt->format('c'),
                            //'FechaEntrega'=>$dt->format('c'),
                            'IdCliente'=>$detalles['id_OVP'],
                            'IdDeposito'=>1,
                            //'IdOrdenOrigen'=>"null",
                            //'IdReferencia'=>113,
                            //'IdVendedor'=>1,
                            'Monto'=>$detalles['monto'],
                            //'MontoDolar'=>11,
                            'Nit'=>$detalles['nit'],
                            //'Notas'=>'mnopqr',
                            //'Pie'=>"pieeeee",
                            'RazonSocial'=>$detalles['cliente'],
                            //'Referencia'=>"IVA",
                            'Telefono'=>$detalles['telefono'],
                        ),
                    ),
                    "DetalleProductos" => $DetalleProductos,

                    "FormadePago" =>  array(
                        array(
                            'CantidadPagos'=>1,//default
                            'Cod_Tipo_Pago'=>0,//default
                            'Descripcion'=>"null",//default
                            'FechaExpiracion'=>$dt->format('c'),
                            'FechaRecepcion'=>$dt->format('c'),
                            'IdFormaPago'=>21,
                            'Monto'=>$detalles['monto'],
                            'Nombre'=>$detalles['nombre'],
                            //'Numero'=>"null",
                            //'NumeroContrato'=>"null",
                            //'Plazo'=>3,
                            //'Tarjeta'=>"VISA",
                        ),
                    ),
                    "Lotes" =>  array(
                        //$Lotes,
                    ),
                );

                $result = $clienteovp->GC_GrabarVentasCls($parametros);

                if (is_soap_fault($result)) {
                    trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
                }

                if($result->GC_GrabarVentasClsResult==1){
                    $outNumeroFactura=$result->outNumeroFactura;
                    $outIdOrdenFactura=$result->outIdOrdenFactura;
                    $idordenovp=$outIdOrdenFactura." | ".$outNumeroFactura;
                    $mensajeerror='';
                    $migrado=true;
                    $query = ("UPDATE t_factura SET outIdOrdenFactura ='".$result->outIdOrdenFactura."',outNumeroFactura ='".$result->outNumeroFactura."',outCodigoDeControl ='".$result->outCodigoDeControl."',outNumeroAutorizacion ='".$result->outNumeroAutorizacion."', errorOVPFact=NULL WHERE idfactura=$idfacturaplanilla AND nrofactura=$nro;");
                    $resultupdateOVP = $conexion->exec($query);
                    $datolog=$this->logOVP->saveLog($parametros,$query,"agregarfactura","t_factura","UPDATE",$result,$idusuario,$conexion);

                }else {
                    $outNumeroFactura='';
                    $outIdOrdenFactura='';
                    $idordenovp='';
                    $migrado=false;
                    $mensajeerror=$result->msgError;
                    $mensajeerror = trim($mensajeerror);
                    $mensajeerror= str_replace("'", "", $mensajeerror);
                    $mensajeerror= str_replace("\"", "", $mensajeerror);
                    //$mensajeerror = stripslashes($mensajeerror);
                    $mensajeerror = htmlspecialchars($mensajeerror);
                    $query = ("UPDATE t_factura SET outIdOrdenFactura =NULL,outNumeroFactura =NULL, errorOVPFact='".$mensajeerror."' WHERE idfactura=$idfacturaplanilla AND nrofactura=$nro;");
                    $resultupdateOVP = $conexion->exec($query);
                    $datolog=$this->logOVP->saveLog($parametros,$query,"errorAgregarfactura","t_factura","UPDATE",$result,$idusuario,$conexion);
                }

            }else{
                $outNumeroFactura='';
                $outIdOrdenFactura='';
                $idordenovp='';
                $migrado=false;
                $mensajeerror="no hay valores para guardar la FACTURA en la OVP";
                $datolog='';
                $query='';
            }

            $respuesta=array(
                'log'=>$datolog,
                'factura'=>$query,
                'outNumeroFactura'=>$outNumeroFactura,
                'outIdOrdenFactura'=>$outIdOrdenFactura,
                'idordenovp'=>$idordenovp,
                'mensajeerror'=>$mensajeerror,
                'migrado'=>$migrado
            );

            return $respuesta;

        }
        //END --- ARRAY DE LA FACTURA
    }

    public function agregarfacturaventasiat($idfacturaplanilla,$nro,$estado1,$ciudadovp,$clienteovp,$idusuario,$conexion){
        $respuesta=[];
        
        $outNumeroFactura='';
        $result = $conexion->query("select IFNULL(outNumeroFactura,'') as outNumeroFactura FROM t_factura WHERE idfactura=$idfacturaplanilla;");
        while ($row =  $result ->fetch(PDO::FETCH_ASSOC)){
            $outNumeroFactura=$row['outNumeroFactura'];
        }
        
        if($outNumeroFactura==''){
            $dt = new DateTime();

            //START --- DATOS DE LA FACTURA

            $resultOVP = $conexion->prepare("SELECT
            t_factura.idfactura,
            t_factura.idembarque,
            t_cliente.idcliente,
            t_factura.iddosificacion,
            IFNULL(t_factura.idtipodocumento,5) as idtipodocumento,
            t_factura.nit,
            t_factura.nombre as cliente,
            t_factura.fecha,
            t_factura.nrofactura,
            t_factura.codigocontrol,
            t_factura.idcobraratipo,
            t_factura.idestadofactura,
            t_cargo.idcargo,
            t_cargo.idconcepto,
            t_concepto.id_OVP,
            t_concepto.concepto,
            t_cargo.notas,
            t_cargo.monto*t_tipocambio.tipocambio as monto,
            ROUND(t_cargo.cantidad*t_cargo.monto*t_tipocambio.tipocambio,2) as subtotal,
            t_cliente.direccion,
            t_cliente.telefono,
            t_cliente.email
    FROM
            t_factura
            LEFT JOIN t_cargo ON t_factura.idfactura = t_cargo.idfacturanotadebito
            LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
            LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha)
            LEFT JOIN t_embarque ON t_factura.idembarque = t_embarque.idembarque
            LEFT JOIN t_cliente ON t_embarque.idcliente = t_cliente.idcliente
            LEFT JOIN t_estadofactura ON t_factura.idestadofactura = t_estadofactura.idestadofactura 
    WHERE
            t_factura.idfactura = ".$idfacturaplanilla.";");

            $resultOVP->execute();

            $ovpDetalle = $conexion->prepare("SELECT
            t_factura.idfactura,
            t_factura.idembarque,
            t_cliente.idcliente,
            t_factura.iddosificacion,
            IFNULL(t_factura.idtipodocumento,5) as idtipodocumento,
            t_factura.nit,
            t_factura.nombre as cliente,
            t_factura.fecha,
            t_factura.nrofactura,
            t_factura.codigocontrol,
            t_factura.idcobraratipo,
            t_factura.idestadofactura,
            t_cargo.idcargo,
            t_cargo.idconcepto,
            t_concepto.concepto,
            CASE t_factura.idestadofactura WHEN 2 THEN 0 ELSE valorfacturado ( t_factura.idfactura ) END AS monto,
            t_cliente.direccion,
            t_cliente.telefono,
            t_cliente.email,
            t_cliente.cliente AS nombre,
            t_cliente.id_OVP AS id_OVP
    FROM
            t_factura
            LEFT JOIN t_cargo ON t_factura.idfactura = t_cargo.idfacturanotadebito
            LEFT JOIN t_embarque ON t_factura.idembarque = t_embarque.idembarque
            LEFT JOIN t_cliente ON t_embarque.idcliente = t_cliente.idcliente
            LEFT JOIN t_estadofactura ON t_factura.idestadofactura = t_estadofactura.idestadofactura
            LEFT JOIN t_concepto ON t_cargo.idconcepto=t_concepto.idconcepto
            LEFT JOIN t_tipocambio ON t_cargo.iddivisa=t_tipocambio.iddivisaorigen AND 1=t_tipocambio.iddivisadestino AND t_factura.fecha BETWEEN t_tipocambio.fechainicio AND ifnull(t_tipocambio.fechafin,t_factura.fecha)
    WHERE
            t_factura.idfactura = ".$idfacturaplanilla.";");

            $ovpDetalle->execute();

            //START --- ARRAY DE LA FACTURA
            if($estado1 == "ovpfactura"){//verificar si puede guardar en ovp
                if (($resultOVP->rowCount()) > 0){
                    $detalles = $ovpDetalle->fetch(PDO::FETCH_ASSOC);

                    //$dt =  new DateTime($detalles["fecha"] . " " .date('H:i:s', time()));
                    if((int)ADDSUBTIME == 1){
                        $dt->add(new DateInterval('PT' . OVPTIME . 'M'));
                    }elseif((int)ADDSUBTIME == 0){
                        $dt->sub(new DateInterval('PT' . OVPTIME . 'M'));
                    }else{
                        die('NO MIGRO DATOS, ERROR.');
                    }

                    //DETALLE productos
                    while ($rowOVP =  $resultOVP ->fetch(PDO::FETCH_ASSOC)){
                        $DetalleProductos[]=array(
                            'Cantidad' => 1,
                            'CantidadDos'=>0,
                            'CantidadEnvase'=>0,
                            'Descripcion' => $rowOVP['concepto'],
                            'Descuento' => 0,
                            'Factor' => 1,
                            'IdEnvase' => null,
                            'IdProducto' => $rowOVP['id_OVP'],
                            'Importe' => $rowOVP['subtotal'],
                            'ImporteDolar' => 0,
                            'PrecioUnitario' => $rowOVP['subtotal'],
                            'PrecioUnitarioDolar'=>0,
                        );
                    }

                    $parametros=array();
                    $parametros['autenticacionServicio']=array(
                        'codigoDeSeguridad'=>codigoSeguridadOVP
                    );
                    $parametros['autenticacionSistema']=array(
                        'baseDeDatos'=>$ciudadovp,
                        'equipoTransaccion'=>$idusuario,
                        'idSucursal'=>0,
                        'password'=>userOVP,
                        'usuario'=>passOVP
                    );
                    $parametros['MONEDA']="Bolivianos";
                    $parametros['Datos']=array(
                        "DetalleProductos" => $DetalleProductos,
                        "Encabezado" =>  array(
                            array(
                                'AtributosAdcionales'=>array (),
                                'CelularTigoMoney'=>null,
                                'CodigoExterno'=>null, //LUIS LOAYZA
                                'Contacto'=>$detalles['telefono'],
                                'Cotizacion'=>0,
                                'Direccion'=>$detalles['direccion'],
                                'EmailComprador'=>null,
                                'Fecha'=>$dt->format('c'),
                                'FechaEntrega'=>$dt->format('c'),
                                'IdAmbiente'=>null,
                                'IdCliente'=>$detalles['id_OVP'],
                                'IdDeposito'=>1,
                                'IdOrdenOrigen'=>null,
                                'IdReferencia'=>null,
                                'IdVendedor'=>0,
                                'Latitud'=>0,
                                'Longitud'=>0,
                                'Monto'=>$detalles['monto'],
                                'MontoDolar'=>0,
                                'Nit'=>$detalles['nit'],
                                'Notas'=>null,
                                'OperadorTransaccion'=>null,
                                'OtrosCargos'=>0,
                                'Pie'=>null,
                                'PorcenDesGlobal'=>0,
                                'RazonSocial'=>$detalles['cliente'],
                                'Referencia'=>null,
                                'Telefono'=>$detalles['telefono'],
                                'TipoEntrega'=>null,
                                'TipoF'=>null,
                            ),
                        ),
                        "EncabezadoSiat"=>array(
                            "CAFC"=>null,
                            "codigoDocumentoSector"=>1,
                            "codigoMetodoPago"=>1, //AL CONTADO
                            "codigoTipoDocumentoIdentidad"=>$detalles['idtipodocumento'], //NIT
                            "complementoDocumentoIdentidad"=>null,
                            "enviarCorreoCliente"=>false,
                            "excepcion"=>false,
                            "montoGift"=>0,
                            "tarjetaOfuscadaDerecha"=>null,
                            "tarjetaOfuscadaIzquierda"=>null,
                        ),
                        "FormadePago" =>  array(
                            array(
                                'CantidadPagos'=>1,//default
                                'Cod_Tipo_Pago'=>0,//default
                                'CodigoComprobanteBanco'=>null,
                                'Descripcion'=>null,//default
                                'FechaExpiracion'=>$dt->format('c'),
                                'FechaRecepcion'=>$dt->format('c'),
                                'IdFormaPago'=>21,
                                'Monto'=>$detalles['monto'],
                                'Nombre'=>$detalles['nombre'],
                                'Numero'=>null,
                                'NumeroContrato'=>null,
                                'Plazo'=>0,
                                'Tarjeta'=>null
                            ),
                        ),
                        "Lotes" =>  array(
                            //$Lotes,
                        ),
                        "ReporteAAdjuntar"=>null
                    );

                    $result = $clienteovp->GC_SIAT_GrabarCompraVenta($parametros);

                    if (is_soap_fault($result)) {
                        trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
                    }

                    if($result->GC_SIAT_GrabarCompraVentaResult==1){
                        $outNumeroFactura=$result->respuestaDocumento->numeroFactura;
                        $outIdOrdenFactura=$result->respuestaDocumento->idOrdenFactura;
                        $cuf=$result->respuestaDocumento->CUF;
                        $urlDocumento=$result->respuestaDocumento->urlDocumento;
                        $NombreDocumentoPDF=$result->respuestaDocumento->NombreDocumentoPDF;
                        $NombreDocumentoXML=$result->respuestaDocumento->NombreDocumentoXML;
                        $DocumentoXML=utf8_encode($result->respuestaDocumento->DocumentoXML);
                        $codigoEmision=$result->respuestaDocumento->codigoEmision;
                        $idordenovp=$outIdOrdenFactura." | ".$outNumeroFactura;
                        $mensajeerror='';
                        $migrado=true;
                        $query = ("UPDATE t_factura SET outIdOrdenFactura ='$outIdOrdenFactura',
                                outNumeroFactura ='$outIdOrdenFactura',
                                cuf='$cuf',
                                urlDocumento='$urlDocumento',
                                NombreDocumentoPDF='$NombreDocumentoPDF',
                                NombreDocumentoXML='$NombreDocumentoXML',
                                DocumentoXML='$DocumentoXML',
                                nrofactura='$outNumeroFactura',
                                codigoEmision='$codigoEmision',
                                errorOVPFact=NULL 
                                WHERE 
                                idfactura=$idfacturaplanilla AND nrofactura=$nro;");
                        $resultupdateOVP = $conexion->exec($query);

                        $resultXML = $conexion->prepare("select DocumentoXML FROM t_factura WHERE idfactura=$idfacturaplanilla;");
                        $resultXML->execute();
                        while ($rsXML =  $resultXML ->fetch(PDO::FETCH_ASSOC)){
                            $xml = simplexml_load_string($rsXML["DocumentoXML"]);
                            //convert into json
                            $json  = json_encode($xml);
                            //convert into associative array
                            $xmlArr = json_decode($json, true);

                            $leyenda=$xmlArr["cabecera"]["leyenda"];
                            $cufd=$xmlArr["cabecera"]["cufd"];
                            $municipio=$xmlArr["cabecera"]["municipio"];
                            $telefono=$xmlArr["cabecera"]["telefono"];
                            $direccion=$xmlArr["cabecera"]["direccion"];
                            $fechaemision=$xmlArr["cabecera"]["fechaEmision"];
                            $fechaemision_split= explode("T", $fechaemision);
                            $fecha=$fechaemision_split[0];
                            $hora=$fechaemision_split[1];

                            $resultupdateOVP2 = $conexion->exec("UPDATE t_factura SET fecha='$fecha', hora='$hora', leyenda='$leyenda', cufd='$cufd', municipio='$municipio', telefono='$telefono', direccion='$direccion' WHERE idfactura=$idfacturaplanilla;");

                        }


                        $datolog=$this->logOVP->saveLog($parametros,$query,"agregarfactura","t_factura","UPDATE",$result,$idusuario,$conexion);

                    }else {
                        $outNumeroFactura='';
                        $outIdOrdenFactura='';
                        $idordenovp='';
                        $migrado=false;
                        $mensajeerror=$result->respuestaDocumento->msgError;
                        $mensajeerror = trim($mensajeerror);
                        $mensajeerror= str_replace("'", "", $mensajeerror);
                        $mensajeerror= str_replace("\"", "", $mensajeerror);
                        //$mensajeerror = stripslashes($mensajeerror);
                        $mensajeerror = htmlspecialchars($mensajeerror);
                        $query = ("UPDATE t_factura SET outIdOrdenFactura =NULL,outNumeroFactura =NULL, errorOVPFact='".$mensajeerror."' WHERE idfactura=$idfacturaplanilla AND nrofactura=$nro;");
                        $resultupdateOVP = $conexion->exec($query);
                        $datolog=$this->logOVP->saveLog($parametros,$query,"errorAgregarfactura","t_factura","UPDATE",$result,$idusuario,$conexion);
                    }

                }else{
                    $outNumeroFactura='';
                    $outIdOrdenFactura='';
                    $idordenovp='';
                    $migrado=false;
                    $mensajeerror="no hay valores para guardar la FACTURA en la OVP";
                    $datolog='';
                    $query='';
                }

                



            }
        }else{
            $outNumeroFactura='';
            $outIdOrdenFactura='';
            $idordenovp='';
            $migrado=false;
            $mensajeerror="La factura ya fue migrada, actualice la pagina para verificar";
            $datolog='';
            $query='';
        }

        $respuesta=array(
            'log'=>$datolog,
            'factura'=>$query,
            'outNumeroFactura'=>$outNumeroFactura,
            'outIdOrdenFactura'=>$outIdOrdenFactura,
            'idordenovp'=>$idordenovp,
            'mensajeerror'=>$mensajeerror,
            'DocumentoXML'=>$DocumentoXML,
            'cuf'=>$cuf,
            'migrado'=>$migrado
        );
            
        
        return $respuesta;
        
        //END --- ARRAY DE LA FACTURA
    }

    public function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    /*
    public function agregarpagoaterceros($idpagosdetalle,$idpagos,$tipo,$nro,$estado1,$ciudadovp,$clienteovp){
        $dt = new DateTime();
        if ($_SESSION["idciudad"] == 4) {
            $idovp = "";
        } elseif ($_SESSION["idciudad"] == 11) {
            $idovp = "SC";
        }else{
            die("Error inesperado al definir la sucursal.");
        }

        if ($tipo == 3) {//PAGOS A TERCEROS PARA PLANILLAS
            $resultPago = mysql_query("SELECT
                dav_pagosdetalle.idpagosdetalle,
                dav_pagosdetalle.idpagos,
                dav_facturaplanilla.fecha,
                dav_casos.idcasos,
                dav_casos.carpeta as NroOrdenRGI,
                dav_cliente.id_OVP" . $idovp . " as IdCliente,
                dav_pagosdetalle.idfacturaplanilla,
                dav_concepto.idconcepto,
                CONCAT('FC: '," . $nro . ",'; Por concepto de: ',dav_concepto.descripcion) as Descripcion,
                dav_concepto.id_OVP" . $idovp . " as IdReferencia,
                dav_concepto.idprov_OVP" . $idovp . " as IdProveedor,
                (SELECT id_OVP" . $idovp . " FROM dav_banco WHERE cuenta='DETP' LIMIT 1) as IdTipoValorFondosARendir,
                203 as IdTipoValorOrigen,
                dav_pagosdetalle.monto as Monto,
                dav_pagosdetalle.nro as numero,
                dav_pagosdetalle.prepagado
                FROM dav_pagosdetalle
                LEFT JOIN dav_facturaplanilla ON dav_pagosdetalle.idcasos=dav_facturaplanilla.idcasos
                LEFT JOIN dav_concepto ON dav_pagosdetalle.idconcepto=dav_concepto.idconcepto
                LEFT JOIN dav_casos ON dav_pagosdetalle.idcasos=dav_casos.idcasos
                LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
                WHERE idpagosdetalle=" . $idpagosdetalle . ";");

        } elseif ($tipo == 4) {//PAGOS A TERCEROS PARA NOTAS DE DEBITO
            $resultPago = mysql_query("SELECT
                dav_notasdebitodetalle.idnotasdebitodetalle as idpagosdetalle,
                dav_notasdebitodetalle.idnotasdebito as idpagos,
                dav_notasdebito.fecha,
                dav_notasdebito.idcasos,
                dav_casos.carpeta as NroOrdenRGI,
                dav_cliente.id_OVP" . $idovp . " as IdCliente,
                concat(dav_tiponotasdebito.tiponotasdebito,': ',dav_notasdebito.numero,'/',dav_notasdebito.gestion,' | ',dav_concepto.descripcion) as Descripcion,
                dav_concepto.id_OVP" . $idovp . " as IdReferencia,
                dav_concepto.idprov_OVP" . $idovp . " as IdProveedor,
                (SELECT id_OVP" . $idovp . " FROM dav_banco WHERE cuenta='DETP' LIMIT 1) as IdTipoValorFondosARendir,
                CONCAT(dav_notasdebito.numero,'/',dav_notasdebito.gestion) AS numero,
                ROUND (dav_notasdebitodetalle.monto,2) as Monto
                FROM dav_notasdebitodetalle
                LEFT JOIN dav_notasdebito ON dav_notasdebitodetalle.idnotasdebito=dav_notasdebito.idnotasdebito
                LEFT JOIN dav_casos ON dav_notasdebito.idcasos=dav_casos.idcasos
                LEFT JOIN dav_concepto ON dav_notasdebitodetalle.idconcepto=dav_concepto.idconcepto
                LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
                LEFT JOIN dav_tiponotasdebito ON dav_notasdebito.idtiponotasdebito=dav_tiponotasdebito.idtiponotasdebito
                WHERE dav_notasdebitodetalle.idnotasdebitodetalle=" . $idpagosdetalle . ";");
        } elseif ($tipo == 5) {//PAGOS A TERCEROS EN BASE AL BANCO
            $resultTributo = mysql_query("SELECT idpagos,numero,dav_pagos.idciudad,dav_pagos.tributos,dav_banco.banco
                FROM dav_pagos
                LEFT JOIN dav_banco ON dav_pagos.idbanco=dav_banco.idbanco
                WHERE idpagos=".$idpagos.";");
            $tributo = mysql_fetch_assoc($resultTributo);

            if ($tributo["tributos"] == 1){
                $resultPagoCarpeta = mysql_query("SELECT idpagosdetalle,idpagos,idcasos,idconcepto,monto,mostrarprepagado FROM dav_pagosdetalle WHERE idpagosdetalle=".$idpagosdetalle);
                $pagoCaso = mysql_fetch_assoc($resultPagoCarpeta);

                $resultPagoDetalle = mysql_query("SELECT
                    dav_pagosdetalle.idpagosdetalle,
                    dav_pagosdetalle.idcasos,
                    dav_concepto.descripcion as Referencia,
                    dav_pagosdetalle.monto as Monto
                    FROM
                    dav_pagosdetalle
                    LEFT JOIN dav_pagos ON dav_pagosdetalle.idpagos=dav_pagos.idpagos
                    LEFT JOIN dav_casos ON dav_pagosdetalle.idcasos=dav_casos.idcasos
                    LEFT JOIN dav_concepto ON dav_pagosdetalle.idconcepto=dav_concepto.idconcepto
                    LEFT JOIN dav_banco ON dav_pagos.idbanco=dav_banco.idbanco
                    LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
                    WHERE
                    dav_pagosdetalle.idpagos=".$idpagos."
                    AND dav_pagosdetalle.idcasos=".$pagoCaso["idcasos"].";");
            }

            $resultPago = mysql_query("SELECT
                dav_pagosdetalle.idpagosdetalle,
                dav_pagosdetalle.idpagos,
                dav_pagos.fecha,
                dav_casos.idcasos,
                dav_casos.carpeta as NroOrdenRGI,
                dav_cliente.id_OVP" . $idovp . " as IdCliente,
                dav_pagosdetalle.idfacturaplanilla,
                dav_concepto.idconcepto,
                CONCAT('FC: '," . $nro . ",'; Por concepto de: ',dav_concepto.descripcion) as Descripcion,
                dav_concepto.id_OVP" . $idovp . " as IdReferencia,
                IF(dav_pagos.idbanco=22,51,IF(dav_pagos.idbanco=23,54,IF(dav_pagos.idbanco=44,49,IF(dav_pagos.idbanco=43,(SELECT idprov_OVP" . $idovp . " FROM dav_concepto WHERE concepto='PSADA' LIMIT 1),".($_SESSION["idciudad"]==11 ? 65 : 0).")))) as IdProveedor,
                (SELECT id_OVP" . $idovp . " FROM dav_banco WHERE cuenta='DETP' LIMIT 1) as IdTipoValorFondosARendir,
                IF(dav_pagos.idbanco=43,211,".(strpos($tributo["banco"], "SUCURSAL LP") === FALSE ? 203 : 211).") as IdTipoValorOrigen,
                ".($tributo["tributos"] == 1 ?
                    "SUM(dav_pagosdetalle.monto) as Monto," :
                    "(dav_pagosdetalle.monto) as Monto,"
                )."
                dav_pagos.numero as numero,
                dav_pagosdetalle.prepagado
                FROM dav_pagosdetalle
                LEFT JOIN dav_facturaplanilla ON dav_pagosdetalle.idcasos=dav_facturaplanilla.idcasos
                LEFT JOIN dav_casos ON dav_pagosdetalle.idcasos=dav_casos.idcasos
                LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
                LEFT JOIN dav_pagos ON dav_pagosdetalle.idpagos=dav_pagos.idpagos
                LEFT JOIN dav_concepto ON ".($tributo["tributos"] == 1 ?
                    "dav_pagos.idconceptoOVP=dav_concepto.idconcepto" :
                    "dav_pagosdetalle.idconcepto=dav_concepto.idconcepto"
                )."
                LEFT JOIN dav_banco ON dav_pagos.idbanco=dav_banco.idbanco
                WHERE ".($tributo["tributos"] == 1 ?
                    "dav_pagos.idpagos=" . $idpagos . " AND dav_casos.idcasos=" . $pagoCaso["idcasos"] :
                    "dav_pagosdetalle.idpagosdetalle=" . $idpagosdetalle
                )."
                ;");
        }

        if (mysql_num_rows($resultPago) > 0 && $estado1=="ovpterceros"){
            while($rowOVP = mysql_fetch_array($resultPago)){
                //$dt =  new DateTime($rowOVP["fecha"] . " " .date('H:i:s', time()));
                if((int)ADDSUBTIME == 1){
                    $dt->add(new DateInterval('PT' . OVPTIME . 'M'));
                }elseif((int)ADDSUBTIME == 0){
                    $dt->sub(new DateInterval('PT' . OVPTIME . 'M'));
                }else{
                    die('NO MIGRO DATOS, ERROR.');
                }
                if ($tributo["tributos"] == 1) {
                    while ($rowOVP2 = mysql_fetch_array($resultPagoDetalle)) {//detalle del pago - lista de conceptos
                        $DetallepPagos[] = array(
                            'Descripcion' => $rowOVP2["Referencia"],
                            'Monto' => $rowOVP2["Monto"]
                        );
                    }
                }

                $parametros=array();
                $parametros['CodigoSeguridad']=codigoSeguridadOVP;
                $parametros['login']=userOVP;
                $parametros['pwd']=passOVP;
                $parametros['BaseDeDatos']=$ciudadovp;
                $parametros['PCTransaccion']=$_SESSION["idusuario"];
                $parametros['Moneda']="Bolivianos";
                $parametros['Fecha']=$dt->format('c');
                $parametros['TipoDeCuenta']="Clientes";
                $parametros['IdEmpleado']=0;
                $parametros['Descripcion']=$rowOVP["Descripcion"]; // PONER EL NUMERO DE PLANILLA y CONCEPTO PARA IDENTIFICAR
                $parametros['codigoReferencia']=$rowOVP["IdReferencia"];
                $parametros['Monto']=$rowOVP["Monto"];
                $parametros['IdTipoValorOrigen']=$rowOVP["IdTipoValorOrigen"];
                $parametros['IdTipoValorFondosARendir']=$rowOVP["IdTipoValorFondosARendir"];
                $parametros['Numero']="0";
                $parametros['Tarjeta'] = "NULL";
                $parametros['Vencimiento']=$dt->format('c');
                //$parametros['PermiteExceder']="false";
                $parametros['NroOrdenRGI']=$rowOVP["NroOrdenRGI"];
                $parametros['IdCliente']=$rowOVP["IdCliente"];
                $parametros['codigoExterno']=$rowOVP["numero"];//DEFINIMOS EL NUMERO DE PLANILLA PARA IDENFITICAR EN OVP
                $parametros['usaCtaCteProveedor']="True";//PARA LOS PAGOS QUE SE PLANILLAN ANTES
                $parametros['IdProveedor']=$rowOVP["IdProveedor"];//LISTA DE CONCEPTOS QUE SE USAN EN PLANILLAS (EJ: CAINCO, CNC, SLG, ETC.)
                $parametros['ListaDetalleAsignacion']=($tributo["tributos"] == 1 ? $DetallepPagos : "NULL");

                $result = $clienteovp->RGI_GrabarAsignacion($parametros);

                if($result->RGI_GrabarAsignacionResult==1){
                    if ($tributo["tributos"] == 1) {
                        $searchpago = mysql_query("SELECT idpagosovp from dav_pagosovp where idpagos=" . $idpagos . " AND idcasos=" . $rowOVP["idcasos"] . ";");
                    }else{
                        $searchpago = mysql_query("SELECT idpagosovp from dav_pagosovp where idpagosdetalle=".$idpagosdetalle." AND idcasos=".$rowOVP["idcasos"].";");
                    }

                    if (mysql_num_rows($searchpago) > 0) {//actualizar datos del pago OVP ya registrado
                        $spago = mysql_fetch_assoc($searchpago);
                        $query = ("UPDATE dav_pagosovp SET outNroAsignacion='".$result->respuestaServicio->NroAsignacion."', IdOrdenIFP='".$result->respuestaServicio->IdOrden."', respuestaOVP='".json_encode($result->respuestaServicio)."', tipo=".$tipo.", errorOVP=NULL WHERE idpagosovp=".$spago["idpagosovp"].";");
                        $resultupdateOVP = mysql_query($query);
                        $this->logOVP->saveLog($parametros,$query,"agregarPagoProveedorTipo".$tipo,"dav_pagosovp","UPDATE",$result);
                    }else{//insertar datos del pago OVP
                        $query = ("INSERT INTO dav_pagosovp (idcasos, tipo, outNroAsignacion, IdOrdenIFP, respuestaOVP, errorOVP ".($tributo["tributos"] == 1 ? ", idpagos" : ", idpagosdetalle").") VALUES (".$rowOVP["idcasos"].", ".$tipo.", '".$result->respuestaServicio->NroAsignacion."','".$result->respuestaServicio->IdOrden."','".json_encode($result->respuestaServicio)."', NULL ".($tributo["tributos"] == 1 ? (", ".$idpagos) : (", ".$idpagosdetalle)).");");
                        $resultupdateOVP = mysql_query($query);
                        $this->logOVP->saveLog($parametros,$query,"agregarPagoProveedorTipo".$tipo,"dav_pagosovp","INSERT",$result);
                    }

                    ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>&Eacute;xito!</h4>
                        <?= "El pago <b>".$result->respuestaServicio->NroAsignacion."</b> se ha registrado en la OVP." ;?>
                    </div>
                    <?
                }else{
                    if ($tributo["tributos"] == 1) {
                        $searchpago = mysql_query("SELECT idpagosovp from dav_pagosovp where idpagos=" . $idpagos . " AND idcasos=" . $rowOVP["idcasos"] . ";");
                    }else{
                        $searchpago = mysql_query("SELECT idpagosovp from dav_pagosovp where idpagosdetalle=".$idpagosdetalle." AND idcasos=".$rowOVP["idcasos"].";");
                    }

                    if (mysql_num_rows($searchpago) > 0) {//actualizar datos del pago OVP ya registrado
                        $spago = mysql_fetch_assoc($searchpago);
                        $query = ("UPDATE dav_pagosovp SET errorOVP='".mysql_real_escape_string($result->msgError)."' WHERE idpagosovp=".$spago["idpagosovp"].";");
                        $resultupdateOVP = mysql_query($query);
                        $this->logOVP->saveLog($parametros,$query,"errorAgregarPagoProveedorTipo".$tipo,"dav_pagosovp","UPDATE",$result);
                    }else{//insertar datos del pago OVP
                        $query = ("INSERT INTO dav_pagosovp (idcasos, tipo, respuestaOVP, errorOVP ".($tributo["tributos"] == 1 ? ", idpagos" : ", idpagosdetalle").") VALUES (".$idpagosdetalle.",".$rowOVP["idcasos"].", ".$tipo.", '".json_encode($result->respuestaServicio)."', '".mysql_real_escape_string($result->msgError)."' ".($tributo["tributos"] == 1 ? (", ".$idpagos) : (", ".$idpagosdetalle)).");");
                        $resultupdateOVP = mysql_query($query);
                        $this->logOVP->saveLog($parametros,$query,"errorAgregarPagoProveedorTipo".$tipo,"dav_pagosovp","INSERT",$result);
                    }

                    ?>
                    <div class="alert alert-block">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>Advertencia!</h4>
                        <?= "El pago no fue guardado en OVP, <b>".$result->msgError."</b>." ;?>
                    </div>
                    <?
                }
            }
        }else{
            ?>
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Advertencia!</h4>
                <?= "No hay valores para guardar en la OVP." ;?>
            </div>
            <?
        }

    }
    */
    /*
    public function agregarresagados($idpagos,$tipo,$estado1,$ciudadovp,$clienteovp){
        $dt = new DateTime();
        if ($_SESSION["idciudad"] == 4) {
            $idovp = "";
        } elseif ($_SESSION["idciudad"] == 11) {
            $idovp = "SC";
        }else{
            die("Error inesperado al definir la sucursal.");
        }

        if ($tipo == 4) {//PAGOS A TERCEROS PARA NOTAS DE DEBITO
            $resultPago = mysql_query("SELECT
                dav_notasdebito.idtiponotasdebito,
                concat(dav_tiponotasdebito.tiponotasdebito,': ',dav_notasdebito.numero,'/',dav_notasdebito.gestion,' | ',dav_concepto.descripcion) as Descripcion,
                dav_notasdebito.idnotasdebito as idpagos,
                dav_notasdebito.idcasos,
                dav_notasdebito.fecha,
                dav_casos.carpeta as NroOrdenRGI,
                dav_cliente.id_OVP" . $idovp . " as IdCliente,
                dav_notasdebito.glosa,
                dav_tiponotasdebito.tiponotasdebito,
                sum(dav_notasdebitodetalle.monto) as Monto,
                dav_concepto.id_OVP" . $idovp . " as IdReferencia,
                (SELECT idprov_OVP" . $idovp . " FROM dav_concepto WHERE concepto='OCPND' LIMIT 1) as IdProveedor,
                203 as IdTipoValorOrigen,
                (SELECT id_OVP" . $idovp . " FROM dav_banco WHERE cuenta='DETP' LIMIT 1) as IdTipoValorFondosARendir,
                202 as IdTipoValorDestino,
                CONCAT(dav_notasdebito.numero,'/',dav_notasdebito.gestion) AS numero
                FROM
                dav_notasdebito
                LEFT JOIN dav_casos ON dav_notasdebito.idcasos=dav_casos.idcasos
                LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
                LEFT JOIN dav_tiponotasdebito ON dav_notasdebito.idtiponotasdebito=dav_tiponotasdebito.idtiponotasdebito
                LEFT JOIN dav_notasdebitodetalle ON dav_notasdebito.idnotasdebito=dav_notasdebitodetalle.idnotasdebito
                LEFT JOIN dav_concepto ON dav_notasdebitodetalle.idconcepto=dav_concepto.idconcepto
                WHERE dav_notasdebito.idnotasdebito=".$idpagos.";");

            $resultPagoDetalle = mysql_query("SELECT
                dav_notasdebitodetalle.idnotasdebitodetalle,
                dav_concepto.descripcion as Referencia,
                dav_notasdebitodetalle.monto as Monto
                FROM
                dav_notasdebitodetalle
                LEFT JOIN dav_notasdebito ON dav_notasdebitodetalle.idnotasdebito=dav_notasdebito.idnotasdebito
                LEFT JOIN dav_casos ON dav_notasdebito.idcasos=dav_casos.idcasos
                LEFT JOIN dav_concepto ON dav_notasdebitodetalle.idconcepto=dav_concepto.idconcepto
                LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
                WHERE
                dav_notasdebito.idnotasdebito=".$idpagos.";");
        }

        if (mysql_num_rows($resultPago) > 0 && $estado1=="ovppagoresagado"){

            $resultConceptoPlanilla = mysql_query("SELECT idconcepto,concepto,descripcion,id_OVP".$idovp." as id_OVP from dav_concepto where concepto='CCRPT';");//concepto de la planilla
            $rowConcepto = mysql_fetch_assoc($resultConceptoPlanilla);

            while($rowOVP = mysql_fetch_array($resultPagoDetalle)) {//detalle del pago - lista de conceptos
                $DetallepPagos[]=array(
                    'Descripcion' => $rowOVP["Referencia"],
                    'Monto' => $rowOVP["Monto"]
                );
            }

            while($rowOVP = mysql_fetch_array($resultPago)){
                //$dt =  new DateTime($rowOVP["fecha"] . " " .date('H:i:s', time()));
                if((int)ADDSUBTIME == 1){
                    $dt->add(new DateInterval('PT' . OVPTIME . 'M'));
                }elseif((int)ADDSUBTIME == 0){
                    $dt->sub(new DateInterval('PT' . OVPTIME . 'M'));
                }else{
                    die('NO MIGRO DATOS, ERROR.');
                }
                $parametros=array();
                $parametros['CodigoSeguridad']=codigoSeguridadOVP;
                $parametros['login']=userOVP;
                $parametros['pwd']=passOVP;
                $parametros['BaseDeDatos']=$ciudadovp;
                $parametros['PCTransaccion']=$_SESSION["idusuario"];
                $parametros['Moneda']="Bolivianos";
                $parametros['Fecha']=$dt->format('c');
                //$parametros['TipoDeCuenta']="Clientes";
                $parametros['IdEmpleado']=0;
                $parametros['Descripcion']=$rowOVP["Descripcion"]; // PONER EL NUMERO DE PLANILLA y CONCEPTO PARA IDENTIFICAR
                $parametros['codigoReferenciaAsignacion']=$rowOVP["IdReferencia"];
                $parametros['codigoReferenciaCierre']=$rowConcepto["id_OVP"];
                $parametros['Monto']=$rowOVP["Monto"];
                $parametros['IdTipoValorOrigen']=$rowOVP["IdTipoValorOrigen"];
                $parametros['IdTipoValorFondosARendir']=$rowOVP["IdTipoValorFondosARendir"];
                $parametros['IdTipoValorDestino']=$rowOVP["IdTipoValorDestino"];
                $parametros['Numero']="0";
                $parametros['Tarjeta'] = "NULL";
                $parametros['Vencimiento']=$dt->format('c');
                //$parametros['PermiteExceder']="false";
                $parametros['NroOrdenRGI']=$rowOVP["NroOrdenRGI"];
                $parametros['IdCliente']=$rowOVP["IdCliente"];
                $parametros['codigoExterno']=$rowOVP["numero"];//DEFINIMOS EL NUMERO DE PLANILLA PARA IDENFITICAR EN OVP
                $parametros['usaCtaCteProveedor']="True";//PARA LOS PAGOS QUE SE PLANILLAN ANTES
                $parametros['IdProveedor']=$rowOVP["IdProveedor"];//LISTA DE CONCEPTOS QUE SE USAN EN PLANILLAS (EJ: CAINCO, CNC, SLG, ETC.)
                $parametros['ListaDetalleAsignacion']=$DetallepPagos;

                $result = $clienteovp->RGI_GrabarResagados($parametros);

                if($result->RGI_GrabarResagadosResult==1){
                    $searchpago = mysql_query("SELECT idpagosovp from dav_pagosovp where idpagos=".$idpagos." AND idcasos=".$rowOVP["idcasos"].";");

                    if (mysql_num_rows($searchpago) > 0) {//actualizar datos del pago OVP ya registrado
                        $spago = mysql_fetch_assoc($searchpago);
                        $query = ("UPDATE dav_pagosovp SET tipo=".$tipo.", outNroAsignacion='".$result->respuestaServicioAsignacion->NroAsignacion."', IdOrdenIFP='".$result->respuestaServicioAsignacion->IdOrden."', errorOVP=NULL, respuestaOVP='".json_encode($result)."' WHERE idpagosovp=".$spago["idpagosovp"].";");
                        $resultupdateOVP = mysql_query($query);
                        $this->logOVP->saveLog($parametros,$query,"agregarRezagadoTipo".$tipo,"dav_pagosovp","UPDATE",$result);
                    }else{//insertar datos del pago OVP
                        $query = ("INSERT INTO dav_pagosovp (idpagos, idcasos, tipo, outNroAsignacion, IdOrdenIFP, respuestaOVP, errorOVP) VALUES (".$idpagos.", ".$rowOVP["idcasos"].", ".$tipo.", '".$result->respuestaServicioAsignacion->NroAsignacion."', '".$result->respuestaServicioAsignacion->IdOrden."', '".json_encode($result)."', NULL );");
                        $resultupdateOVP = mysql_query($query);
                        $this->logOVP->saveLog($parametros,$query,"agregarRezagadoTipo".$tipo,"dav_pagosovp","INSERT",$result);
                    }

                    //busca los pagos que estan registrados en OVP
                    $resultOVPdatos = mysql_query("SELECT
                        idpagosovp, idpagos, idcasos, idpagosdetalle, nroLote, outNroAsignacion, IdOrdenIFP, IdOrdenIFC, NroAsignacionFact, errorOVPFact
                        FROM dav_pagosovp
                        WHERE
                        dav_pagosovp.idcasos=" . $rowOVP["idcasos"] . "
                        AND dav_pagosovp.outNroAsignacion IS NOT NULL
                        AND (dav_pagosovp.IdOrdenIFC IS NULL OR dav_pagosovp.IdOrdenIFC = '')
                        ORDER BY dav_pagosovp.outNroAsignacion+0 ASC;
                        ");

                    $cantidad =count($result->respuestaServicioCierreCarpeta->ListaAsignaciones->AsignacionDeLaCarpetaRGI);
                    $json = json_encode($result->respuestaServicioCierreCarpeta->ListaAsignaciones->AsignacionDeLaCarpetaRGI);
                    $json = json_decode($json);
                    if ($cantidad==1){//grabar datos de planillaje en dav_pagosovp
                        while($rowOVP = mysql_fetch_array($resultOVPdatos)){
                            if ((int)$rowOVP["outNroAsignacion"] == (int)$json->NroAsignacion) {
                                $query = ("UPDATE dav_pagosovp SET IdOrdenIFC='".$json->IdOrdenIFC."', NroAsignacionFact='".$json->NroAsignacion."', errorOVPFact=NULL WHERE idpagosovp=".$rowOVP["idpagosovp"].";");
                                $resultupdateOVP = mysql_query($query);
                                $this->logOVP->saveLog(NULL,$query,"agregarRezagadoCierreTipo".$tipo,"dav_pagosovp","UPDATE",NULL);
                            }else{
                                $query = "N/A";
                                $this->logOVP->saveLog(NULL, $query, "errorAgregarRezagadoCierreTipo".$tipo, "dav_pagosovp", "UPDATE", $result);
                            }
                        }
                    }else{
                        while($rowOVP = mysql_fetch_array($resultOVPdatos)){
                            for ($i=0 ; $i<$cantidad ; $i++) {
                                if ((int)$rowOVP["outNroAsignacion"] == (int)$json[$i]->NroAsignacion) {
                                    $query = ("UPDATE dav_pagosovp SET IdOrdenIFC='".$json[$i]->IdOrdenIFC."', NroAsignacionFact='".$json[$i]->NroAsignacion."', errorOVPFact=NULL WHERE idpagosovp=".$rowOVP["idpagosovp"].";");
                                    $resultupdateOVP = mysql_query($query);
                                    $this->logOVP->saveLog(NULL, $query,"agregarRezagadoCierreTipo".$tipo,"dav_pagosovp","UPDATE",NULL);
                                }
                            }
                        }
                    }

                    ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>&Eacute;xito!</h4>
                        <?= "El registro <b>".$result->respuestaServicioAsignacion->NroAsignacion."</b> se ha registrado en la OVP." ;?>
                    </div>
                    <?
                }else{
                    $searchpago = mysql_query("SELECT idpagosovp from dav_pagosovp where idpagos=".$idpagos." AND idcasos=".$rowOVP["idcasos"].";");

                    if (mysql_num_rows($searchpago) > 0) {//actualizar datos del pago OVP ya registrado
                        $spago = mysql_fetch_assoc($searchpago);
                        $query = ("UPDATE dav_pagosovp SET errorOVP='".mysql_real_escape_string($result->msgError)."' WHERE idpagosovp=".$spago["idpagosovp"].";");
                        $resultupdateOVP = mysql_query($query);
                        $this->logOVP->saveLog($parametros,$query,"errorAgregarRezagadoTipo".$tipo,"dav_pagosovp","UPDATE",$result);
                    }else{//insertar datos del pago OVP
                        $query = ("INSERT INTO dav_pagosovp (idpagos, idcasos, tipo, respuestaOVP, errorOVP) VALUES (".$idpagos.",".$rowOVP["idcasos"].", ".$tipo.", '".json_encode($result)."', '".mysql_real_escape_string($result->msgError)."');");
                        $resultupdateOVP = mysql_query($query);
                        $this->logOVP->saveLog($parametros,$query,"errorAgregarRezagadoTipo".$tipo,"dav_pagosovp","INSERT",$result);
                    }

                    ?>
                    <div class="alert alert-block">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>Advertencia!</h4>
                        <?= "El registro no fue guardado en OVP, <b>".$result->msgError."</b>." ;?>
                    </div>
                    <?
                }
            }
        }else{
            ?>
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Advertencia!</h4>
                <?= "No hay valores para guardar en la OVP." ;?>
            </div>
            <?
        }

    }
    */
    /*
    public function aplicarpagoproveedor($idpagosdetalle,$idpagos,$tipo,$estado1,$ciudadovp,$clienteovp){
        $dt = new DateTime();
        if ($_SESSION["idciudad"] == 4) {
            $idovp = "";
        } elseif ($_SESSION["idciudad"] == 11) {
            $idovp = "SC";
        }else{
            die("Error inesperado al definir la sucursal.");
        }

        if ($tipo == "nd") {//APLICACION DE PAGOS A PROVEEDOR PARA NOTAS DE DEBITO
            $resultPago = mysql_query("SELECT
                dav_notasdebito.idtiponotasdebito,
                concat('CPBTE (ND): ',dav_notasdebito.numero,'/',dav_notasdebito.gestion,' | ',dav_concepto.descripcion) as Descripcion,
                dav_notasdebito.idnotasdebito as idpagos,
                dav_notasdebito.idcasos,
                dav_pagos.fecha,
                dav_pagos.idmetodopago,
                dav_pagos.alaordende,
                dav_pagos.cheque,
                dav_casos.carpeta as NroOrdenRGI,
                dav_cliente.id_OVP" . $idovp . " as IdCliente,
                dav_tiponotasdebito.tiponotasdebito,
                sum(dav_notasdebitodetalle.monto) as Monto,
                dav_concepto.id_OVP" . $idovp . " as IdReferencia,
                (SELECT idprov_OVP" . $idovp . " FROM dav_concepto WHERE concepto='OCPND' LIMIT 1) as IdProveedor,
                203 as CodigoFormaPago,
                (SELECT id_OVP" . $idovp . " FROM dav_banco WHERE cuenta='DETP' LIMIT 1) as IdTipoValorFondosARendir,
                dav_banco.id_OVP" . $idovp . " as IdFormaPago,
                CONCAT(dav_pagos.numero) AS numero,
                dav_pagosovp.idpagosovp,
                dav_pagosovp.outNroAsignacion,
                dav_pagosovp.errorOVP,
                dav_pagosovp.IdOrdenIFP,
                dav_pagosovp.IdOrdenIFC,
                dav_pagosovp.IdOrdenPG
                FROM
                dav_notasdebito
                LEFT JOIN dav_casos ON dav_notasdebito.idcasos=dav_casos.idcasos
                LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
                LEFT JOIN dav_tiponotasdebito ON dav_notasdebito.idtiponotasdebito=dav_tiponotasdebito.idtiponotasdebito
                LEFT JOIN dav_notasdebitodetalle ON dav_notasdebito.idnotasdebito=dav_notasdebitodetalle.idnotasdebito
                LEFT JOIN dav_pagos ON dav_notasdebitodetalle.idpagos=dav_pagos.idpagos
                LEFT JOIN dav_pagosovp ON dav_notasdebito.idnotasdebito=dav_pagosovp.idpagos
                LEFT JOIN dav_concepto ON dav_notasdebitodetalle.idconcepto=dav_concepto.idconcepto
                LEFT JOIN dav_banco ON dav_pagos.idbanco=dav_banco.idbanco
                WHERE dav_notasdebitodetalle.idnotasdebitodetalle=".$idpagosdetalle."
                AND dav_notasdebitodetalle.idpagos=".$idpagos.";");
        } elseif ($tipo == "ocp") {//APLICACION DE PAGOS A PROVEEDOR A OTROS CONCEPTOS DE PLANILLA
            $resultPago = mysql_query("SELECT
                dav_pagos.idpagos,
                dav_pagosdetalle.idpagosdetalle,
                CONCAT('CPBTE ',dav_pagos.numero,'; Por concepto de: ',dav_pagos.concepto) as Descripcion,
                dav_pagosdetalle.idcasos,
                dav_pagos.fecha,
                dav_pagos.idmetodopago,
                dav_pagos.alaordende,
                dav_pagos.cheque,
                dav_casos.carpeta as NroOrdenRGI,
                dav_cliente.id_OVP" . $idovp . " as IdCliente,
                sum(dav_pagosdetalle.monto) as Monto,
                dav_concepto.id_OVP" . $idovp . " as IdReferencia,
                (SELECT idprov_OVP" . $idovp . " FROM dav_concepto WHERE idconcepto=(SELECT idconcepto FROM dav_pagosdetalle WHERE idpagosdetalle=".$idpagosdetalle.") LIMIT 1) as IdProveedor,
                203 as CodigoFormaPago,
                (SELECT id_OVP" . $idovp . " FROM dav_banco WHERE cuenta='DETP' LIMIT 1) as IdTipoValorFondosARendir,
                dav_banco.id_OVP" . $idovp . " as IdFormaPago,
                CONCAT(dav_pagos.numero) AS numero,
                dav_pagosovp.idpagosovp,
                dav_pagosovp.outNroAsignacion,
                dav_pagosovp.errorOVP,
                dav_pagosovp.IdOrdenIFP,
                dav_pagosovp.IdOrdenIFC,
                dav_pagosovp.IdOrdenPG
                FROM
                dav_pagosdetalle
                LEFT JOIN dav_casos ON dav_pagosdetalle.idcasos=dav_casos.idcasos
                LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
                LEFT JOIN dav_pagos ON dav_pagosdetalle.idpagos=dav_pagos.idpagos
                LEFT JOIN dav_pagosovp ON dav_pagosdetalle.idpagosdetalle=dav_pagosovp.idpagosdetalle
                LEFT JOIN dav_concepto ON dav_pagosdetalle.idconcepto=dav_concepto.idconcepto
                LEFT JOIN dav_banco ON dav_pagos.idbanco=dav_banco.idbanco
                WHERE dav_pagosdetalle.idpagosdetalle=".$idpagosdetalle."
                AND dav_pagosdetalle.idpagos=".$idpagos.";");
        }

        if (mysql_num_rows($resultPago) > 0 && $estado1=="ovppagogc"){
            while($rowOVP = mysql_fetch_array($resultPago)){
                //$dt =  new DateTime($rowOVP["fecha"] . " " .date('H:i:s', time()));
                if((int)ADDSUBTIME == 1){
                    $dt->add(new DateInterval('PT' . OVPTIME . 'M'));
                }elseif((int)ADDSUBTIME == 0){
                    $dt->sub(new DateInterval('PT' . OVPTIME . 'M'));
                }else{
                    die('NO MIGRO DATOS, ERROR.');
                }
                $parametros=array();
                $parametros['autenticacionServicio']=array(
                    "codigoDeSeguridad" => codigoSeguridadOVP
                );
                $parametros['autenticacionSistema']=array(
                    "baseDeDatos" => $ciudadovp,
                    "equipoTransaccion" => "1",
                    "password" => passOVP,
                    "usuario" => userOVP,
                );
                $parametros['fechaPago']=$dt->format('c');
                $parametros['boolValidarFechaServidor']="false";//false: no verifica contra la hora del server | true: si lo hace
                $parametros['codigoProveedor']=$rowOVP["IdProveedor"];
                $parametros['cadIdOrdenFacturasAPagar']=$rowOVP["IdOrdenIFP"]."*".$rowOVP["CodigoFormaPago"]."*".$rowOVP["Monto"];
                $parametros['FormadePagoConfigurada'] =  array(
                    array(
                        'CantidadPagos'=>0,//default
                        'Cod_Tipo_Pago'=>0,//default
                        'Descripcion'=>"NULL",//default
                        'FechaExpiracion'=>$dt->format('c'),
                        'FechaRecepcion'=>$dt->format('c'),
                        'IdFormaPago'=>$rowOVP["IdFormaPago"],
                        'Monto'=>$rowOVP["Monto"],
                        'Nombre'=>$rowOVP["alaordende"],
                        'Numero'=>($rowOVP["idmetodopago"]==1 || $rowOVP["idmetodopago"]==2 ? $rowOVP["cheque"] : "NULL"),
                        'Tarjeta'=>"NULL",
                        'NumeroContrato'=>"NULL",
                        'Plazo'=>0,
                    ),
                );
                $parametros['MontoAPagar']=$rowOVP["Monto"];//"del ".$rowOVP["debitos1"]." al ".$rowOVP["debitos2"]
                $parametros['strConcepto']=$rowOVP["alaordende"];
                $parametros['Moneda']="Bolivianos";
                $parametros['strPie']="NULL";
                $parametros['strNotas']="NULL";
                $parametros['codigoReferencia']=$rowOVP["IdReferencia"];
                $parametros['strInfoAdicional']=$rowOVP["Descripcion"];
                $parametros['datosOperadorTransaccion']="NULL";
                $parametros['codigoReferenciaPagosInternet']="NULL";
                $parametros['Latitud']="NULL";
                $parametros['Longitud']="NULL";
                $parametros['codigoExterno']=$rowOVP["numero"];

                $result = $clienteovp->GC_GrabarPagoFacturasSeleccionadas($parametros);

                if($result->GC_GrabarPagoFacturasSeleccionadasResult==1){
                    $query = ("UPDATE dav_pagosovp SET IdOrdenPG='".$result->respuestaDocumentoPago->IdOrden."', respuestaPG='".json_encode($result->respuestaDocumentoPago)."', errorOVP=NULL WHERE idpagosovp=".$rowOVP["idpagosovp"].";");
                    $resultupdateOVP = mysql_query($query);
                    $this->logOVP->saveLog($parametros,$query,"agregarAplicacionPagoProveedorTipo".$tipo,"dav_pagosovp","UPDATE",$result);
                    ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>&Eacute;xito!</h4>
                        <?= "La aplicaci&oacute;n del pago <b>".$result->respuestaDocumentoPago->IdOrden."</b> se ha registrado en la OVP." ;?>
                    </div>
                    <?
                }else{
                    $query = ("UPDATE dav_pagosovp SET errorOVP='".mysql_real_escape_string($result->msgError)."' WHERE idpagosovp=".$rowOVP["idpagosovp"].";");
                    $resultupdateOVP = mysql_query($query);
                    $this->logOVP->saveLog($parametros,$query,"errorAgregarAplicacionPagoProveedorTipo".$tipo,"dav_pagosovp","UPDATE",$result);
                    ?>
                    <div class="alert alert-block">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>Advertencia!</h4>
                        <?= "El pago no fue aplicado en OVP, <b>".$result->msgError."</b>." ;?>
                    </div>
                    <?
                }
            }
        }else{
            ?>
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Advertencia!</h4>
                <?= "No hay valores para guardar en la OVP." ;?>
            </div>
            <?
        }

    }
    */
    /*
    public function agregarpagoenlote($idpagos,$estado1,$tipo,$ciudadovp,$clienteovp){
        $dt = new DateTime();
        if ($_SESSION["idciudad"] == 4) {
            $idovp = "";
        } elseif ($_SESSION["idciudad"] == 11) {
            $idovp = "SC";
        }else{
            die("Error inesperado al definir la sucursal.");
        }

        if ($tipo == 'pago'){//TIPOS DE DETALLE PAGO (Puede ser: pago,debito,anticipado)
            $resultPago = mysql_query("SELECT
            dav_pagos.idpagos,
            dav_pagos.fecha,
            dav_pagosdetalle.idpagosdetalle as idpagosdetalle,
            dav_casos.idcasos,
            dav_pagos.concepto as Concepto,
            dav_concepto.id_OVP".$idovp." as IdReferencia,
            CONCAT('CPBTE ',dav_pagos.numero,'; Por concepto de: ',dav_pagos.concepto) as Descripcion,
            dav_pagos.idmetodopago,
            dav_pagos.cheque as Tarjeta,
            dav_pagos.debitos1,
            dav_pagos.debitos2,
            sum(dav_pagosdetalle.monto) as Monto,
            dav_banco.id_OVP".$idovp." as IdTipoValor,
            (SELECT id_OVP".$idovp." FROM dav_banco WHERE cuenta='DETP' LIMIT 1) as IdTipoValorFondosARendir,
            dav_cliente.id_OVP".$idovp." as IdCliente
            FROM
            dav_pagosdetalle
            LEFT JOIN dav_pagos ON dav_pagosdetalle.idpagos=dav_pagos.idpagos
            LEFT JOIN dav_casos ON dav_pagosdetalle.idcasos=dav_casos.idcasos
            LEFT JOIN dav_concepto ON dav_pagos.idconceptoOVP=dav_concepto.idconcepto
            LEFT JOIN dav_banco ON dav_pagos.idbanco=dav_banco.idbanco
            LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
            WHERE
            dav_pagos.idpagos=".$idpagos."
            GROUP BY dav_pagos.idpagos
            ORDER BY dav_pagosdetalle.idpagosdetalle ASC;");

            $resultPagoDetalle = mysql_query("SELECT
            dav_pagosdetalle.idpagosdetalle as idpagosdetalle,
            CONCAT('CPBTE ',dav_pagos.numero,'; Por concepto de: ',dav_concepto.descripcion) as Referencia,
            dav_pagos.numero as Numero,
            dav_casos.carpeta as NroOrdenRGI,
            dav_pagosdetalle.monto as Monto,
			dav_casos.idcasos as idcasos,
            dav_cliente.id_OVP".$idovp." as IdCliente
            FROM
            dav_pagosdetalle
            LEFT JOIN dav_pagos ON dav_pagosdetalle.idpagos=dav_pagos.idpagos
            LEFT JOIN dav_casos ON dav_pagosdetalle.idcasos=dav_casos.idcasos
            LEFT JOIN dav_concepto ON dav_pagosdetalle.idconcepto=dav_concepto.idconcepto
            LEFT JOIN dav_banco ON dav_pagos.idbanco=dav_banco.idbanco
            LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
            WHERE
            dav_pagosdetalle.idpagos=".$idpagos."
            ORDER BY dav_pagosdetalle.idpagosdetalle;");

        }elseif ($tipo == 'debito'){
            $resultPago = mysql_query("SELECT
            dav_pagos.idpagos,
            dav_pagos.fecha,
            dav_notasdebitodetalle.idnotasdebitodetalle as idpagosdetalle,
            dav_casos.idcasos,
            dav_pagos.concepto as Concepto,
            dav_concepto.id_OVP".$idovp." as IdReferencia,
            CONCAT('CPBTE ',dav_pagos.numero,'; Por concepto de: ',dav_pagos.concepto) as Descripcion,
            dav_pagos.idmetodopago,
            dav_pagos.cheque as Tarjeta,
            dav_pagos.debitos1,
            dav_pagos.debitos2,
            sum(dav_notasdebitodetalle.monto) as Monto,
            dav_banco.id_OVP".$idovp." as IdTipoValor,
            (SELECT id_OVP".$idovp." FROM dav_banco WHERE cuenta='DETP' LIMIT 1) as IdTipoValorFondosARendir,
            dav_cliente.id_OVP".$idovp." as IdCliente
            FROM
            dav_notasdebitodetalle
            LEFT JOIN dav_pagos ON dav_notasdebitodetalle.idpagos=dav_pagos.idpagos
            LEFT JOIN dav_notasdebito ON dav_notasdebitodetalle.idnotasdebito=dav_notasdebito.idnotasdebito
            LEFT JOIN dav_casos ON dav_notasdebito.idcasos=dav_casos.idcasos
            LEFT JOIN dav_concepto ON dav_pagos.idconceptoOVP=dav_concepto.idconcepto
            LEFT JOIN dav_banco ON dav_pagos.idbanco=dav_banco.idbanco
            LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
            WHERE
            dav_pagos.idpagos=".$idpagos."
            GROUP BY dav_notasdebitodetalle.idpagos
			ORDER BY dav_notasdebitodetalle.idnotasdebitodetalle ASC;");

            $resultPagoDetalle = mysql_query("SELECT
            dav_notasdebitodetalle.idnotasdebitodetalle as idpagosdetalle,
            CONCAT('CPBTE ',dav_pagos.numero,'; Por concepto de: ',dav_concepto.descripcion) as Referencia,
            dav_pagos.numero as Numero,
            dav_casos.carpeta as NroOrdenRGI,
            dav_notasdebitodetalle.monto as Monto,
			dav_casos.idcasos as idcasos,
            dav_cliente.id_OVP".$idovp." as IdCliente
            FROM
            dav_notasdebitodetalle
            LEFT JOIN dav_pagos ON dav_notasdebitodetalle.idpagos=dav_pagos.idpagos
            LEFT JOIN dav_notasdebito ON dav_notasdebitodetalle.idnotasdebito=dav_notasdebito.idnotasdebito
            LEFT JOIN dav_casos ON dav_notasdebito.idcasos=dav_casos.idcasos
            LEFT JOIN dav_concepto ON dav_notasdebitodetalle.idconcepto=dav_concepto.idconcepto
            LEFT JOIN dav_banco ON dav_pagos.idbanco=dav_banco.idbanco
            LEFT JOIN dav_cliente ON dav_casos.idcliente=dav_cliente.idcliente
            WHERE
            dav_notasdebitodetalle.idpagos=".$idpagos."
            ORDER BY dav_notasdebitodetalle.idnotasdebitodetalle;");

        }elseif ($tipo == 'anticipado'){
            $resultPago = mysql_query("SELECT
            dav_pagos.idpagos,
            dav_pagos.fecha,
            dav_anticiposdevueltos.idanticiposdevueltos as idpagosdetalle,
            dav_anticipos.idanticipos as idcasos,
            dav_pagos.concepto as Concepto,
            dav_concepto.id_OVP".$idovp." as IdReferencia,
            CONCAT('CPBTE ',dav_pagos.numero,'; Por concepto de: ',dav_pagos.concepto) as Descripcion,
            dav_pagos.idmetodopago,
            dav_pagos.cheque as Tarjeta,
            dav_pagos.debitos1,
            dav_pagos.debitos2,
            sum(dav_anticiposdevueltos.monto) as Monto,
            dav_banco.id_OVP".$idovp." as IdTipoValor,
            (SELECT id_OVP".$idovp." FROM dav_banco WHERE cuenta='DETP' LIMIT 1) as IdTipoValorFondosARendir,
            dav_cliente.id_OVP".$idovp." as IdCliente
            FROM
            dav_anticiposdevueltos
            LEFT JOIN dav_pagos ON dav_anticiposdevueltos.idpagos=dav_pagos.idpagos
            LEFT JOIN dav_anticipos ON dav_anticiposdevueltos.idanticipos=dav_anticipos.idanticipos
            LEFT JOIN dav_concepto ON dav_pagos.idconceptoOVP=dav_concepto.idconcepto
            LEFT JOIN dav_banco ON dav_pagos.idbanco=dav_banco.idbanco
            LEFT JOIN dav_cliente ON dav_anticipos.idcliente=dav_cliente.idcliente
            WHERE
            dav_anticiposdevueltos.idpagos=".$idpagos."
            GROUP BY dav_anticiposdevueltos.idpagos
            ORDER BY dav_anticiposdevueltos.idanticiposdevueltos ASC;");

            $resultPagoDetalle = mysql_query("SELECT
            dav_anticiposdevueltos.idanticiposdevueltos as idpagosdetalle,
            CONCAT('CPBTE ',dav_pagos.numero,'; Nro de recibo: ',dav_anticipos.recibo) as Referencia,
            dav_pagos.numero as Numero,
            dav_anticipos.recibo as NroOrdenRGI,
            dav_anticiposdevueltos.monto as Monto,
            dav_anticipos.idanticipos as idcasos,
            dav_cliente.id_OVP".$idovp." as IdCliente
            FROM
            dav_anticiposdevueltos
            LEFT JOIN dav_pagos ON dav_anticiposdevueltos.idpagos=dav_pagos.idpagos
            LEFT JOIN dav_anticipos ON dav_anticiposdevueltos.idanticipos=dav_anticipos.idanticipos
            LEFT JOIN dav_banco ON dav_pagos.idbanco=dav_banco.idbanco
						LEFT JOIN dav_cliente ON dav_anticipos.idcliente=dav_cliente.idcliente
            WHERE
            dav_anticiposdevueltos.idpagos=".$idpagos."
            ORDER BY dav_anticiposdevueltos.idanticiposdevueltos;");
        }

        if (mysql_num_rows($resultPago) > 0 && $estado1=="ovppago"){

            //detalle del pago - lista de conceptos
            while($rowOVP = mysql_fetch_array($resultPagoDetalle)) {
                $DetallepPagos[]=array(
                    'CodigoExterno' => $rowOVP["Numero"],
                    'Descripcion' => $rowOVP["Referencia"],
                    "IdCliente" => $rowOVP["IdCliente"],
                    'ImporteExtranjero' => round(($rowOVP["Monto"]/6.96),2),
                    'ImporteLocal' => $rowOVP["Monto"],
                    'NroOrdenOCarpeta' => $rowOVP["NroOrdenRGI"],
                );
                $itemPagos[] = $rowOVP["idcasos"];
                $itemPagosDetalle[] = $rowOVP["idpagosdetalle"];
            }

            //detalle del pago
            while($rowOVP = mysql_fetch_array($resultPago)) {
                //$dt =  new DateTime($rowOVP["fecha"] . " " .date('H:i:s', time()));
                if((int)ADDSUBTIME == 1){
                    $dt->add(new DateInterval('PT' . OVPTIME . 'M'));
                }elseif((int)ADDSUBTIME == 0){
                    $dt->sub(new DateInterval('PT' . OVPTIME . 'M'));
                }else{
                    die('NO MIGRO DATOS, ERROR.');
                }
                $parametros = array(
                    'CodigoSeguridad' => codigoSeguridadOVP,
                    'login' => userOVP,
                    'pwd' => passOVP,
                    'BaseDeDatos' => $ciudadovp,
                    'PCTransaccion' => $_SESSION["idusuario"],
                    'Moneda' => "Bolivianos",
                    'Fecha' => $dt->format('c'),
                    'AsignacionesEnLote' => Array(
                        "DetalleDeAsignaciones" => $DetallepPagos,
                        "FechaVencimiento" => $dt->format('c'),
                        "IdEncargado" => "0",
                        "IdReferencia" => $rowOVP["IdReferencia"],
                        "IdTipoValorDisponible" => $rowOVP["IdTipoValor"],
                        "IdTipoValorFondosARendir" => $rowOVP["IdTipoValorFondosARendir"],
                        "Numero" => "0",
                        //"Tarjeta"=>"VISA",
                    )
                );

                $result = $clienteovp->RGI_GrabarAsignacionEnLote($parametros);

                if($result->RGI_GrabarAsignacionEnLoteResult==1){
                    $cantidad = count($result->respuestaAsignacionesGrabadas->ListaAsignacionesGrabadas->RespuestaAsignacionRGI);
                    if($cantidad == 1){
                        for ($i=0;$i<$cantidad;$i++){// agrupa todos los nros de asignacions del lote
                            $searchpago = mysql_query("SELECT idpagosovp from dav_pagosovp where idpagos=".$rowOVP["idpagos"]." AND idcasos=".$itemPagos[$i]." AND idpagosdetalle=".$itemPagosDetalle[$i].";");

                            if ($tipo == 'pago' || $tipo == 'debito' || $tipo == 'anticipado') {//TIPOS DE DETALLE PAGO (Puede ser: pago,debito,anticipado)
                                if (mysql_num_rows($searchpago) > 0) {//actualizar datos del pago OVP ya registrado
                                    $spago = mysql_fetch_assoc($searchpago);
                                    $query = ("UPDATE dav_pagosovp SET outNroAsignacion='".$result->respuestaAsignacionesGrabadas->ListaAsignacionesGrabadas->RespuestaAsignacionRGI->NroAsignacion."', nroLote=".$result->respuestaAsignacionesGrabadas->IdAsignacionLote.", errorOVP=NULL WHERE idpagosovp=".$spago["idpagosovp"].";");
                                    $resultupdateOVP = mysql_query($query);
                                    $this->logOVP->saveLog($parametros,$query,"agregarAsignacionLote","dav_pagosovp","UPDATE",$result);
                                }else{//insertar datos del pago OVP
                                    $query = ("INSERT INTO dav_pagosovp (idpagos, idpagosdetalle, idcasos, nroLote, outNroAsignacion, errorOVP) VALUES (".$rowOVP["idpagos"].", ".$itemPagosDetalle[$i].", ".$itemPagos[$i].", ".$result->respuestaAsignacionesGrabadas->IdAsignacionLote.", '".$result->respuestaAsignacionesGrabadas->ListaAsignacionesGrabadas->RespuestaAsignacionRGI->NroAsignacion."', NULL);");
                                    $resultupdateOVP = mysql_query($query);
                                    $this->logOVP->saveLog($parametros,$query,"agregarAsignacionLote","dav_pagosovp","INSERT",$result);
                                }
                            }
                        }
                    }else{
                        for ($i=0;$i<$cantidad;$i++){// agrupa todos los nros de asignacions del lote
                            $searchpago = mysql_query("SELECT idpagosovp from dav_pagosovp where idpagos=".$rowOVP["idpagos"]." AND idcasos=".$itemPagos[$i]." AND idpagosdetalle=".$itemPagosDetalle[$i].";");

                            if ($tipo == 'pago' || $tipo == 'debito' || $tipo == 'anticipado') {//TIPOS DE DETALLE PAGO (Puede ser: pago,debito,anticipado)

                                if (mysql_num_rows($searchpago) > 0) {//actualizar datos del pago OVP ya registrado
                                    $spago = mysql_fetch_assoc($searchpago);
                                    $query = ("UPDATE dav_pagosovp SET outNroAsignacion='".$result->respuestaAsignacionesGrabadas->ListaAsignacionesGrabadas->RespuestaAsignacionRGI[$i]->NroAsignacion."', nroLote=".$result->respuestaAsignacionesGrabadas->IdAsignacionLote.", errorOVP=NULL WHERE idpagosovp=".$spago["idpagosovp"].";");
                                    $resultupdateOVP = mysql_query($query);
                                    $this->logOVP->saveLog($parametros,$query,"agregarAsignacionLote","dav_pagosovp","UPDATE",$result);
                                }else{//insertar datos del pago OVP
                                    $query = ("INSERT INTO dav_pagosovp (idpagos, idpagosdetalle, idcasos, nroLote, outNroAsignacion, errorOVP) VALUES (".$rowOVP["idpagos"].", ".$itemPagosDetalle[$i].",".$itemPagos[$i].", ".$result->respuestaAsignacionesGrabadas->IdAsignacionLote.", '".$result->respuestaAsignacionesGrabadas->ListaAsignacionesGrabadas->RespuestaAsignacionRGI[$i]->NroAsignacion."', NULL);");
                                    $resultupdateOVP = mysql_query($query);
                                    $this->logOVP->saveLog($parametros,$query,"agregarAsignacionLote","dav_pagosovp","INSERT",$result);
                                }
                            }
                        }
                    }

                    ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>&Eacute;xito!</h4>
                        <?= "El lote <b>".$result->respuestaAsignacionesGrabadas->IdAsignacionLote."</b> se ha registrado en la OVP." ;?>
                    </div>
                    <?
                }else{
                    $searchpago = mysql_query("SELECT idpagosovp from dav_pagosovp where idpagos=".$rowOVP["idpagos"].";");

                    if (mysql_num_rows($searchpago) > 0) {//actualizar datos del pago OVP ya registrado
                        $spago = mysql_fetch_assoc($searchpago);
                        $query = ("UPDATE dav_pagosovp SET errorOVP='".mysql_real_escape_string($result->msgError)."' WHERE idpagosovp=".$spago["idpagosovp"].";");
                        $resultupdateOVP = mysql_query($query);
                        $this->logOVP->saveLog($parametros,$query,"errorAgregarLote","dav_pagosovp","UPDATE",$result);
                    }else{//insertar datos del pago OVP
                        $query = ("INSERT INTO dav_pagosovp (idpagos, idpagosdetalle, idcasos, errorOVP) VALUES (".$rowOVP["idpagos"].", ".$rowOVP["idpagosdetalle"].",".$rowOVP["idcasos"].", '".mysql_real_escape_string($result->msgError)."');");
                        $resultupdateOVP = mysql_query($query);
                        $this->logOVP->saveLog($parametros,$query,"errorAgregarLote","dav_pagosovp","INSERT",$result);
                    }

                    ?>
                    <div class="alert alert-block">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h4>Advertencia!</h4>
                        <?= "El pago no fue guardado en OVP, <b>".$result->msgError."</b>." ;?>
                    </div>
                    <?
                }
            }

        }else{
            ?>
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Advertencia!</h4>
                <?= "No hay valores para guardar en la OVP." ;?>
            </div>
            <?
        }
    }
    */
    /*
    public function agregarplanillaje($idcasos,$idpagos,$nro,$tipo,$estado2,$ciudadovp,$clienteovp){
        $dt = new DateTime();

        if ($_SESSION["idciudad"] == 4) {
            $idovp = "";
        } elseif ($_SESSION["idciudad"] == 11) {
            $idovp = "SC";
        }else{
            die("Error inesperado al definir la sucursal.");
        }

        //START --- DATOS PARA EL CIERRE O PLANILLAJE
        if ($tipo == 4) {//cerrar NDs
            $resultOVPcierre = mysql_query("SELECT dav_casos.idcasos,dav_casos.carpeta,dav_casos.idusuario,dav_cliente.idcliente,dav_cliente.id_OVP" . $idovp . " as id_OVP,dav_notasdebito.textoadjunto as adjuntos,dav_notasdebito.fecha
            FROM dav_casos
            LEFT JOIN dav_cliente ON dav_cliente.idcliente=dav_casos.idcliente
            LEFT JOIN dav_notasdebito ON dav_casos.idcasos=dav_notasdebito.idcasos
            WHERE dav_casos.idcasos=" . $idcasos . ";");
        } else{//cerrar pagos tradicionales
            $resultOVPcierre = mysql_query("SELECT dav_casos.idcasos,dav_casos.carpeta,dav_casos.idusuario,dav_cliente.idcliente,dav_cliente.id_OVP" . $idovp . " as id_OVP,dav_facturaplanilla.adjuntos,dav_facturaplanilla.fecha
            FROM dav_casos
            LEFT JOIN dav_cliente ON dav_cliente.idcliente=dav_casos.idcliente
            LEFT JOIN dav_facturaplanilla ON dav_facturaplanilla.idcasos=dav_casos.idcasos
            WHERE dav_casos.idcasos=" . $idcasos . ";");
        }
        //END --- DATOS PARA EL CIERRE O PLANILLAJE

        $resultConceptoPlanilla = mysql_query("SELECT idconcepto,concepto,descripcion,id_OVP".$idovp." as id_OVP from dav_concepto where concepto='CCRPT';");//concepto de la planilla
        $rowConcepto = mysql_fetch_assoc($resultConceptoPlanilla);

        //START --- ARRAY DEL PLANILLAJE
        if($estado2 == "ovpplanillaje"){//verificar si puede guardar en ovp
            if (mysql_num_rows($resultOVPcierre) > 0){
                while($rowOVP = mysql_fetch_array($resultOVPcierre)){
                    //$dt =  new DateTime($rowOVP["fecha"] . " " .date('H:i:s', time()));
                    if((int)ADDSUBTIME == 1){
                        $dt->add(new DateInterval('PT' . OVPTIME . 'M'));
                    }elseif((int)ADDSUBTIME == 0){
                        $dt->sub(new DateInterval('PT' . OVPTIME . 'M'));
                    }else{
                        die('NO MIGRO DATOS, ERROR.');
                    }
                    $parametros=array();
                    $parametros['CodigoSeguridad']=codigoSeguridadOVP;
                    $parametros['login']=userOVP;
                    $parametros['pwd']=passOVP;
                    $parametros['BaseDeDatos']=$ciudadovp;
                    $parametros['PCTransaccion']=$_SESSION["idusuario"];
                    $parametros['Moneda']="Bolivianos";
                    $parametros['Fecha']=$dt->format('c');
                    $parametros['NroOrdenRGI']=$rowOVP["carpeta"];
                    $parametros['IdCliente']=$rowOVP["id_OVP"];//VALOR TEMPORAL
                    //$parametros['descripcionTransaccion']=$rowOVP["adjuntos"];
                    $parametros['descripcionTransaccion']= utf8_encode(substr($rowOVP["adjuntos"], 0, 120));
                    $parametros['codigoReferencia']=$rowConcepto["id_OVP"];
                    $parametros['IdTipoValor']=202;

                    $result = $clienteovp->RGI_GrabarCierreCarpeta($parametros);
                    if (is_soap_fault($result)) {
                        trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
                    }

                    $resultOVPdatos = mysql_query("SELECT
                        idpagosovp, idpagos, idcasos, idpagosdetalle, nroLote, outNroAsignacion, IdOrdenIFP, IdOrdenIFC, NroAsignacionFact, errorOVPFact
                        FROM dav_pagosovp
                        WHERE
                        dav_pagosovp.idcasos=" . $idcasos . "
                        AND dav_pagosovp.outNroAsignacion IS NOT NULL
                        AND (dav_pagosovp.IdOrdenIFC IS NULL OR dav_pagosovp.IdOrdenIFC = '')
                        ORDER BY dav_pagosovp.outNroAsignacion+0 ASC;
                        ");

                    if($result->RGI_GrabarCierreCarpetaResult==1){
                        if ($tipo == 4) {//cerrar NDs
                            //graba datos de planillaje global en la ND
                            $query = ("UPDATE dav_notasdebito SET listaAsignacionesOVP='" . json_encode($result->outRespuestaServicio) . "' WHERE idcasos=" . $idcasos . ";");
                            $resultupdateOVP = mysql_query($query);
                            $this->logOVP->saveLog($parametros,$query,"cerrarND","dav_notasdebito","UPDATE",$result);
                        }else{//graba datos de planillaje global en facturaplanilla
                            $query = ("UPDATE dav_facturaplanilla SET listaAsignacionesOVP='".json_encode($result->outRespuestaServicio)."', errorOVP=NULL WHERE idcasos=".$idcasos." AND nro=".$nro.";");
                            $resultupdateOVP = mysql_query($query);
                            $this->logOVP->saveLog($parametros,$query,"cerrarPlanilla","dav_facturaplanilla","UPDATE",$result);
                        }

                        $cantidad =count($result->outRespuestaServicio->ListaAsignaciones->AsignacionDeLaCarpetaRGI);
                        $json = json_encode($result->outRespuestaServicio->ListaAsignaciones->AsignacionDeLaCarpetaRGI);
                        $json = json_decode($json);
                        if ($cantidad==1){//grabar datos de planillaje en dav_pagosovp
                            while($rowOVP = mysql_fetch_array($resultOVPdatos)){
                                if ((int)$rowOVP["outNroAsignacion"] == (int)$json->NroAsignacion) {
                                    $query = ("UPDATE dav_pagosovp SET IdOrdenIFC='" . $json->IdOrdenIFC . "', NroAsignacionFact='" . $json->NroAsignacion . "', errorOVPFact=NULL WHERE idpagosovp=" . $rowOVP["idpagosovp"] . ";");
                                    $resultupdateOVP = mysql_query($query);
                                    $this->logOVP->saveLog(NULL, $query, "asignarCodIFC", "dav_pagosovp", "UPDATE", NULL);
                                }else{
                                    $query = "N/A";
                                    $this->logOVP->saveLog(NULL, $query, "errorAsignarCodIFC", "dav_pagosovp", "UPDATE", $result);
                                }
                            }
                        }else{
                            while($rowOVP = mysql_fetch_array($resultOVPdatos)){
                                for ($i=0 ; $i<$cantidad ; $i++) {
                                    if ((int)$rowOVP["outNroAsignacion"] == (int)$json[$i]->NroAsignacion) {
                                        $query = ("UPDATE dav_pagosovp SET IdOrdenIFC='" . $json[$i]->IdOrdenIFC . "', NroAsignacionFact='" . $json[$i]->NroAsignacion . "', errorOVPFact=NULL WHERE idpagosovp=" . $rowOVP["idpagosovp"] . ";");
                                        $resultupdateOVP = mysql_query($query);
                                        $this->logOVP->saveLog(NULL, $query, "asignarCodIFC", "dav_pagosovp", "UPDATE", NULL);
                                    }
                                }
                            }
                        }
                    }  else {
                        if ($tipo != 4) {//cerrar NDs
                            $query = ("UPDATE dav_facturaplanilla SET errorOVP='" . mysql_real_escape_string($result->msgError) . "' WHERE idcasos=" . $idcasos . " AND nro=" . $nro . ";");
                            $resultupdateOVP = mysql_query($query);
                            $this->logOVP->saveLog($parametros,$query,"errorCerrarPlanilla","dav_facturaplanilla","UPDATE",$result);
                        }

                        while($rowOVP = mysql_fetch_array($resultOVPdatos)){//grabar datos de planillaje en dav_pagosdetalle
                            $query = ("UPDATE dav_pagosovp SET errorOVPFact='".mysql_real_escape_string($result->msgError)."' WHERE idpagosovp=".$rowOVP["idpagosovp"].";");
                            $resultupdateOVP = mysql_query($query);
                            $this->logOVP->saveLog($parametros,$query,"errorCodIFC","dav_pagosovp","UPDATE",$result);
                        }
                    }
                }
            }else{
                ?>
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h4>Advertencia!</h4>
                    <?= "No hay valores para guardar PLANILLAJE en la OVP." ;?>
                </div>
                <?
            }
        }
    }
    */

    /*
    public function agregaranticipo($idanticipos,$estado1,$ciudadovp,$clienteovp){
        $dt = new DateTime();
        if ($_SESSION["idciudad"] == 4) {
            $idovp = "";
        } elseif ($_SESSION["idciudad"] == 11) {
            $idovp = "SC";
        }else{
            die("Error inesperado al definir la sucursal.");
        }

        //START --- DATOS DEL ANTICIPO
        $resultOVP = mysql_query("SELECT
    dav_anticipos.idanticipos,
    dav_anticipos.fecha,
    dav_anticipos.recibo,
    dav_tipoanticipos.tipoanticipos,
    dav_anticipos.idbanco,
    CONCAT(dav_banco.banco,'|',dav_banco.cuenta) as banco,
    dav_banco.id_OVP".$idovp." as idBancoOVP,
    dav_anticipos.idtipotransferencia,
    dav_tipotransferencia.tipotransferencia,
    dav_anticipos.glosa,
    dav_anticipos.monto,
    dav_anticipos.idcliente,
    dav_cliente.id_OVP".$idovp." as idClienteOVP
    FROM
    dav_anticipos
    LEFT JOIN dav_tipoanticipos ON dav_anticipos.idtipoanticipos=dav_tipoanticipos.idtipoanticipos
    LEFT JOIN dav_banco ON dav_anticipos.idbanco=dav_banco.idbanco
    LEFT JOIN dav_cliente ON dav_anticipos.idcliente=dav_cliente.idcliente
    LEFT JOIN dav_tipotransferencia ON dav_anticipos.idtipotransferencia=dav_tipotransferencia.idtipotransferencia
    WHERE dav_anticipos.idanticipos=".$idanticipos.";");

        //START --- ARRAY DEL ANTICIPO
        if($estado1 == "ovpanticipo"){//verificar si puede guardar en ovp
            if (mysql_num_rows($resultOVP) > 0){
                while($rowOVP = mysql_fetch_array($resultOVP)){
                    //$dt =  new DateTime($rowOVP["fecha"] . " " .date('H:i:s', time()));
                    if((int)ADDSUBTIME == 1){
                        $dt->add(new DateInterval('PT' . OVPTIME . 'M'));
                    }elseif((int)ADDSUBTIME == 0){
                        $dt->sub(new DateInterval('PT' . OVPTIME . 'M'));
                    }else{
                        die('NO MIGRO DATOS, ERROR.');
                    }
                    $parametros=array();
                    $parametros['CodigoSeguridad']=codigoSeguridadOVP;
                    $parametros['login']=userOVP;
                    $parametros['pwd']=passOVP;
                    $parametros['BaseDeDatos']=$ciudadovp;
                    $parametros['PCTransaccion']=$_SESSION["idusuario"];
                    $parametros['IdCliente']=$rowOVP["idClienteOVP"];
                    $parametros['Fecha']=$dt->format('c');
                    $parametros['Moneda']="Bolivianos";
                    $parametros['codReferencia']="200";
                    //$parametros['Pie']="Prueba pie";
                    //$parametros['Notas']="Prueba notas";
                    //$parametros['Latitud']=0;
                    //$parametros['Longitud']=0;
                    $parametros['formaDePago']=array(
                        'Descripcion' => substr($rowOVP["glosa"], 0, 60),//limite de caracteres
                        'FechaExpiracion' => $dt->format('c'),
                        'FechaRecepcion' => $dt->format('c'),
                        'IdFormaPago' => $rowOVP["idBancoOVP"],
                        'Monto' => $rowOVP["monto"],
                        'Nombre' => $rowOVP["banco"],
                        'Numero' => $rowOVP["recibo"],
                    );

                    $result = $clienteovp->GC_GrabarAnticipoCliente($parametros);
                    if (is_soap_fault($result)) {
                        trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
                    }

                    if($result->GC_GrabarAnticipoClienteResult==1){
                        $query = ("UPDATE dav_anticipos SET IdOrden ='".$result->respuestaDocumentoGC->IdOrden."',outNumero ='".$result->respuestaDocumentoGC->Numero."', errorOVPAnt=NULL WHERE idanticipos=".$idanticipos.";");
                        $resultupdateOVP = mysql_query($query);
                        $this->logOVP->saveLog($parametros,$query,"agregarAnticipo","dav_anticipos","UPDATE",$result);
                    }else {
                        $query = ("UPDATE dav_anticipos SET IdOrden=NULL,outNumero=NULL, errorOVPAnt='".mysql_real_escape_string($result->msgError)."' WHERE idanticipos=".$idanticipos.";");
                        $resultupdateOVP = mysql_query($query);
                        $this->logOVP->saveLog($parametros,$query,"errorAgregarAnticipo","dav_anticipos","UPDATE",$result);
                    }
                }
            }else{
                echo "no hay valores para guardar ANTICIPOS en la OVP";
            }
        }
    }
    */
    /*
    public function agregarcobro($idanticipos,$estado2,$ciudadovp,$clienteovp,$tipocobro,$idfacpago,$monto2,$tributos,$idcobros,$fecha){
        $dt = new DateTime();
        //$dt =  new DateTime($fecha . " " .date('H:i:s', time()));
        if((int)ADDSUBTIME == 1){
            $dt->add(new DateInterval('PT' . OVPTIME . 'M'));
        }elseif((int)ADDSUBTIME == 0){
            $dt->sub(new DateInterval('PT' . OVPTIME . 'M'));
        }else{
            die('NO MIGRO DATOS, ERROR.');
        }

        if ($_SESSION["idciudad"] == 4) {
            $idovp = "";
        } elseif ($_SESSION["idciudad"] == 11) {
            $idovp = "SC";
        }else{
            die("Error inesperado al definir la sucursal.");
        }

        //START --- DATOS DEL COBRO
        $resultAnticipo = mysql_query("SELECT idanticipos,IdOrden,dav_cliente.id_OVP".$idovp." as idcliente,IFNULL(saldo,monto) as saldo
                                from dav_anticipos
                                LEFT JOIN dav_cliente ON dav_cliente.idcliente=dav_anticipos.idcliente
                                where idanticipos=".$idanticipos.";");
        $detalleAnticipo = mysql_fetch_assoc($resultAnticipo);

        if ($tipocobro == 1){//factura
            $resFactura = mysql_query("SELECT dav_facturaplanilla.*, SUM(dav_facturasdetalle.monto) as monto
                        FROM dav_facturaplanilla
                        LEFT JOIN dav_facturasdetalle ON dav_facturaplanilla.idfacturaplanilla=dav_facturasdetalle.idfacturaplanilla
                        WHERE idestadofactura=1 AND dav_facturaplanilla.idfacturaplanilla=".$idfacpago.";");
        }
        if ($tipocobro == 2 && $tributos == 1){//planilla tributos
            $result = mysql_query("SELECT idfacturaplanilla,idcasos,idciudad FROM dav_facturaplanilla WHERE idfacturaplanilla=".$idfacpago.";");
            $resultFact = mysql_fetch_assoc($result);

            $resultantiguo = mysql_query("SELECT dav_pagos.fecha,dav_pagosdetalle.idpagosdetalle,dav_pagosdetalle.idpagos,dav_pagosdetalle.idcasos,dav_pagosdetalle.idconcepto
                    FROM dav_pagosdetalle
                    LEFT JOIN dav_pagos ON dav_pagosdetalle.idpagos=dav_pagos.idpagos
                    where dav_pagosdetalle.idcasos=".$resultFact['idcasos'].";");

            $lista = array();//$resultantiguo se convierte en array para busqueda de valores
            while ($rs = mysql_fetch_assoc($resultantiguo)){
                $lista[] = $rs;
            }
            $antforma = 0;
            foreach ($lista as $key => $val) {
                if ($val['idconcepto'] == 31 || $val['idconcepto'] == 41 || $val['idconcepto'] == 23) {// se busca los conceptos de tributos
                    if ($val['fecha'] < '2020-05-01' || $val['fecha'] == NULL ){//verificamos si son registros iniciales de OVP
                        $antforma++;
                    }
                }
            }

            $resFactura = mysql_query ("SELECT
                        dav_facturaplanilla.idfacturaplanilla,
                        dav_pagos.idpagos,
                        dav_pagos.fecha,
                        dav_pagosovp.idpagos as idpovp,
                        dav_pagos.numero,
                        ".($antforma > 0 ?
                    "(dav_pagosdetalle.monto) as monto," :
                    "SUM(dav_pagosdetalle.monto) as monto,"
                )."
                        dav_pagosovp.idpagosovp,
                        dav_pagosovp.outNroAsignacion,
                        dav_pagosovp.IdOrdenIFC,
                        dav_pagosovp.cobrado
                        FROM
                        dav_pagosdetalle
                        LEFT JOIN dav_pagos ON dav_pagosdetalle.idpagos=dav_pagos.idpagos
                        LEFT JOIN dav_pagosovp ".
                ($antforma > 0 ?
                    "ON dav_pagosdetalle.idpagosdetalle=dav_pagosovp.idpagosdetalle" :
                    "ON dav_pagos.idpagos=dav_pagosovp.idpagos"
                )
                ."
                        LEFT JOIN dav_facturaplanilla ON dav_pagosdetalle.idcasos=dav_facturaplanilla.idcasos
                        LEFT JOIN dav_casos ON dav_pagosdetalle.idcasos=dav_casos.idcasos
                        LEFT JOIN dav_concepto ON dav_pagosdetalle.idconcepto=dav_concepto.idconcepto
                        WHERE
                        IFNULL(dav_pagosdetalle.prepagado,0)=0
                        AND IFNULL(dav_concepto.tributos,0)=1
                        AND dav_facturaplanilla.idestadoplanilla=1
                        AND ifnull(dav_pagos.idestadopago,0)<>3 AND ifnull(dav_pagosdetalle.nd,0)=0
                        AND dav_facturaplanilla.idfacturaplanilla=".$idfacpago.";");
        }
        if ($tipocobro == 2 && $tributos == 0){//planilla otros
            $resFactura = mysql_query("SELECT
                        dav_facturaplanilla.idfacturaplanilla,
                        dav_pagos.idpagos,
                        dav_pagos.fecha,
                        dav_pagos.numero,
                        dav_pagosdetalle.monto,
                        dav_pagosovp.idpagosovp,
                        dav_pagosovp.outNroAsignacion,
                        dav_pagosovp.IdOrdenIFC,
                        dav_pagosovp.cobrado,
                        dav_pagosovp.saldo
                        FROM
                        dav_pagosdetalle
                        LEFT JOIN dav_pagos ON dav_pagosdetalle.idpagos=dav_pagos.idpagos
                        LEFT JOIN dav_pagosovp ON dav_pagosovp.idpagosdetalle=dav_pagosdetalle.idpagosdetalle
                        LEFT JOIN dav_facturaplanilla ON dav_pagosdetalle.idcasos=dav_facturaplanilla.idcasos
                        LEFT JOIN dav_casos ON dav_pagosdetalle.idcasos=dav_casos.idcasos
                        LEFT JOIN dav_concepto ON dav_pagosdetalle.idconcepto=dav_concepto.idconcepto
                        WHERE
                        IFNULL(dav_pagosdetalle.prepagado,0)=0
                        AND IFNULL(dav_concepto.tributos,0)=0
                        AND dav_facturaplanilla.idestadoplanilla=1
                        AND ifnull(dav_pagos.idestadopago,0)<>3 AND ifnull(dav_pagosdetalle.nd,0)=0
                        AND (IFNULL(dav_pagosovp.saldo,0)!=0 OR dav_pagosovp.saldo IS NULL)
                        AND IFNULL(dav_pagosovp.cobrado,0)=0
                        AND dav_facturaplanilla.idfacturaplanilla=".$idfacpago.";");
        }
        if ($tipocobro == 3){//notas de debito
            $resFactura = mysql_query("SELECT
                        dav_notasdebito.idcasos,
                        dav_notasdebito.idnotasdebito,
                        dav_notasdebitodetalle.monto,
                        dav_notasdebito.numero,
                        dav_notasdebito.gestion,
                        dav_notasdebito.fecha,
                        dav_pagosovp.idpagosovp,
                        dav_pagosovp.outNroAsignacion,
                        dav_pagosovp.IdOrdenIFC,
                        dav_pagosovp.IdOrdenIFP,
                        dav_pagosovp.cobrado
                        FROM
                        dav_notasdebito
                        LEFT JOIN dav_notasdebitodetalle ON dav_notasdebito.idnotasdebito=dav_notasdebitodetalle.idnotasdebito
                        LEFT JOIN dav_pagosovp ON dav_pagosovp.idpagos=dav_notasdebito.idnotasdebito
                        LEFT JOIN dav_casos ON dav_notasdebito.idcasos=dav_casos.idcasos
                        WHERE dav_notasdebito.idestadopago=2 AND dav_notasdebitodetalle.idnotasdebito=".$idfacpago."
                        AND dav_notasdebito.idtiponotasdebito=1
                        GROUP BY dav_notasdebito.idcasos, dav_notasdebito.idnotasdebito;");
        }

        $continuar = 0;
        while($row = mysql_fetch_assoc($resFactura)){
            $continuar += ($tipocobro == 1 ? (empty($row['outIdOrdenFactura']) ? 1 : 0 ) : (empty($row['IdOrdenIFC']) ? 1 : 0 ));
        }
        mysql_data_seek($resFactura, 0);
        //START --- ARRAY COBRO
        $monto = $monto2;
        if($estado2 == "ovpcobro") {//verificar si puede guardar en ovp
            if (mysql_num_rows($resFactura) > 0) {
                if ($continuar == 0) {
                    //$resultLimpiar = mysql_query("UPDATE dav_cobros SET errorOVP=NULL WHERE idcobros=".$idcobros.";");
                    while ($rowOVP = mysql_fetch_array($resFactura)) {
                        if ($monto != 0) {
                            $parametros = array();
                            $parametros['CodigoSeguridad'] = codigoSeguridadOVP;
                            $parametros['login'] = userOVP;
                            $parametros['pwd'] = passOVP;
                            $parametros['BaseDeDatos'] = $ciudadovp;
                            $parametros['FechaDocumento'] = $dt->format('c');
                            $parametros['IdCliente'] = $detalleAnticipo["idcliente"];
                            $parametros['IdOrdenAnticipo'] = $detalleAnticipo["IdOrden"];
                            $parametros['Moneda'] = "Bolivianos";
                            $parametros['PCTransaccion'] = $_SESSION["idusuario"];

                            if ($tipocobro == 1 && !empty($rowOVP["outIdOrdenFactura"])) {//factura
                                $parametros['MontoAAplicar'] = $monto;// o usar $monto | $rowOVP['monto']
                                $parametros['IdOrdenFactura'] = $rowOVP["outIdOrdenFactura"];
                                $parametros['IdFormaPagoFactura'] = ($rowOVP['fecha'] < '2020-05-01' ? 204 : 21);
                                $result = $clienteovp->GC_GrabarAplicacionAnticipo($parametros);
                                if (is_soap_fault($result)) {
                                    trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
                                }
                                if ($result->GC_GrabarAplicacionAnticipoResult == 1) {
                                    $resultupdateOVP = mysql_query("UPDATE dav_facturaplanilla SET cobrado='" . $result->GC_GrabarAplicacionAnticipoResult . "' WHERE idfacturaplanilla=" . $rowOVP["idfacturaplanilla"] . ";");
                                    $query = ("UPDATE dav_cobros SET errorOVP=NULL,resultOVP=CONCAT(ifnull(resultOVP,''),'FacturaNro: " . $rowOVP["nro"] . "<br>') WHERE idcobros=" . $idcobros . ";");
                                    $resultupdateOVP = mysql_query($query);
                                    $querysaldo = "UPDATE dav_anticipos SET saldo=".($detalleAnticipo["saldo"]-$monto)." WHERE idanticipos=".$detalleAnticipo["idanticipos"].";";
                                    $resultSaldo = mysql_query($querysaldo);
                                    $this->logOVP->saveLog($parametros, $query, "agregarAplicacionAnticipoFactura", "dav_cobros", "UPDATE", $result);
                                } else {
                                    $query = ("UPDATE dav_cobros SET errorOVP=CONCAT(ifnull(errorOVP,''),'" . mysql_real_escape_string($result->msgError) . "<br>') WHERE idcobros=" . $idcobros . ";");
                                    $resultupdateOVP = mysql_query($query);
                                    $this->logOVP->saveLog($parametros, $query, "errorAgregarAplicacionAnticipoFactura", "dav_cobros", "UPDATE", $result);
                                }
                            } elseif ($tipocobro == 1 && empty($rowOVP["outIdOrdenFactura"])) {
                                $query = ("UPDATE dav_cobros SET errorOVP=CONCAT(ifnull(errorOVP,''), 'FacturaNro: " . $rowOVP["nro"] . "<br>') WHERE idcobros=" . $idcobros . ";");
                                $resultupdateOVP = mysql_query($query);
                                $this->logOVP->saveLog($parametros, $query, "errorAgregarAplicacionAnticipoFaltaFactura", "dav_cobros", "UPDATE", NULL);
                            }

                            if ($tipocobro == 2 &&
                                !empty($rowOVP["IdOrdenIFC"]) && $tributos == 1) {//planillas tributos
                                if ($tributos == 1) {//planilla tributos
                                    $parametros['MontoAAplicar'] = ($rowOVP['fecha'] < '2020-05-01' ? $rowOVP['monto'] : $monto);// o usar $monto | $rowOVP['monto']
                                }
                                $parametros['IdOrdenFactura'] = $rowOVP["IdOrdenIFC"];
                                $parametros['IdFormaPagoFactura'] = 202;
                                $result = $clienteovp->GC_GrabarAplicacionAnticipo($parametros);
                                if (is_soap_fault($result)) {
                                    trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
                                }
                                if ($result->GC_GrabarAplicacionAnticipoResult == 1) {
                                    $resultupdateOVP = mysql_query("UPDATE dav_pagosovp SET cobrado='" . $result->GC_GrabarAplicacionAnticipoResult . "' WHERE idpagosovp=" . $rowOVP["idpagosovp"] . ";");
                                    $query = ("UPDATE dav_cobros SET errorOVP=NULL,resultOVP=CONCAT(ifnull(resultOVP,''),'CPBTE: " . $rowOVP["numero"] . "<br>') WHERE idcobros=" . $idcobros . ";");
                                    $resultupdateOVP = mysql_query($query);
                                    $querysaldo = "UPDATE dav_anticipos SET saldo=".($detalleAnticipo["saldo"]-($rowOVP['fecha'] < '2020-05-01' ? $rowOVP['monto'] : $monto))." WHERE idanticipos=".$detalleAnticipo["idanticipos"].";";
                                    $resultSaldo = mysql_query($querysaldo);
                                    $this->logOVP->saveLog($parametros, $query, "agregarAplicacionAnticipoPlanilla", "dav_cobros", "UPDATE", $result);
                                } else {
                                    $query = ("UPDATE dav_cobros SET errorOVP=CONCAT(ifnull(errorOVP,''),'" . mysql_real_escape_string($result->msgError) . "<br>') WHERE idcobros=" . $idcobros . ";");
                                    $resultupdateOVP = mysql_query($query);
                                    $this->logOVP->saveLog($parametros, $query, "errorAgregarAplicacionAnticipoPlanilla", "dav_cobros", "UPDATE", $result);
                                }
                            } elseif ($tipocobro == 2 && (empty($rowOVP["outNroAsignacion"]) || empty($rowOVP["IdOrdenIFC"])) && $tributos == 1) {
                                $query = ("UPDATE dav_cobros SET errorOVP=CONCAT(ifnull(errorOVP,''), 'CPBTE: " . $rowOVP["numero"] . "<br>') WHERE idcobros=" . $idcobros . ";");
                                $resultupdateOVP = mysql_query($query);
                                $this->logOVP->saveLog($parametros, $query, "errorAgregarAplicacionAnticipoFaltaPlanilla", "dav_cobros", "UPDATE", NULL);
                            }

                            if ($tipocobro == 2 &&
                                !empty($rowOVP["IdOrdenIFC"]) && empty($rowOVP["cobrado"]) && $tributos == 0) {//planillas otros
                                $rowOVP['monto'] = (is_null($rowOVP['saldo']) ? $rowOVP['monto'] : ($rowOVP['saldo'] != 0 ? $rowOVP['saldo'] : 0));
                                if ($monto == $rowOVP['monto']) {//monto aplicado == a monto de planillas
                                    $parametros['MontoAAplicar'] = $rowOVP['monto'];
                                } elseif ($rowOVP['monto'] < $monto) {//monto de planillas < monto aplicado
                                    $parametros['MontoAAplicar'] = $rowOVP['monto'];
                                } elseif ($rowOVP['monto'] > $monto) {//monto de planillas > monto aplicado
                                    $parametros['MontoAAplicar'] = $monto;
                                }
                                $parametros['IdOrdenFactura'] = $rowOVP["IdOrdenIFC"];
                                $parametros['IdFormaPagoFactura'] = 202;

                                $result = $clienteovp->GC_GrabarAplicacionAnticipo($parametros);
                                if (is_soap_fault($result)) {
                                    trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
                                }
                                if ($result->GC_GrabarAplicacionAnticipoResult == 1) {
                                    if ($monto == $rowOVP['monto']) {//monto aplicado == a monto de planillas
                                        $saldoanticipo = (float)$detalleAnticipo["saldo"]-(float)$rowOVP['monto'];
                                        $monto = $monto - $rowOVP['monto'];
                                        $queryovp = ("UPDATE dav_pagosovp SET cobrado='" . $result->GC_GrabarAplicacionAnticipoResult . "', saldo=" . $monto . " WHERE idpagosovp=" . $rowOVP["idpagosovp"] . ";");
                                    } elseif ($rowOVP['monto'] < $monto) {//monto de planillas < monto aplicado
                                        $saldoanticipo = (float)$detalleAnticipo["saldo"]-(float)$rowOVP['monto'];
                                        $monto = $monto - $rowOVP['monto'];
                                        $saldo = 0;
                                        $queryovp = ("UPDATE dav_pagosovp SET cobrado='" . $result->GC_GrabarAplicacionAnticipoResult . "', saldo=" . $saldo . " WHERE idpagosovp=" . $rowOVP["idpagosovp"] . ";");
                                    } elseif ($rowOVP['monto'] > $monto) {//monto de planillas > monto aplicado
                                        $saldoanticipo = (float)$detalleAnticipo["saldo"]-(float)$monto;
                                        $saldo = $rowOVP['monto'] - $monto;
                                        $monto = 0;
                                        if ($saldo == 0) {
                                            $queryovp = ("UPDATE dav_pagosovp SET cobrado='" . $result->GC_GrabarAplicacionAnticipoResult . "', saldo=" . $saldo . " WHERE idpagosovp=" . $rowOVP["idpagosovp"] . ";");
                                        } else {
                                            $queryovp = ("UPDATE dav_pagosovp SET cobrado='0', saldo=" . $saldo . " WHERE idpagosovp=" . $rowOVP["idpagosovp"] . ";");
                                        }
                                    }
                                    $resultupdateOVP = mysql_query($queryovp);
                                    $querysaldo = "UPDATE dav_anticipos SET saldo=".$saldoanticipo." WHERE idanticipos=".$detalleAnticipo["idanticipos"].";";
                                    $resultSaldo = mysql_query($querysaldo);
                                    $query = ("UPDATE dav_cobros SET errorOVP=NULL,resultOVP=CONCAT(ifnull(resultOVP,''),'CPBTE: " . $rowOVP["numero"] . "<br>') WHERE idcobros=" . $idcobros . ";");
                                    $resultupdateOVP = mysql_query($query);
                                    //$this->logOVP->saveLog($parametros, $query, "agregarAplicacionAnticipoPlanilla", "dav_cobros", "UPDATE", $result);
                                } else {
                                    $query = ("UPDATE dav_cobros SET errorOVP=CONCAT(ifnull(errorOVP,''),'" . mysql_real_escape_string($result->msgError) . "<br>') WHERE idcobros=" . $idcobros . ";");
                                    $resultupdateOVP = mysql_query($query);
                                    $monto=0;
                                    //$this->logOVP->saveLog($parametros, $query, "errorAgregarAplicacionAnticipoPlanilla", "dav_cobros", "UPDATE", $result);
                                }
                            } elseif ($tipocobro == 2 && (empty($rowOVP["outNroAsignacion"]) || empty($rowOVP["IdOrdenIFC"])) && $tributos == 0) {
                                $query = ("UPDATE dav_cobros SET errorOVP=CONCAT(ifnull(errorOVP,''), 'CPBTE: " . $rowOVP["numero"] . "<br>') WHERE idcobros=" . $idcobros . ";");
                                $resultupdateOVP = mysql_query($query);
                                $this->logOVP->saveLog($parametros, $query, "errorAgregarAplicacionAnticipoFaltaPlanilla", "dav_cobros", "UPDATE", NULL);
                            }

                            if ($tipocobro == 3 && !empty($rowOVP["outNroAsignacion"]) && !empty($rowOVP["IdOrdenIFC"]) ) {//notas de debito
                                $parametros['MontoAAplicar'] = $monto;// o usar $monto | $rowOVP['monto']
                                $parametros['IdOrdenFactura'] = $rowOVP["IdOrdenIFC"];
                                $parametros['IdFormaPagoFactura'] = 202;
                                $result = $clienteovp->GC_GrabarAplicacionAnticipo($parametros);
                                if (is_soap_fault($result)) {
                                    trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faultstring})", E_USER_ERROR);
                                }
                                if ($result->GC_GrabarAplicacionAnticipoResult == 1) {
                                    $resultupdateOVP = mysql_query("UPDATE dav_pagosovp SET cobrado='" . $result->GC_GrabarAplicacionAnticipoResult . "' WHERE idpagosovp=" . $rowOVP["idpagosovp"] . ";");
                                    $query = ("UPDATE dav_cobros SET errorOVP=NULL,resultOVP=CONCAT(ifnull(resultOVP,''),'ND: " . ($rowOVP["numero"] . "/" . $rowOVP["gestion"]) . "<br>') WHERE idcobros=" . $idcobros . ";");
                                    $resultupdateOVP = mysql_query($query);
                                    $querysaldo = "UPDATE dav_anticipos SET saldo=".($detalleAnticipo["saldo"]-$monto)." WHERE idanticipos=".$detalleAnticipo["idanticipos"].";";
                                    $resultSaldo = mysql_query($querysaldo);
                                    $this->logOVP->saveLog($parametros, $query, "agregarAplicacionAnticipoND", "dav_cobros", "UPDATE", $result);
                                } else {
                                    $query = ("UPDATE dav_cobros SET errorOVP=CONCAT(ifnull(errorOVP,''),'" . mysql_real_escape_string($result->msgError) . "<br>') WHERE idcobros=" . $idcobros . ";");
                                    $resultupdateOVP = mysql_query($query);
                                    $this->logOVP->saveLog($parametros, $query, "errorAgregarAplicacionAnticipoND", "dav_cobros", "UPDATE", $result);
                                }
                            } elseif ($tipocobro == 3 && (empty($rowOVP["outNroAsignacion"]) || empty($rowOVP["IdOrdenIFC"]))) {
                                $query = ("UPDATE dav_cobros SET errorOVP=CONCAT(ifnull(errorOVP,''), 'ND: " . ($rowOVP["numero"] . "/" . $rowOVP["gestion"]) . "<br>') WHERE idcobros=" . $idcobros . ";");
                                $resultupdateOVP = mysql_query($query);
                                $this->logOVP->saveLog($parametros, $query, "errorAgregarAplicacionAnticipoFaltaND", "dav_cobros", "UPDATE", NULL);
                            }
                        }
                    }
                } else {
                    $resultLimpiar = mysql_query("UPDATE dav_cobros SET errorOVP=NULL WHERE idcobros=".$idcobros.";");
                    while ($rowOVP = mysql_fetch_array($resFactura)) {
                        if ($tipocobro == 1 && empty($rowOVP["outIdOrdenFactura"])) {//factura
                            $query = ("UPDATE dav_cobros SET errorOVP=CONCAT(ifnull(errorOVP,''), 'FacturaNro: " . $rowOVP["nro"] . "<br>') WHERE idcobros=" . $idcobros . ";");
                            $resultupdateOVP = mysql_query($query);
                            $this->logOVP->saveLog(NULL,$query,"errorCobroNoEncuentraFactura","dav_cobros","UPDATE",NULL);
                        }

                        if (($tipocobro == 2) && (empty($rowOVP["outNroAsignacion"]) || empty($rowOVP["IdOrdenIFC"]))) {//planillas y otros
                            $cpbte = (!empty($rowOVP['idpagos']) ? $rowOVP["numero"] : "S/CPBTE");
                            $query = ("UPDATE dav_cobros SET errorOVP=CONCAT(ifnull(errorOVP,''), 'CPBTE: " . $cpbte . "<br>') WHERE idcobros=" . $idcobros . ";");
                            $resultupdateOVP = mysql_query($query);
                            $this->logOVP->saveLog(NULL,$query,"errorCobroNoEncuentraPlanilla","dav_cobros","UPDATE",NULL);
                        }

                        if (($tipocobro == 3) && (empty($rowOVP["outNroAsignacion"]) || empty($rowOVP["IdOrdenIFC"]))) {//planillas y otros
                            $cpbte = (!empty($rowOVP['idnotasdebito']) ? ($rowOVP["numero"]."/".$rowOVP["gestion"]) : "S/ND");
                            $query = ("UPDATE dav_cobros SET errorOVP=CONCAT(ifnull(errorOVP,''), 'ND: " . $cpbte . "<br>') WHERE idcobros=" . $idcobros . ";");
                            $resultupdateOVP = mysql_query($query);
                            $this->logOVP->saveLog(NULL,$query,"errorCobroNoEncuentraND","dav_cobros","UPDATE",NULL);
                        }
                    }
                }
            } else {
                $query = ("UPDATE dav_cobros SET resultOVP=NULL, errorOVP='Error inesperado, vuelva a intentarlo.' WHERE idcobros=" . $idcobros . ";");
                $resultupdateOVP = mysql_query($query);
                $this->logOVP->saveLog(NULL,$query,"errorInesperadoCobro","dav_cobros","UPDATE",NULL);
            }
        }
    }
    */
}
