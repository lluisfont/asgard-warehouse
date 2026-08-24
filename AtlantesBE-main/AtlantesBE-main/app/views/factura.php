<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<head>
	<meta equiv='Content-Type' content='text/html; charset=utf-8'/>
  <?= $membretadoImagen ?>
</head>
<body>
    <!--
<div style="height: 250px">

</div>
    -->
<table border='1' cellpadding='3' cellspacing='1' style='width: 725px'>
				<tr>
					<td style="width: 100px"><span style='font-size: 9pt; font-family: Tahoma; font-weight: bold'>Fecha:</span></td>
					<td align='left' style="width: 300px"><span
							style='font-size: 9pt; font-family: Tahoma'><?= $fecha ?> <?= $hora ?></span></td>
					<td style="width: 100px"><span style='font-size: 9pt; font-family: Tahoma; font-weight: bold'>NIT/CI/CEX:</span></td>
					<td align='left' style="width: 100px"><span
							style='font-size: 9pt; font-family: Tahoma'><?= $datosXML['datos']['cabecera']['numeroDocumento'] ?></span>
					</td>
				</tr>
				<tr>
					<td style="width: 100px; vertical-align: top"><span style='font-size: 9pt; font-family: Tahoma; font-weight: bold'>Nombre/Razón Social:</span></td>
					<td align='left' style="width: 300px;vertical-align: top"><span
							style='font-size: 9pt; font-family: Tahoma'><?=$datosXML['datos']['cabecera']['nombreRazonSocial']?></span>
					</td>
					<td style="width: 100px; vertical-align: top"><span style='font-size: 9pt; font-family: Tahoma;font-weight: bold'>Cod. Cliente:</span></td>
					<td align='left' style="width: 100px; vertical-align: top"><span
							style='font-size: 9pt; font-family: Tahoma'><?= $datosXML['datos']['cabecera']['codigoCliente'] ?></span>
					</td>
				</tr>
</table>
<br/>
<h3>Datos Adicionales de la Operación</h3>
<table style="width: 100%; border: none; border-collapse: collapse">
	<tr>
		<td width='275'>
			<span style='font-size: 9pt; font-family: Tahoma'>No. Carpeta: <?=$embarque?></span> <span style='font-size: 9pt; font-family: Tahoma'>No. Guia: <?=$numeroguia?></span><br/>
			<span style='font-size: 9pt; font-family: Tahoma'>Proveedor: <?=$proveedor?></span> <span style='font-size: 9pt; font-family: Tahoma'>Origen: <?=$origen?></span><br/>
			<span style='font-size: 9pt; font-family: Tahoma'>Destino: <?=$destino?></span> <span style='font-size: 9pt; font-family: Tahoma'>Peso: <?=$peso?> KG</span><br/>
			<span style='font-size: 9pt; font-family: Tahoma'>Volumen: <?=$volumen?></span> <span style='font-size: 9pt; font-family: Tahoma'>Piezas: <?=$piezas?></span><br/>
			<span style='font-size: 9pt; font-family: Tahoma'>Pallets: <?=$pallets?></span><br/>
			<span style='font-size: 9pt; font-family: Tahoma'>No PACEÑA: <?=$carpetapacena?> / <?=$nodui?></span><br/>
		  <span style='font-size: 9pt; font-family: Tahoma'>Modo Transporte: <?=$tipoembarque?></span><br/>
		  <span style='font-size: 9pt; font-family: Tahoma'>Equivalente a Dólares: 6.96 Bs</span>
		</td>
	</tr>
</table>
<br>
<h3>Datos Adicionales de la Factura:</h3>
<?=$datosAdicionales?>
<br>
<?=$pageBreakTable?>
<div style="display: block;<?=$alturaTable?>"></div>
<h3>Detalle de Factura:</h3>
<table border='0' cellpadding='2' cellspacing='0' style='border-top: 1px solid black; border-collapse: collapse;width: 100%'>
	<tr>
		<td style='width:55px;border: 3px double black;border-spacing: 2px;text-align: center'><span style='font-size: 9pt; font-family: Tahoma'><strong>Cantidad</strong></span></td>
		<td style='width:75px;border: 3px double black;border-spacing: 2px;text-align: center'><span style='font-size: 9pt; font-family: Tahoma'><strong>Unidad de Medida</strong></span></td>
		<td style='width:120px;border: 3px double black;border-spacing: 2px;text-align: left'><span style='font-size: 9pt; font-family: Tahoma'><strong>Código Producto/Servicio</strong></span></td>
		<td style='width:250px;border: 3px double black;border-spacing: 2px;text-align: center'><span style='font-size: 9pt; font-family: Tahoma'><strong>Descripción</strong></span></td>
		<td style='width:90px;border: 3px double black;border-spacing: 2px;text-align: center'><span style='font-size: 9pt; font-family: Tahoma'><strong>Precio Unitario</strong></span></td>
		<td style='width:70px;border: 3px double black;border-spacing: 2px;text-align: center'><span style='font-size: 9pt; font-family: Tahoma'><strong>Descuento</strong></span></td>
		<td style='width:75px;border: 3px double black;border-spacing: 2px;text-align: center'><span style='font-size: 9pt; font-family: Tahoma'><strong>Subtotal</strong></span></td>
	</tr>
	<tr>
		<td width='725' colspan='7' style='border: 3px double black;border-spacing: 2px;'>
			<table border='0' cellpadding='2' cellspacing='0'>
        <?=$detalleFactura?>
			</table>
		</td>
	</tr>
</table>
<?=$pageBreak?>
<div style="display: block;<?=$altura?>"></div>
<table border='1' cellpadding='2' cellspacing='0' style='border-collapse: collapse;width: 100%;'>
	<tr>
		<td style='border: none;width: 350px' colspan='3'>SON:
      <?=$V->ValorEnLetras($datosXML['datos']['cabecera']['montoTotal'],'Bolivianos')?></td>
		<td colspan='3' align='right' style='border: 3px double black;border-spacing: 2px;width: 310px'><span
				style='font-size: 9pt; font-family: Tahoma'>SUBTOTAL Bs:</span></td>
		<td align='right' style='border: 3px double black;border-spacing: 2px;width: 75px'><span
				style='font-size: 9pt; font-family: Tahoma'><?=number_format($datosXML['datos']['cabecera']['montoTotal'], 2, '.', ',')?></span>
		</td>
	</tr>
	<tr>
		<td style='border: none;width: 350px'colspan='3' rowspan="5">
			<div style='float: left; width: 75px;margin: 0;padding: 0;'>
				<img src='<?=folder_files.$idempresa?>/documentos/facturas/QR_code-<?=$idfactura?>.png' />
			</div>
		</td>
		<td colspan='3' align='right' style='border: 3px double black;border-spacing: 2px;width: 310px'><span
				style='font-size: 9pt; font-family: Tahoma'>DESCUENTO Bs:</span></td>
		<td align='right' style='border: 3px double black;border-spacing: 2px;width: 75px'><span
				style='font-size: 9pt; font-family: Tahoma'>0.00</span></td>
	</tr>
	<tr>
		<td colspan='3' align='right' style='border: 3px double black;border-spacing: 2px;width: 310px'><span
				style='font-size: 9pt; font-family: Tahoma'>TOTAL Bs:</span></td>
		<td align='right' style='border: 3px double black;border-spacing: 2px;width: 75px'><span
				style='font-size: 9pt; font-family: Tahoma'><?=number_format($datosXML['datos']['cabecera']['montoTotal'], 2, '.', ',')?></span>
		</td>
	</tr>
	<tr>
		<td colspan='3' align='right' style='border: 3px double black;border-spacing: 2px;width: 310px'><span
				style='font-size: 9pt; font-family: Tahoma'>MONTO GIFT CARD Bs:</span></td>
		<td align='right' style='border: 3px double black;border-spacing: 2px;width: 75px'><span
				style='font-size: 9pt; font-family: Tahoma'>0.00</span></td>
	</tr>
	<tr>
		<td colspan='3' align='right' style='border: 3px double black;border-spacing: 2px;width: 310px;'><span
				style='font-size: 9pt; font-family: Tahoma'><strong>MONTO A PAGAR Bs:</strong></span></td>
		<td align='right' style='border: 3px double black;border-spacing: 2px;width: 75px'><span
				style='font-size: 9pt; font-family: Tahoma'><strong><?=number_format($datosXML['datos']['cabecera']['montoTotalMoneda'], 2, '.', ',')?></strong></span>
		</td>
	</tr>
	<tr>
		<td colspan='3' align='right' style='border: 3px double black;border-spacing: 2px;width: 310px;'><span
				style='font-size: 9pt; font-family: Tahoma'><strong>IMPORTE BASE CRÉDITO FISCAL Bs:</strong></span></td>
		<td align='right' style='border: 3px double black;border-spacing: 2px;width: 75px'><span
				style='font-size: 9pt; font-family: Tahoma'><strong><?=number_format($datosXML['datos']['cabecera']['montoTotalSujetoIva'], 2, '.', ',')?></strong></span>
		</td>
	</tr>
</table>
</body>
