<?php
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

$html = "
<style>
td.a {
        border: 1px solid black;
        border-collapse: collapse;
    }
p.b{
	font-size: 12px;
}

table.c, td.d, th {
  border: 1px solid;
  border-collapse: collapse;
}
table.c{
	border-collapse: collapse;
}
</style>
<h5><p align='center'><b>
Documento tributario electronico <br>
Factura Comprobante credito fiscal
<b></p></h5>
<hr>
<div>
<table>
<tr>
<td>
<table align = 'left'>
<tr>
<td>
<p style ='font-size: 8px'><b>Codigo de generacion:</p>
</td>
<td>
<p style ='font-size: 8px'>655DAAA6-02AE-4274-9ACB-E49926FDC3D1</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Numero control:</p>
</td>
<td>
<p style ='font-size: 8px'>DTE-11-00000000-300000000000027</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Sello de recepcion:</p>
</td>
<td>
<p style ='font-size: 8px'>2023A21CAA205ACF49A3A397B1047B733FC28GIB</p>
</td>
</tr>
</table>
</td>
<td>
<img src='008_4.png' width='100px' >
</td>
<td>

<table align = 'right'>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Modelo de Facturacion:</p>
</td>
<td>
<p style ='font-size: 8px'>Previo</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Tipo de Transmision:</p>
</td>
<td>
<p style ='font-size: 8px'>Normal</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Fecha y Hora de Generacion:</p>
</td>
<td>
<p style ='font-size: 8px'>21/08/2023 18:51:56</p>
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
<p style ='font-size: 8px'><b>Nombre o razon social:</p>
</td>
<td>
<p style ='font-size: 8px'>INTERACTIVEMENUSV</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>NIT:</p>
</td>
<td>
<p style ='font-size: 8px'>02100903931053</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>NRC:</p>
</td>
<td>
<p style ='font-size: 8px'>2926189</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Actividad economica:</p>
</td>
<td>
<p style ='font-size: 8px'>Programación informática</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Direccion:</p>
</td>
<td>
<p style ='font-size: 8px'>AV. ALVARADO DIAG. CENTROAMERICA,#4</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Numero de telefono:</p>
</td>
<td>
<p style ='font-size: 8px'>79213508</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Correo electronico:</p>
</td>
<td>
<p style ='font-size: 8px'>salvador@ggg.com</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Tipo de establecimiento:</p>
</td>
<td>
<p style ='font-size: 8px'>Casa Matriz</p>
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
<p style ='font-size: 8px'><b>Nombre o razon social:</p>
</td>
<td>
<p style ='font-size: 8px'>JULIO CESAR MARROQUIN DIAZ</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>NIT:</p>
</td>
<td>
<p style ='font-size: 8px'>02100903931053</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>NRC:</p>
</td>
<td>
<p style ='font-size: 8px'>2926189</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Actividad economica:</p>
</td>
<td>
<p style ='font-size: 8px'>Programación informática,Programación informática</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Direccion:</p>
</td>
<td>
<p style ='font-size: 8px'>AV. ALVARADO DIAG. CENTROAMERICA,#4,Sta Ana, Sta ana</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Numero de telefono:</p>
</td>
<td>
<p style ='font-size: 8px'>79213508</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Correo electronico:</p>
</td>
<td>
<p style ='font-size: 8px'>salvador@ggg.com</p>
</td>
</tr>
<tr>
<td align='right'>
<p style ='font-size: 8px'><b>Tipo de establecimiento:</p>
</td>
<td>
<p style ='font-size: 8px'>Casa Matriz</p>
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
<td WIDTH='25' class='d'><p style ='font-size: 12px'><b>N°<b></p></td>
<td WIDTH='25'  class='d'><p style ='font-size: 12px'><b>Cantidad<b></p></td>
<td WIDTH='25'  class='d'><p style ='font-size: 12px'><b>Unidad<b></p></td>
<td WIDTH='300'  class='d'><p style ='font-size: 12px'><b>Descripción<b></p></td>
<td class='d'><p style ='font-size: 12px'><b>precio unitario <b></p></td>
<td  class='d'><p style ='font-size: 12px'><b>Ventas no sujetas<b></p></td>
<td  class='d'><p style ='font-size: 12px'><b>Ventas exentas<b></p></td>
<td  class='d'><p style ='font-size: 12px'><b>Ventas gravadas<b></p></td>
</tr>
<tr><td  class='d'><p class='b'>1</b></td><td  class='d'><p class='b'>3</p></td><td  class='d'><p class='b'>Unidad</p></td><td  class='d'><p class='b'>Flete internacional<p/></td><td  class='d'><p class='b'>$250.00</p></td><td  class='d'><p class='b'>$0.00</p></td><td  class='d'><p class='b'>$0.00</p></td><td  class='d'><p class='b'>$750.00</p></td></tr>

<tr><td class='d' colspan='4' align='right'><p class='b'><b>Suma de Ventas : <b></p></td><td class='d'><p class='b'>$0.00</p></td><td class='d'><p class='b'>$0.00</p></td><td class='d'><p class='b'>$0.00</p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Suma Total de Operaciones :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Monto global Desc., Rebajas y otros a ventas no sujetas :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Monto global Desc., Rebajas y otros a ventas Exentas :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Monto global Desc., Rebajas y otros a ventas gravadas :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Impuesto al Valor Agregado13% :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Sub-Total :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Sub-Total :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>IVA Percibido :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>IVA Retenido :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Retención Renta :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Monto Total de la Operación :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Total Otros montos no afectos :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
<tr><td class='d' colspan='7' align='right'><p class='b'><b>Total a Pagar :<b></p></td><td class='d'><p class='b'>$750.00</p></td></tr>
</table>

</div>

<div>
<table>
<tr><td><p class='b'><b>Valor en Letras :</b></p></td><td> DOSCIENTOS VEINTISEIS DOLARES</td></tr>
<tr><td><p class='b'><b>Condición de la Operación: </b></p></td><td>CONTADO</td></tr>
<tr><td><p class='b'><b>Observaciones :</b></p></td><td></td></tr>
</table>
</div>


";

$mpdf->WriteHTML($html);
$mpdf->Output();
?>