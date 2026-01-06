<?php
require_once 'config/Config.php';
require_once __DIR__ . '../../../mpdf/vendor/autoload.php';
//require_once __DIR__ .'../../../phpqrcode/qrlib.php';
$mpdf = new \Mpdf\Mpdf();
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
$sql = "select dte as objeto_json from dtesPedidos where id =(".$data['idVenta'].")";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
		$dte = $row["objeto_json"]; 	  
  }
} 
else {
  echo "0";
}
$conn->close();	
$dteStr = $dte;
$tempDir = "qrimages/";
$dte = json_decode($dte,true);
$fecha = substr($dte["fhProcesamiento"],0,10);
$fecha = substr($dte["fhProcesamiento"],6,4)."-".substr($dte["fhProcesamiento"],3,2)."-".substr($dte["fhProcesamiento"],0,2);
$codeContents = 'https://admin.factura.gob.sv/consultaPublica?ambiente=00&codGen='.$dte["dteJson"]["identificacion"]["codigoGeneracion"].'&fechaEmi='.$fecha;
//QRcode::png($codeContents, $tempDir.'008_4.png', QR_ECLEVEL_L, 3, 4); 


$html = "<style>
td.a {
        border: 1px solid black;
        border-collapse: collapse;
    }
p.b{
	font-size: 10px;
}

table.c, td.d, th {
  border: 1px solid;
  border-collapse: collapse;

}
table.c{
	border-collapse: collapse;
	 width: 100%; 
}
</style>
<table>
<tr>
<td WIDTH='33%'>
<p align='left'><img src='".BASE_URL."assets/images/logo.png' width='100px' ></p>
</td>
<td align='center' WIDTH='33%'>
<h5><p align='center'><b>
Documento tributario electrónico <br>
Factura de Exportación
<b></p></h5>
</td>
<td WIDTH='33%'>
&nbsp;
</td>
</tr>
</table>
<hr>
<div>
<table>
<tr>
<td>
<table align = 'left'>
<tr>
<td>
<p style ='font-size: 8px'><b>Numero de pedido:</p>
</td>
<td>
<p style ='font-size: 8px'>".$data['idVenta']."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Fecha de Generacion:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["fhProcesamiento"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>ipo documento:</p>
</td>
<td>
<p style ='font-size: 8px'>Factura de exportacion</p>
</td>
</tr>
</table>
</td>
<td>
</td>
<td>

<table align = 'right'>
<tr>
<td align='right'>
</td>
<td>
</td>
</tr>
<tr>
<td align='right'>
</td>
<td>
</td>
</tr>
</td>
</tr>
<tr>
<td align='right'>
</td>
<td>
</td>
</tr>
<tr>
<td align='right'>
</td>
<td>
</td>
</tr>
</table>

</td>
</tr>
</table>
</div>
<! – EMISOR –>

<div align='center'>
<table>
<tr>
<td align='center'>
<h5><p align='center'><b>EXPORTADOR(Emisor)<b></p></h5>
</td>
<td align='center'>
</td>
<td align='center'>
<h5><p align='center'><b>RECEPTOR<b></p></h5>
</td>
</tr>
<tr>
<td class='a'>
<table align = 'left'>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Nombre o razón social:</p>
</td>
<td>
<p style ='font-size: 8px'>".($dte["dteJson"]["emisor"]["nombre"])."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>NIT:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["nit"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>NRC:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["emisor"]["nrc"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Actividad económica:</p>
</td>
<td>
<p style ='font-size: 8px'>".($dte["dteJson"]["emisor"]["descActividad"])."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Dirección:</p>
</td>
<td>
<p style ='font-size: 8px'>".($dte["dteJson"]["emisor"]["direccion"]["complemento"])."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Número de teléfono:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["emisor"]["telefono"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Correo electrónico:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["emisor"]["correo"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Tipo de establecimiento:</p>
</td>
<td>
<p style ='font-size: 8px'>".nomEstablecimiento."</p>
</td>
</tr>
</table>
</td>

<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>

<! – RECEPTOR –>

<td class ='a'>

<table align = 'left'>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Nombre o razón social:</p>
</td>
<td>
<p style ='font-size: 8px'>".($dte["dteJson"]["receptor"]["nombre"])."</p>
</td>
</tr>
<tr>
<td align='right'>";
if(strlen($dte["dteJson"]["receptor"]["numDocumento"])<11){
$html .= "<p style ='font-size: 8px'><b>DUI:</p>";
}else{
$html .= "<p style ='font-size: 8px'><b>NIT:</p>";	
}
$html .="</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["receptor"]["numDocumento"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Número de teléfono:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["receptor"]["telefono"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Correo electrónico:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["receptor"]["correo"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Dirección:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["receptor"]["complemento"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Pais :</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["receptor"]["nombrePais"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Actividad :</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["receptor"]["descActividad"]."</p>
</td>
</tr>
</table>

</td>
</tr>

</table>

</div>
<br>

<! – RECEPTOR –>

<div>

<table class='c'>
<tr>
<td WIDTH='25' class='d'><p style ='font-size: 10px'><b>N°<b></p></td>
<td WIDTH='25'  class='d'><p style ='font-size: 10x'><b>Cantidad<b></p></td>
<td WIDTH='25'  class='d'><p style ='font-size: 10px'><b>Unidad<b></p></td>
<td WIDTH='400'  class='d'><p style ='font-size: 10px'><b>Descripción<b></p></td>
<td WIDTH='50' class='d'><p style ='font-size: 10px'><b>Precio unitario <b></p></td>
<td WIDTH='50'  class='d'><p style ='font-size: 10px'><b>Ventas Afectas<b></p></td>
</tr>
";
$array = $dte["dteJson"]["cuerpoDocumento"];

foreach ($array as $value) {


$html .= "<tr><td  class='d'><p class='b'>".($value['numItem'])."</b></td><td  class='d'><p class='b'>". ($value['cantidad'])."</p></td><td  class='d'><p class='b'>Unidad</p></td><td  class='d'><p class='b'>".($value['descripcion'])."<p/></td><td  class='d'><p class='b'>$".(number_format($value['precioUni'],2,'.',''))."</p></td><td  class='d'><p class='b'>$".(number_format($value['ventaGravada'],2,'.',''))."</p></td></tr>";

	
}
$html .="<tr><td class='d' colspan='5' align='right'><p class='b'><b>Total Afectas : <b></p></td></td><td class='d'><p class='b'>$".(number_format($dte["dteJson"]["resumen"]["totalGravada"],2,'.',''))."</p></td></tr>
<tr><td class='d' colspan='5' align='right'><p class='b'><b>Monto global Desc., Rebajas de operaciones afectas :<b></p></td><td class='d'><p class='b'>$".(number_format($dte["dteJson"]["resumen"]["totalDescu"],2,'.',''))."</p></td></tr>
<tr><td class='d' colspan='5' align='right'><p class='b'><b>Total a Pagar:<b></p></td><td class='d'><p class='b'>$".(number_format($dte["dteJson"]["resumen"]["totalPagar"],2,'.',''))."</p></td></tr>
</table>
</div>

<div>
<table>
<tr><td align='right'><p class='b'><b>Valor en Letras :</b></p></td><td><p class='b'> ".($dte["dteJson"]["resumen"]["totalLetras"])."</p></td></tr>";
if($dte["dteJson"]["resumen"]["condicionOperacion"] == 1){
$html .= "<tr><td align='right'><p class='b'><b>Condición de la Operación: </b></p></td><td><p class='b'>CONTADO</p></td></tr>";
}else{
$html .= "<tr><td align='right'><p class='b'><b>Condición de la Operación: </b></p></td><td><p class='b'>CREDITO</p></td></tr>";
}

$html .= "<tr><td align='right'><p class='b'><b>Observaciones :</b></p></td><td><p class='b'>".($dte["dteJson"]["extension"]["observaciones"])."</p></td></tr>
</table>
</div>
";
$mpdf->WriteHTML($html);


$mpdf->Output();


?>
