<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css"/>
	<script src="//code.jquery.com/jquery-1.12.4.js"></script>
	<script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
  </head>
  <body>
  <input type="text" class="form-control" value="Enviando Factura..." aria-label="Username" aria-describedby="basic-addon1" id="txtEstado" disabled>
  </body>
  </html>

<?php
require_once 'config/Config.php';
require_once __DIR__ . '../../../mpdf/vendor/autoload.php';
require_once __DIR__ .'../../../phpqrcode/qrlib.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
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
$sql = "select dte as objeto_json from dtes where id =(".$data['idVenta'].")";
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
$codeContents = 'https://admin.factura.gob.sv/consultaPublica?ambiente=01&codGen='.$dte["dteJson"]["identificacion"]["codigoGeneracion"].'&fechaEmi='.$fecha;
QRcode::png($codeContents, $tempDir.'008_4.png', QR_ECLEVEL_L, 3, 4); 


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
Factura Comprobante crédito fiscal
<b></p></h5>
</td>
<td WIDTH='33%' align='right'>
<p align='rigth'><img src='".BASE_URL."assets/images/info.png' width='100px' ></p>
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
<p style ='font-size: 8px'><b>Código de generación:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["identificacion"]["codigoGeneracion"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Número control:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["identificacion"]["numeroControl"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Sello de recepción::</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["selloRecibido"]."</p>
</td>
</tr>
</table>
</td>
<td>
<img src='".BASE_URL."qrimages/008_4.png' width='100px' >
</td>
<td>

<table align = 'right'>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Modelo de Facturación:</p>
</td>
<td>";
if($dte["dteJson"]["identificacion"]["tipoModelo"] == 2 ){
$html .= "<p style ='font-size: 8px'>Diferido</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Tipo de Transmisión:</p>
</td>
<td>
<p style ='font-size: 8px'>Contingencia</p>
</td>
</tr>";
}else{
$html .= "<p style ='font-size: 8px'>Previo</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Tipo de Transmisión:</p>
</td>
<td>
<p style ='font-size: 8px'>Normal</p>
</td>
</tr>";	
}

$html .= "<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Fecha y Hora de Generación:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["fhProcesamiento"]."</p>
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
<h5><p align='center'><b>EMISOR<b></p></h5>
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
<td>
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
<td>
<p style ='font-size: 8px'><b>Nombre o razón social:</p>
</td>
<td>
<p style ='font-size: 8px'>".($dte["dteJson"]["receptor"]["nombre"])."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>NIT:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["receptor"]["nit"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>NRC:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["receptor"]["nrc"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Actividad económica:</p>
</td>
<td>
<p style ='font-size: 8px'>".$dte["dteJson"]["receptor"]["descActividad"]."</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Dirección:</p>
</td>
<td>
<p style ='font-size: 8px'>".($dte["dteJson"]["receptor"]["direccion"]["complemento"])."</p>
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
<td WIDTH='300'  class='d'><p style ='font-size: 10px'><b>Descripción<b></p></td>
<td class='d'><p style ='font-size: 10px'><b>Precio unitario <b></p></td>
<td  class='d'><p style ='font-size: 10px'><b>Ventas no sujetas<b></p></td>
<td  class='d'><p style ='font-size: 10px'><b>Ventas exentas<b></p></td>
<td  class='d'><p style ='font-size: 10px'><b>Ventas gravadas<b></p></td>
</tr>
";
$array = $dte["dteJson"]["cuerpoDocumento"];

foreach ($array as $value) {
foreach ( $data['medidas'] as $medida ){
if($value['uniMedida']==$medida['medida']){
$unidad = $medida['nombre_corto'];
}
}
$html .= "<tr><td class='d'><p class='b'>".($value['numItem'])."</b></td><td
class='d'><p class='b'>". ($value['cantidad'])."</p></td><td class='d'><p
class='b'>".$unidad."</p></td><td class='d'><p
class='b'>".($value['descripcion'])."<p/></td><td class='d'><p
class='b'>$".(number_format($value['precioUni'],2,'.',''))."</p></td><td class='d'><p
class='b'>$0.00</p></td><td class='d'><p
class='b'>$".(number_format($value['ventaExenta'],2,'.',''))."</p></td><td class='d'><p
class='b'>$".(number_format($value['ventaGravada'],2,'.',''))."</p></td></tr>";
}

$html .="<tr><td class='d' colspan='4' align='right'><p class='b'><b>Suma de Ventas : <b></p></td><td class='d'><p class='b'>$0.00</p></td><td class='d'><p class='b'>$0.00</p></td><td class='d'><p class='b'>$".(number_format($dte["dteJson"]["resumen"]["totalExenta"],2,'.',''))."</p></td><td class='d'><p class='b'>$".(number_format($dte["dteJson"]["resumen"]["totalGravada"],2,'.',''))."</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Suma Total de Operaciones :<b></p></td><td class='d'><p class='b'>$".(number_format($dte["dteJson"]["resumen"]["subTotalVentas"],2,'.',''))."</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Monto global Desc., Rebajas y otros a ventas no sujetas :<b></p></td><td class='d'><p class='b'>$0.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Monto global Desc., Rebajas y otros a ventas Exentas :<b></p></td><td class='d'><p class='b'>$0.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Monto global Desc., Rebajas y otros a ventas gravadas :<b></p></td><td class='d'><p class='b'>$".(number_format($dte["dteJson"]["resumen"]["descuGravada"],2,'.',''))."</p></td></tr>";

if($dte["dteJson"]["resumen"]["tributos"]!= null){
	$array = $dte["dteJson"]["resumen"]["tributos"];
foreach ($array as $value) {	
$html .="<tr><td class='d' colspan='7' align='right'><p class='b'><b>".($value['descripcion'])."<b></p></td><td class='d'><p class='b'>$".(number_format($value['valor'],2,'.',''))."</p></td></tr>";
}
}else{
$html .="<tr><td class='d' colspan='7' align='right'><p class='b'><b>".("Impuesto al Valor Agregado13%:")."<b></p></td><td class='d'><p class='b'>$".(number_format($value['valor'],2,'.',''))."</p></td></tr>";
}

$html .= "<tr><td class='d' colspan='7' align='right'><p class='b'><b>Sub-Total :<b></p></td><td class='d'><p class='b'>$".(number_format($dte["dteJson"]["resumen"]["subTotal"],2,'.',''))."</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>IVA Percibido :<b></p></td><td class='d'><p class='b'>$0.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>IVA Retenido :<b></p></td><td class='d'><p class='b'>$".(number_format($dte["dteJson"]["resumen"]["ivaRete1"],2,'.',''))."</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Retención Renta :<b></p></td><td class='d'><p class='b'>$0.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Monto Total de la Operación :<b></p></td><td class='d'><p class='b'>$".(number_format($dte["dteJson"]["resumen"]["montoTotalOperacion"],2,'.',''))."</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Total Otros montos no afectos :<b></p></td><td class='d'><p class='b'>$0.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Total a Pagar :<b></p></td><td class='d'><p class='b'>$".(number_format($dte["dteJson"]["resumen"]["totalPagar"],2,'.',''))."</p></td></tr>
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


$mpdf->Output('facturas/'.$dte["dteJson"]["identificacion"]["codigoGeneracion"].'.pdf', 'F'); //guarda a ruta
$jsonFile = fopen('facturas/'.$dte["dteJson"]["identificacion"]["codigoGeneracion"].'.json', 'w+') or die("Error creando archivo");
fwrite($jsonFile,$dteStr) or die("Error escribiendo en el archivo");
fclose($jsonFile);
//$fpdf->Output('D', 'report.pdf');

// PHP Mailer

 $mail = new PHPMailer(true);
 //Enable verbose debug output
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = HOST_SMTP;                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = USER_SMTP;                     //SMTP username
                $mail->Password   = CLAVE_SMTP;                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = PUERTO_SMTP; 
                $mail->setFrom(USER_SMTP, NOMBREEMISOR); //Sets the From email address for the message
$mail->AddAddress($dte["dteJson"]["receptor"]["correo"], "Cliente"); //Adds a"To" address
$mail->AddAddress($dte["dteJson"]["receptor"]["correo"], $dte["dteJson"]["receptor"]["nombre"]); //Adds a"To" address
$mail->WordWrap = 50; 
$mail->IsHTML(true); //Sets message type to HTML
$file = 'facturas/'.$dte["dteJson"]["identificacion"]["codigoGeneracion"].'.pdf';
$file2 = 'facturas/'.$dte["dteJson"]["identificacion"]["codigoGeneracion"].'.json';
$mail->AddAttachment($file); //Adds an attachment from a path on the filesystem
$mail->AddAttachment($file2); //Adds an attachment from a path on the filesystem

$mail->Subject = 'Credito fiscal '.$dte["dteJson"]["identificacion"]["codigoGeneracion"]; //Sets the Subject of the message
 $mail->Body    = 'Documento DTE Credito Fiscal';
 if($mail->Send()){
     echo "<script>document.getElementById('txtEstado').value= 'DTE : ".$dte["dteJson"]["identificacion"]["numeroControl"]." enviado con exito al Correo : ".$dte["dteJson"]["receptor"]["correo"]."';</script>";
 }else{
     echo "<script>document.getElementById('txtEstado').value= 'Error al enviar DTE';</script>";
 }
 
 
 
//PHP Mailer 




?>
