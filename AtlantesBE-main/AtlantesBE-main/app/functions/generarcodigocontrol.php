<?
function d($j, $k)
{
	$table = array(
		array(0,1,2,3,4,5,6,7,8,9),
		array(1,2,3,4,0,6,7,8,9,5),
		array(2,3,4,0,1,7,8,9,5,6),
		array(3,4,0,1,2,8,9,5,6,7),
		array(4,0,1,2,3,9,5,6,7,8),
		array(5,9,8,7,6,0,4,3,2,1),
		array(6,5,9,8,7,1,0,4,3,2),
		array(7,6,5,9,8,2,1,0,4,3),
		array(8,7,6,5,9,3,2,1,0,4),
		array(9,8,7,6,5,4,3,2,1,0),
		);
	
	return $table[$j][$k];
}

function p($pos, $num)
{
	$table = array(
		array(0,1,2,3,4,5,6,7,8,9),
		array(1,5,7,6,2,8,3,0,9,4),
		array(5,8,0,3,7,9,6,1,4,2),
		array(8,9,1,6,0,4,3,5,2,7),
		array(9,4,5,3,1,2,6,8,7,0),
		array(4,2,8,6,5,7,3,9,0,1),
		array(2,7,9,3,8,0,6,4,1,5),
		array(7,0,4,6,9,1,3,2,5,8),
		);
	
	return $table[$pos % 8][$num];
}

function inv($j)
{
	$table = array(0,4,3,2,1,5,6,7,8,9);
	return $table[$j];
}

function calcsum($number)
{
	$c = 0;
	$n = strrev($number);
	$len = strlen($n);
	for ($i = 0; $i < $len; $i++)
		$c = d($c, p($i+1, $n[$i]));

	return inv($c);
}

// END VERHOEFF

//******** ALG BASE64 *******
 function base64($numero){
 $dicionario = array(	'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 
 											'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
 											'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',
 											'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd',
 											'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
 											'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x',
 											'y', 'z', '+', '/' ); 
 $cociente = 1;
 $resto=0; // dan
 $palabra = '';
 
 while ($cociente > 0){
 	
	 	$cociente = (int)$numero / 64; 
	 	
	 	$resto = (int) $numero % 64;
	 	if($cociente > 0)
	 	$palabra = $dicionario[$resto] . $palabra;
	 	
	 	$numero = $cociente;
	 	
 	}
 	
 return ($palabra);
}
// END BASE64

// ************* ALLEGERC4 ****************
function AllegedRC4($mensaje,$key)
{ //$state[256]; //dan
	$state= array(); // dan undefined
	$x = 0; $y = 0; $index1 = 0; $index2 = 0; 
  $mensajecifrado = "";
  
	for($i = 0; $i <= 255; $i++)
	{
		$state[$i] = $i;				
	}
	
	for($i = 0; $i <= 255; $i++)
	{			
	  $index2 = (ObtieneASCII($key[$index1]) + $state[$i] + $index2) % 256;		  	 
		$aux = $state[$i];
		$state[$i] = $state[$index2];
		$state[$index2] = $aux;		
		$index1 = ($index1 + 1) % strlen($key);
	}					
	
	for($i = 0; $i <= (strlen($mensaje)-1); $i++)
	{	
		$x = ($x + 1) % 256;		//int
		$y = ($state[$x] + $y) % 256;		
	  $aux = $state[$x];
	  $state[$x] = $state[$y];
	  $state[$y] = $aux;
		$nmen = ObtieneASCII($mensaje[$i]) ^ ($state[($state[$x] + $state[$y]) % 256]);					
		$mensajecifrado=$mensajecifrado.RellenaCero(ConvierteAHexadecimal($nmen));				
	}		
	$k = $mensajecifrado;	
	return ($k);
}

function ObtieneASCII($var)
{ $z = ord($var);
	return($z);
}

function RellenaCero($var)
{ 	
	if(strlen($var) == 1)
	{	$v = '0'."".$var;
		return($v);	
	}
	else
	{	return($var);	
	}	
}

function ConvierteAHexadecimal($var)
{ $z = chr($var);
	return (sprintf('%X',$var));
}
// END ELLEGERC4 

/////  TRUNCA NUMEROS	
function trunca($nume) {
		return round($nume);
}

function trunca2($nume) {
		return floor($nume);
}

function generaCodigoControl($llave,$aut,$fac,$nit,$fec,$mon){ 
    $fec=str_replace("-","",(str_replace("/","",$fec)));
	$nit = $nit=="" ? 0 :$nit;
	
	//print "<br><br>1  ".$aut." 2 ".$fac." 3 ".$nit." 4 ".$fec." 5 ".$mon." E  ".$llave. "<br><br>";
//	print($llave."  ".$aut."  ".$fac."  ".$nit."  ".$fec."  ".$mon."  ");
//	die;

/*
$dbc = new DBConnection;
$ses = new DBsession($dbc);
$sqldat = "SELECT FAC_LLAVE_DOS FROM PAC_PARAM_FACTURAS WHERE FAC_NRO_AUTORIZACION='".$aut."'";

$dset = $ses->execute ($sqldat);
$row  = $dset->Read();
*/

	//$llave = "442F3w5AggG7644D737asd4BH5677sasdL4%44643(3C3674F4"; //jalar este valor de la tabla
	/*** ***/
  
/////FIRST STEP
//adiciona los dos digitos Verhoeff

 $suma = 0;
	for ($i = 0;$i <= 1;$i++){
		if ($i == 0)
		{
			$first = ($fac . calcsum($fac));
		} 
		else 
		{
			$second = ($first . calcsum($first));
		}
	}
	$suma = $suma + $second;
	$fac_verhoeff = $second;
	
	for ($i = 0;$i <= 1;$i++){
		if ($i == 0)
		{
			$first = ($nit . calcsum($nit));
		} 
		else 
		{
			$second = ($first . calcsum($first));
		}
	}
	$suma = $suma + $second;
	$nit_verhoeff = $second;
	
	for ($i = 0;$i <= 1;$i++){
		if ($i == 0)
		{
			$first = ($fec . calcsum($fec));
		} 
		else 
		{
			$second = ($first . calcsum($first));
		}
	}
	$suma = $suma + $second;
	$fec_verhoeff = $second;
	
	$mon=trunca($mon);	
	for ($i = 0;$i <= 1;$i++){
		if ($i == 0)
		{
			$first = ($mon . calcsum($mon));
		} 
		else 
		{
			$second = ($first . calcsum($first));
		}
	}
	$suma = $suma + $second;
	$mon_verhoeff = $second;
	
// agregando 5 digitos	Verhoeff a la sumatoria
	for ($i = 0; $i <= 4; $i ++){
		if ($i == 0)
		{
			$first = ($suma . calcsum($suma));		
		} 
		if ($i == 1)
		{
			$second = ($first . calcsum($first));	
		}
		if ($i == 2)
		{
			$third  = ($second . calcsum($second));
		}
		if ($i == 3)
		{
			$fourth  = ($third . calcsum($third));
		}
		if ($i == 4)
		{
			$fifth  = ($fourth . Calcsum($fourth));
		}
	}
$totverhoeff = $fifth;

/////SECOND STEP
//segmentando la llave de dosificacion

	$largo = strlen($totverhoeff);
	$k1=0;
	for ($k = $largo-5;$k <= $largo-1;$k++){   //obtiene los 5 digitos verhoeff de la sumatoria y les suma 1
		$cod_verhoeff_sum[$k1] = $totverhoeff[$k] + 1;
		$k1++;
	}

	$c = 0;
	$limit_inf = 0;
	$limit_sup = $cod_verhoeff_sum[0];
	$llave_div_aux=""; // dan undefined
	while ($c <= 4){ //segmenta la llave segun los digitos verhoeff
		for ($k = $limit_inf;$k <= $limit_sup - 1 ;$k++){
			$llave_div_aux .= $llave[$k];
		}
		$llave_div[$c] = $llave_div_aux;	
		$limit_inf = $limit_sup;
			//$limit_sup = $limit_inf + $cod_verhoeff_sum[$c + 1];
			$limit_sup = isset($cod_verhoeff_sum[$c + 1]) ? $limit_inf + $cod_verhoeff_sum[$c + 1] : $limit_inf; // dan
		$c++;
		$llave_div_aux = "";
	}
	
	$aut .= $llave_div[0];
	$fac_verhoeff .= $llave_div[1];
	$nit_verhoeff .= $llave_div[2];
	$fec_verhoeff .= $llave_div[3];	
	$mon_verhoeff .= $llave_div[4];
	
///// THIRD STEP	
// aplicando ALLEGEDRC4

	$largo = strlen($totverhoeff);
	$cod_verhoeff=""; // dan undefined
	for ($k = $largo-5;$k <= $largo-1;$k++){	//saca el codigo verhoeff the la sumatoria sin sumarle 1 a los digitos resultantes
		$cod_verhoeff .= $totverhoeff[$k];
	}
	
	$param1 = $aut.$fac_verhoeff.$nit_verhoeff.$fec_verhoeff.$mon_verhoeff;
	$param2 = $llave.$cod_verhoeff;

	$cod_allegedrc4 = AllegedRC4($param1,$param2);

///// FOURTH STEP
  $sumatot =0; // dan
  $sumapar = array(0,0,0,0,0,0); // dan
	$largo = strlen($cod_allegedrc4);
	for ($k = 0;$k <= $largo-1;$k++){  //suma total
		$sumatot += ObtieneASCII($cod_allegedrc4[$k]);
	}
	
	for ($k = 0;$k <= $largo-1;$k = $k+5){  //primera suma parcial
		$sumapar[1] += ObtieneASCII($cod_allegedrc4[$k]);
	}
	for ($k = 1;$k <= $largo-1;$k = $k+5){  //segunda suma parcial
		$sumapar[2] += ObtieneASCII($cod_allegedrc4[$k]);
	}
	for ($k = 2;$k <= $largo-1;$k = $k+5){  //tercera suma parcial
		$sumapar[3] += ObtieneASCII($cod_allegedrc4[$k]);
	}
	for ($k = 3;$k <= $largo-1;$k = $k+5){  //cuarta suma parcial
		$sumapar[4] += ObtieneASCII($cod_allegedrc4[$k]);
	}
	for ($k = 4;$k <= $largo-1;$k = $k+5){  //quinta suma parcial
		$sumapar[5] += ObtieneASCII($cod_allegedrc4[$k]);
	}
	
/////  FIVETH STEP

	for($k = 0;$k <= 4;$k++){  //multiplica suma total por sumas parciales y divide cada resultado entre el digito verhoeff correspondiente
		$multi[$k] = trunca2(($sumatot * $sumapar[$k+1]) / $cod_verhoeff_sum[$k]);
	}
	$stotal =0; //dan
	for($k = 0;$k <= 4;$k++){  //suma los resultados parciales del anterior for
		$stotal = $stotal + $multi[$k];
	}

	$totbase64 = base64($stotal);
	
/////  SEXTH STEP

	$semicod = AllegedRC4($totbase64,$param2);
	$cod_control = ""; // dan
	for($k = 0;$k <= strlen($semicod)-1;$k++){
		$cod_control .= $semicod[$k];
		if (($k % 2) != 0){
			$cod_control .= "-";
		}
	}
	
	$cod_control = rtrim($cod_control,"-");
	//print($cod_control);
	
	return($cod_control);
}