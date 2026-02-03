<?php
require_once 'config/Config.php';
require_once __DIR__ . '../../../mpdf/vendor/autoload.php';
//require_once __DIR__ .'../../../phpqrcode/qrlib.php';
$finicio = $data['finicio'];
$ffin = $data['ffin'];
$documento = $data['documento'];
$anio = $data['anio'];
$mesLetras = $data['mesLetras'];

$servername = HOST;
$username = USER;
$password = PASS;
$dbname = DBNAME;

									// Create connection 
$conn = new mysqli($servername, $username, $password, $dbname);
									// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
	}

if($documento=='contribuyentes'){

$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);


$html = "<style>
td.a {
        border: 1px solid black;
        border-collapse: collapse;
    }
p.b{
	font-size: 20px;
}

table.c, td.d, th {
  border: 1px solid;
  border-collapse: collapse;
}
td.g{
	border-bottom:0;
}
table.c{
	border-collapse: collapse;
} 
</style>
<table>
<tr>
<td WIDTH='33%'>
<p align='left'></p>
</td>
<td align='center' WIDTH='33%'>
<h5><p align='center'><b>
".$data["empresa"]["nombre"]."
<b></p></h5>
</td>
<td WIDTH='33%'>
&nbsp;
</td>
</tr>
</table>

<table>
<tr>
<td><p style ='font-size: 15px'>LIBRO DE VENTAS AL CONTRIBUYENTE</p></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><p style ='font-size: 15px'>REGISTRO NO : ".$data["empresa"]["registro"]."</p></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><p style ='font-size: 15px'>NIT NUMERO : ".$data["empresa"]["ruc"]."</p></td>
</tr>
<tr>
<td><p style ='font-size: 15px'>MES DE : ".$mesLetras."        </p></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><p style ='font-size: 15px'>  AÑO : ".$anio."      </p></td>
</tr>
</table>

<div>

<table class='c'>
<tr>
<td WIDTH='25' rowspan ='3' class='d'><p style ='font-size: 15px'>NUMERO CORRELATIVO</td>
<td WIDTH='25' rowspan ='3' class='d'><p style ='font-size: 15px'>FFECHA DE EMISION</td>
<td WIDTH='25' rowspan ='3' class='d'><p style ='font-size: 15px'>NUMERO CORRELATIVO PRE-IMPRESO</td>
<td WIDTH='35' rowspan ='3' class='d'><p style ='font-size: 15px'>N CONTROL INTERNO SISTEMA FORMULARIO UNICO</td>
<td WIDTH='100' rowspan ='3' class='d'><p style ='font-size: 15px'>NOMBRE DE CLIENTE, MANDATORIO O MANDANTE</td>
<td WIDTH='25' rowspan ='3' class='d'><p style ='font-size: 15px'>N.R.C.</td>
<td WIDTH='400' class='d' colspan='14' align='center'><p style ='font-size: 15px'>OPERACIONES DE VENTAS</td>
</tr>
<tr>
<td WIDTH='400' class='d' colspan='3' align='center'><p style ='font-size: 15px'>PROPIAS</td>
<td WIDTH='400' class='d' colspan='3' align='center'><p style ='font-size: 15px'>A CUENTA DE TERCEROS</td>
<td WIDTH='25' class='d' rowspan ='2'><p style ='font-size: 15px'>IVA RETENIDO</td>
<td WIDTH='25' class='d' rowspan ='2'><p style ='font-size: 15px'>IMPUESTO PERCIBIDO</td>
<td WIDTH='25' class='d' rowspan ='2'><p style ='font-size: 15px'>VENTAS TOTALES</td>
</tr>
<tr>
<td WIDTH='25' class='d'><p style ='font-size: 15px'>EXENTAS</td>
<td WIDTH='25' class='d'><p style ='font-size: 15px'>INTERNAS GRAVADAS</td>
<td WIDTH='25' class='d'><p style ='font-size: 15px'>DEBITO FISCAL</td>
<td WIDTH='25' class='d'><p style ='font-size: 15px'>EXENTAS</td>
<td WIDTH='25' class='d'><p style ='font-size: 15px'>INTERNAS GRAVADAS</td>
<td WIDTH='25' class='d'><p style ='font-size: 15px'>DEBITO FISCAL</td>
</tr>";

$sql = "SELECT v.*, c.*, v.fecha as fechaVenta, v.estado as estadoVenta FROM ventas v INNER JOIN clientes c ON v.id_cliente = c.id WHERE v.fecha BETWEEN '".$finicio."' AND '".$ffin."' AND (docuemi = 'Nota de credito' OR docuemi = 'CREDITO FISCAL') ORDER BY v.id ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
	
	$totalGravadas = 0;
	$totalExentas = 0;
	$totalIva = 0;
	$totalTotales = 0;	
	$ivaRetenidoTotal = 0;
	$totales_Retencion =0;
								  // output data of each row
while($row = $result->fetch_assoc()) {
$c+=1;
	if($row["numdocu"]=='03' ){
	    if($row["estadoVenta"]=='1'){
$retencion = $row["reteIva"];
$totalExentas += $row["vExentas"];
$totalGravadas += $row["vGravadas"];
$totalIva += $row["vIva"]; 
$ivaRetenidoTotal += $row["reteIva"];
$total = $row["vExentas"] + $row["vGravadas"] + $row["vIva"];
$totales_Retencion = $total + $row["reteIva"];
$totalTotales += $total;
$totalTotales_Retencion = $totalTotales + $ivaRetenidoTotal;
$html .= "<tr>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$c."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["fechaVenta"]."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".str_replace("-","",$row["uuid"])."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>-- --</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["nombre"]."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".str_replace("-","",$row["num_identidad"])."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["vExentas"]."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["vGravadas"]."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["vIva"]."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".(number_format($row["reteIva"],2,'.',''))."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".(number_format($total,2,'.',''))."</td>";
$html .= "</tr>";
	        }elseif($row["estadoVenta"]=='0'){
$html .= "<tr>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$c."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["fechaVenta"]."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".str_replace("-","",$row["uuid"])."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>-- --</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>ANULADA</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".str_replace("-","",$row["num_identidad"])."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>"; 
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>"; 
$html .= "</tr>";
	}
	    

	}elseif($row["numdocu"]=='05'){
	  if($row["estadoVenta"]=='1'){
		$total = $row["vExentas"] + $row["vGravadas"] + $row["vIva"];
$html .= "<tr>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$c."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["fechaVenta"]."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".str_replace("-","",$row["uuid"])."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>-- --</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["nombre"]."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".str_replace("-","",$row["num_identidad"])."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["vExentas"]."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>(".$row["vGravadas"].")</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>(".$row["vIva"].")</td>"; 
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>(".(number_format($total,2,'.','')).")</td>"; 
$html .= "</tr>";
$totalExentas -= $row["vExentas"];
$totalGravadas -= $row["vGravadas"];
$totalIva -= $row["vIva"];
$totalTotales -= $total;
}elseif($row["estadoVenta"]=='0'){
$html .= "<tr>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$c."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["fechaVenta"]."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>01</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".str_replace("-","",$row["uuid"])."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".str_replace("-","",$row["num_identidad"])."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>ANULADA</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>"; 
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>"; 
$html .= "</tr>";
	}


	}
	
	
}
} else {
echo "0";
									}
$conn->close();
$totalTotales_Retencion = $totalTotales - $ivaRetenidoTotal;
$html .= "<tr>
<td WIDTH='400' class='d' colspan='6' align='center'><b><p style ='font-size: 15px'>TOTALES DEL MES ......<b></td>
<td class='d'><p style ='font-size: 15px'>".$totalExentas."</td>
<td class='d'><p style ='font-size: 15px'>".$totalGravadas."</td>
<td class='d'><p style ='font-size: 15px'>".round($totalGravadas*0.13,2)."</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($ivaRetenidoTotal,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalTotales,2,'.',''))."</td>


</tr>";
$html .= "</table>
</div>
";

$html .="
<br>
<br>
<br>
<table>
<tr>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
<hr>
&nbsp;&nbsp;&nbsp;&nbsp; Elaborado por &nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
<hr>
&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; Contador &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>

</table>
";

$mpdf->WriteHTML($html);


$mpdf->Output(); //guarda a ruta
}elseif($documento=='consumidor'){
	
	$mpdf = new \Mpdf\Mpdf();


$html = "<style>
td.a {
        border: 1px solid black;
        border-collapse: collapse;
    }
p.b{
	font-size: 20px;
}

table.c, td.d, th {
  border: 1px solid;
  border-collapse: collapse;
}
td.g{
	border-bottom:0;
}
table.c{
	border-collapse: collapse;
} 
</style>
<table>
<tr>
<td WIDTH='33%'>
<p align='left'></p>
</td>
<td align='center' WIDTH='33%'>
<h5><p align='center'><b>
".$data["empresa"]["nombre"]."
<b></p></h5>
</td>
<td WIDTH='33%'>
&nbsp;
</td>
</tr>
</table>

<table>
<tr>
<td><p style ='font-size: 15px'>LIBRO DE VENTAS AL CONSUMIDOR &nbsp;&nbsp;&nbsp;</p></td>
<td><p style ='font-size: 15px'>&nbsp;&nbsp;REGISTRO NO : ".$data["empresa"]["registro"]."</p></td>
</tr>
<tr>
<td><p style ='font-size: 15px'>NIT NUMERO : ".$data["empresa"]["ruc"]."</p></td>
</tr>
<td><p style ='font-size: 15px'>MES DE : ".$mesLetras."        </p></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><p style ='font-size: 15px'>  AÑO : ".$anio."      </p></td>
</tr>
</table>

<table class='c'>
<tr>
<td WIDTH='35' rowspan ='2' class='d'><p style ='font-size: 15px'>Fecha</td>
<td WIDTH='70' rowspan ='2' colspan='4' class='d'><p style ='font-size: 10x'>Cod No. Caja Suc Regist</td>
<td WIDTH='35' rowspan ='2' class='d'><p style ='font-size: 15px'>Del Comprobante</td>
<td WIDTH='35' rowspan ='2' class='d'><p style ='font-size: 15px'>Al Comprobante</td>
<td WIDTH='300' class='d' colspan='4' align='center'><p style ='font-size: 15px'>VENTAS</td>
<td WIDTH='35' rowspan ='2' class='d'><p style ='font-size: 15px'>Ventas Totales</td>
<td WIDTH='35' rowspan ='2' class='d'><p style ='font-size: 15px'>Iva Ret</td>
</tr>

<tr>
<td WIDTH='15' class='d'><p style ='font-size: 15px'>No Sujetas</td>
<td WIDTH='15' class='d'><p style ='font-size: 15px'>Exentas</td>
<td WIDTH='15' class='d'><p style ='font-size: 15px'>Gravadas</td>
<td WIDTH='15' class='d'><p style ='font-size: 15px'>Exporta</td>
</tr>";

$sql = "SELECT count(id) as total, fecha from ventas where fecha BETWEEN '".$finicio."' AND '".$ffin."' and docuemi = 'FACTURA' GROUP by fecha ASC;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$ventasNetasGravadas=0; 
	$totalRetencion = 0;
while($row = $result->fetch_assoc()) {

$totalDia = $row["total"]; 
$fechaActual = $row["fecha"];
$sql1 = "SELECT v.*, c.nombre, c.identidad FROM ventas v INNER JOIN clientes c ON v.id_cliente = c.id WHERE v.fecha BETWEEN '".$fechaActual."' AND '".$fechaActual."' AND (docuemi = 'FACTURA' AND v.estado !=0) ORDER BY v.fecha ";
$result1 = $conn->query($sql1);
if ($result1->num_rows > 0) {
	$totalGravadas = 0;
	$totalExentas = 0;
	$totalIva = 0;
	$totalTotales = 0;		
    $totalReten = 0;
	$c=0;
while($row1 = $result1->fetch_assoc()) {
		$c+=1;
	if($c==1){
		$fechaIni = $row1["fecha"];
		$controlIntIni = str_replace("-","",$row1["uuid"]);
	}
$ventasNetasGravadas += $row1["vGravadas"];
$totalExentas += $row1["vExentas"];
$totalGravadas += $row1["vGravadas"];
$totalReten += $row1["reteIva"];
$totalRetencion += $row1["reteIva"];
$controlIntFinal = str_replace("-","",$row1["uuid"]);


	
}	
$totales = $totalExentas + $totalGravadas;
 
$html .= "<tr>
<td class='d'><p style ='font-size: 15px'>".$fechaActual."</td>
<td class='d'><p style ='font-size: 15px'></td>
<td class='d'><p style ='font-size: 15px'>01</td>
<td class='d'><p style ='font-size: 15px'>000</td>
<td class='d'><p style ='font-size: 15px'>FA</td>
<td class='d'><p style ='font-size: 15px'>".$controlIntIni."</td>
<td class='d'><p style ='font-size: 15px'>".$controlIntFinal."</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalExentas,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalGravadas,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totales,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalReten,2,'.',''))."</td>
</tr>";
}
	
	
	
}	
}




$sql = "SELECT v.*, c.nombre, c.identidad FROM ventas v INNER JOIN clientes c ON v.id_cliente = c.id WHERE v.fecha BETWEEN '".$finicio."' AND '".$ffin."' AND (docuemi = 'EXPORTACION' AND v.estado !=0) ORDER BY v.fecha ";
$b = 0;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	
	$totalExportacion = 0;
	$totalExentas = 0;
	$totalIva = 0;
	$totalTotales = 0;									  // output data of each row
while($row = $result->fetch_assoc()) {
	$b+=1;
	if($b==1){
		$controlIntIni = str_replace("-","",$row["numeroControlDte"]);
	}

$totalExportacion += $row["vGravadas"];
$controlIntFinal = str_replace("-","",$row["numeroControlDte"]);

   


}
}
$totalesExportacion = $totalExentas + $totalExportacion;
if($b>0){
$html .= "<tr>
<td class='d'><p style ='font-size: 15px'>".$ffin."</td>
<td class='d'><p style ='font-size: 15px'></td>
<td class='d'><p style ='font-size: 15px'>01</td>
<td class='d'><p style ='font-size: 15px'>000</td>
<td class='d'><p style ='font-size: 15px'>FA</td>
<td class='d'><p style ='font-size: 15px'>".$controlIntIni."</td>
<td class='d'><p style ='font-size: 15px'>".$controlIntFinal."</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalExentas,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalExportacion,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalExportacion,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
</tr>
";
}
$html .= "
<tr>
<td WIDTH='400' class='d' colspan='7' align='center'><b><p style ='font-size: 15px'>TOTALES DEL MES ......<b></td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalExentas,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($ventasNetasGravadas,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalesExportacion,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($ventasNetasGravadas + $totalesExportacion,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
</tr>
<tr> 
<td class='d' colspan='8' align='right'><p style ='font-size: 15px'>VENTAS NETAS GRAVADAS LOCAL</td>
<td class='d' colspan='5'><p style ='font-size: 15px'>$".(number_format(($ventasNetasGravadas/1.13),2,'.',''))."</td>
</tr>
<tr>
<td class='d' colspan='8' align='right'><p style ='font-size: 15px'>DEBITO FISCAL</td>
<td class='d' colspan='5'><p style ='font-size: 15px'>$".(number_format($ventasNetasGravadas-($ventasNetasGravadas/1.13),2,'.',''))."</td>
</tr>
<tr>
<td class='d' colspan='8' align='right'><p style ='font-size: 15px'>TOTAL VENTAS GRAVADAS</td>
<td class='d' colspan='5'><p style ='font-size: 15px'>$".(number_format($ventasNetasGravadas,2,'.',''))."</td>
</tr>
<tr>
<td class='d' colspan='8' align='right'><p style ='font-size: 15px'>EXPORTACIONES</td>
<td class='d' colspan='5'><p style ='font-size: 15px'>$".(number_format($totalesExportacion,2,'.',''))."</td>
</tr>
<tr>
<td class='d' colspan='8' align='right'><p style ='font-size: 15px'>EXENTAS</td>
<td class='d' colspan='5'><p style ='font-size: 15px'>$".(number_format($totalExentas,2,'.',''))."</td>
</tr>
<tr>
<td class='d' colspan='8' align='right'><p style ='font-size: 15px'>VENTAS NO SUJETAS</td>
<td class='d' colspan='5'><p style ='font-size: 15px'>$0.00</td>
</tr>
<tr>
<td class='d' colspan='8' align='right'><p style ='font-size: 15px'>TOTAL</td>
<td class='d' colspan='5'><p style ='font-size: 15px'>$".(number_format($ventasNetasGravadas,2,'.',''))."</td>
</tr>
</table>";
$html .="
<br>
<br>
<br>
<table>
<tr>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
<hr>
&nbsp;&nbsp;&nbsp;&nbsp; Elaborado por &nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
<hr>
&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; Contador &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>
</table>";

$mpdf->WriteHTML($html);


$mpdf->Output(); //guarda a ruta
	
}if($documento=='compras'){

$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);


$html = "<style>
td.a {
        border: 1px solid black;
        border-collapse: collapse;
    }
p.b{
	font-size: 20px;
}

table.c, td.d, th {
  border: 1px solid;
  border-collapse: collapse;
}
td.g{
	border-bottom:0;
}
table.c{
	border-collapse: collapse;
} 
</style>
<table>
<tr>
<td WIDTH='33%'>
<p align='left'></p>
</td>
<td align='center' WIDTH='33%'>
<h5><p align='center'><b>
".$data["empresa"]["nombre"]."
<b></p></h5>
</td>
<td WIDTH='33%'>
&nbsp;
</td>
</tr>
</table>

<table>
<tr>
<td><p style ='font-size: 15px'>LIBRO DE COMPRAS</p></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><p style ='font-size: 15px'>REGISTRO NO : ".$data["empresa"]["registro"]."</p></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><p style ='font-size: 15px'>NIT NUMERO : ".$data["empresa"]["ruc"]."</p></td>
</tr>
<tr>
<td><p style ='font-size: 15px'>MES DE : ".$mesLetras."        </p></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><p style ='font-size: 15px'>  AÑO : ".$anio."      </p></td>
</tr>
</table>

<div>

<table class='c'>
<tr>
<td WIDTH='25' rowspan ='2' class='d'><p style ='font-size: 15px'>Numero Correlativo</td>
<td WIDTH='25' rowspan ='2' class='d'><p style ='font-size: 10x'>Fecha de Emision</td>
<td WIDTH='35' rowspan ='2' class='d'><p style ='font-size: 15px'>Numero de Documento</td>
<td WIDTH='25' rowspan ='2' class='d'><p style ='font-size: 15px'>N.R.C.</td>
<td WIDTH='25' rowspan ='2' class='d'><p style ='font-size: 15px'>NIT,CIP o DUI del sujeto excluido</td>
<td WIDTH='25' rowspan ='2' class='d'><p style ='font-size: 15px'>Nombre del Proveedor</td>
<td WIDTH='100' class='d' colspan='2' align='center'><p style ='font-size: 15px'>COMPRAS EXENTAS</td>
<td WIDTH='100' class='d' colspan='3' align='center'><p style ='font-size: 15px'>COMPRAS GRAVADAS</td>
<td WIDTH='25' rowspan ='2' class='d'><p style ='font-size: 10x'>Total Compras</td>
<td WIDTH='35' rowspan ='2' class='d'><p style ='font-size: 15px'>Impuesto Retenido a Terceros</td>
<td WIDTH='25' rowspan ='2' class='d'><p style ='font-size: 15px'>Iva Percibido</td>
<td WIDTH='25' rowspan ='2' class='d'><p style ='font-size: 15px'>Compras a Sujetos Excluidos</td>
</tr>
<tr>
<td WIDTH='50' class='d' align='center'><p style ='font-size: 15px'>Internas(Locales)</td>
<td WIDTH='50' class='d' align='center'><p style ='font-size: 15px'>Importaciones e Internacionales</td>
<td WIDTH='50' class='d' align='center'><p style ='font-size: 15px'>Internas (Locales)</td>
<td WIDTH='50' class='d' align='center'><p style ='font-size: 15px'>Importaciones e Internacionales</td>
<td WIDTH='50' class='d' align='center'><p style ='font-size: 15px'>Credito Fiscal</td>
</tr>";

$sql = "SELECT v.*, c.*, v.fecha as fechaCompra FROM ventas2 v INNER JOIN clientes2 c ON v.id_cliente2 = c.id WHERE v.fecha BETWEEN '".$finicio."' AND '".$ffin."' AND (docuemi = 'COMPRA' or docuemi = 'SUJETO EXCLUIDO' or docuemi = 'NOTA DE CREDITO'   ) AND (v.estado = 1) ORDER BY v.fecha ";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
	$totalGravadas = 0;
	$totalExentas = 0;
	$totalIva = 0;
	$totalTotales = 0;	
	$totalTotalesExluidos=0;
								  // output data of each row
while($row = $result->fetch_assoc()) {
$c+=1;
$totalExentas += $row["vExentas"];


$totalPercepcion1 += $row["percepcion1"];
$totalPercepcion2 += $row["percepcion2"];
$total = $row["vExentas"] + $row["vGravadas"] + $row["vIva"] +  $row["percepcion1"] +  $row["percepcion2"]  ;
$totalTotales += $total;
$html .= "<tr>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$c."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["fechaCompra"]."</td>";
if($row["docuemi"]=="SUJETO EXCLUIDO"){
$html .= "<td class='d'><p style ='font-size: 15px'>".str_replace("-","",$row["uuid"])."</td>";		
}else{
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["numc"]."</td>";	
}
$html .= "<td class='d'><p style ='font-size: 15px'>".str_replace("-","",$row["num_identidad"])."</td>";
if($row["docuemi"]=="SUJETO EXCLUIDO"){
$html .= "<td class='d'><p style ='font-size: 15px'>".str_replace("-","",$row["DUI"])."</td>";	
}else{
$html .= "<td class='d'><p style ='font-size: 15px'></td>";	
}
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["nombre"]."</td>";

if($row["docuemi"]=="SUJETO EXCLUIDO"){
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
}else{
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["vExentas"]."</td>";	
}
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
if($row["docuemi"]=="SUJETO EXCLUIDO"){
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
}else{
if($row["docuemi"]=="NOTA DE CREDITO"){
$html .= "<td class='d'><p style ='font-size: 15px'>(".$row["vGravadas"].")</td>";	
$totalGravadas -= $row["vGravadas"];
$totalTotales  -= ($total*2);
}else{
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["vGravadas"]."</td>";	
$totalGravadas += $row["vGravadas"];
}
	
}
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
if($row["docuemi"]=="SUJETO EXCLUIDO"){
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";
}else{
if($row["docuemi"]=="NOTA DE CREDITO"){
$html .= "<td class='d'><p style ='font-size: 15px'>(".$row["vIva"].")</td>";	
$totalIva -= $row["vIva"];	
}else{
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["vIva"]."</td>";	
$totalIva += $row["vIva"];
}	
	
}
if($row["docuemi"]=="NOTA DE CREDITO"){
$html .= "<td class='d'><p style ='font-size: 15px'>(".(number_format($total,2,'.','')).")</td>";	
}else{
$html .= "<td class='d'><p style ='font-size: 15px'>".(number_format($total,2,'.',''))."</td>";	
}
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["percepcion1"]."</td>";
$html .= "<td class='d'><p style ='font-size: 15px'>".$row["percepcion2"]."</td>";
if($row["docuemi"]=="SUJETO EXCLUIDO"){
$totalTotalesExluidos += $row["total"];
$html .= "<td class='d'><p style ='font-size: 15px'>".(number_format((($row["total"]/90)*100),2,'.',''))."</td>";	
}else{
$html .= "<td class='d'><p style ='font-size: 15px'>0.00</td>";	
}
$html .= "</tr>";
}
}
$conn->close();
$html .= "<tr>
<td WIDTH='400' class='d' colspan='6' align='center'><b><p style ='font-size: 15px'>TOTALES DEL MES ......<b></td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalExentas,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalGravadas,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalIva,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalTotales,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>0.00</td>
<td class='d'><p style ='font-size: 15px'>".(number_format($totalPercepcion2,2,'.',''))."</td>
<td class='d'><p style ='font-size: 15px'>".(number_format(($totalTotalesExluidos/90)*100,2,'.',''))."</td>
</tr>";
$html .= "</table>
</div>
";


$html .="
<br>
<br>
<br>
<table>
<tr>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
<hr>
&nbsp;&nbsp;&nbsp;&nbsp; Elaborado por &nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>
<hr>
&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; Contador &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;
</td>
</tr>

</table>
";


$mpdf->WriteHTML($html);


$mpdf->Output(); //guarda a ruta
}


?>
