<?php
require_once 'config/Config.php';
require_once __DIR__ . '../../../mpdf/vendor/autoload.php';
require_once __DIR__ .'../../../phpqrcode/qrlib.php';
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
$sql = "select p.*,v.pago,v.productos, c.nombre from planPagos p join ventas v on p.id_venta=v.id join clientes c on p.id_cliente=c.id where id_plan =(".$data['idPlan'].")";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
		$plan = $row["detalle_plan"];
		$prima = $row["prima"];
		$tasa = $row["interes"];
		$interes = ($row["interes"] * $row["monto"])/100;
		$monto = $row["monto"];
		$totalCapital = $row["monto"];
		$seguro = $row["seguro"] * $row["plazo"];
		$fecha = $row["fecha"];
		$montoTotalPlan = $row["montoTotalPlan"];
		$nombre = $row["nombre"];
  }
} 
else {
  echo "0";
}

$planDetalle = json_decode($plan,true);

$html = "<style>
td.a {
        border: 1px solid black;
        border-collapse: collapse;
    }
p.b{
	font-size: 15px;
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
<td WIDTH='100'>
<p align='left'><img src='".BASE_URL."assets/images/logo.png' width='100px' ></p>
</td>
<td align='right' WIDTH='50%'>
<h5><p style ='font-size: 15px' align='center'><b>
Plan de pagos <br>
<b></p></h5>
</td>
<td WIDTH='33%'>
&nbsp;
</td>
</tr>
</table>
<hr>
<br>
<div>
<table>
<tr>
<td ><p style ='font-size: 15px'><b>Cliente : ".$nombre."<b></p></td>
</tr>
<tr>
<td><p style ='font-size: 15px'><b>Referencia de plan de pago : # ".$data['idPlan']."<b></p></td>
</tr>

</table>
<br>
</div>
<div>

<table class='c'>
<tr>
<td WIDTH='100' class='d'><p style ='font-size: 15px'><b>N°<b></p></td>
<td WIDTH='100'  class='d'><p style ='font-size: 15x'><b>Vencimiento de cuota<b></p></td>
<td WIDTH='100'  class='d'><p style ='font-size: 15px'><b>Capital<b></p></td>
<td WIDTH='100'  class='d'><p style ='font-size: 15px'><b>Interes<b></p></td>
<td WIDTH='100'  class='d'><p style ='font-size: 15px'><b>Seguro<b></p></td>
<td WIDTH='100'  class='d'><p style ='font-size: 15px'><b>Monto Cuota<b></p></td>
<td WIDTH='100'  class='d'><p style ='font-size: 15px'><b>Saldo<b></p></td>
</tr>";

foreach ($planDetalle as $detalle) {
$n ++;
$totalInteres = $detalle['interes'] + 	$totalInteres;
$capital = $detalle['capital'] +  $capital; 
$saldo = $totalCapital - $capital;
$html .= "<tr><td  class='d'><p class='b'>".$detalle['numCuota']."</b></td><td  class='d'><p class='b'>". ($detalle['fechaPago'])."</p></td><td  class='d'><p class='b'>$".$detalle['capital']."</p></td><td  class='d'><p class='b'>$".(number_format($detalle['interes'],2,'.',''))."<p/></td><td  class='d'><p class='b'>$".(number_format($detalle['Seguro'],2,'.',''))."</p></td><td  class='d'><p class='b'>$".$detalle['Cuota']."</p></td><td  class='d'><p class='b'>$".(number_format($detalle['abonoCapital'],2,'.',''))."</p></td></tr>";	
}
$montoTotalPlan = $totalInteres + $capital + $seguro ;
$html .= "<tr><td  class='d' colspan=2 ><p class='b'><b>Totales<b></p></td><td  class='d'><p class='b'>$".$monto."</p></td><td  class='d'><p class='b'>$".(number_format($totalInteres,2,'.',''))."<p/></td><td  class='d'><p class='b'>$".(number_format($seguro,2,'.',''))."</p></td><td  class='d'><p class='b'>$".(number_format($montoTotalPlan,2,'.',''))."</p></td></tr>";

$html .= "</table>
</div>
<br>
<div>
<table class='c'>
<tr>
<td WIDTH='100' class='d'><p style ='font-size: 15px'><b>Prima<b></p></td>
<td WIDTH='100'  class='d'><p style ='font-size: 15x'><b>Monto<b></p></td>
<td WIDTH='100'  class='d'><p style ='font-size: 15x'><b>Tasa de interes<b></p></td>
<td WIDTH='100'  class='d'><p style ='font-size: 15x'><b>Fecha<b></p></td>
</tr>
<tr>
<td WIDTH='100' class='d'><p style ='font-size: 15px'><b>$".$prima."<b></p></td>
<td WIDTH='100'  class='d'><p style ='font-size: 15x'><b>$".$monto."<b></p></td>
<td WIDTH='100'  class='d'><p style ='font-size: 15x'><b>".$tasa."%<b></p></td>
<td WIDTH='100'  class='d'><p style ='font-size: 15x'><b>".$fecha."<b></p></td>
</tr>
</table>
</div>";
$mpdf->WriteHTML($html);


$mpdf->Output();


?>
