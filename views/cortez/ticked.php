<!DOCTYPE html>
<html lang="en">
<?php
 require_once __DIR__ .'../../../phpqrcode/qrlib.php';
 $tempDir = "qrimages/";
$codeContents = 'https://admin.factura.gob.sv/consultaPublica?ambiente=00&codGen='.$data['venta']['uuid'].'&fechaEmi='.$data['venta']['fecha'];
QRcode::png($codeContents, $tempDir.'008_4.png', QR_ECLEVEL_L, 3, 4); 
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL . 'assets/css/ticked.css'; ?>">
</head>

<body>

    <img class="imglogo" src="<?php echo BASE_URL . 'assets/images/logo.png'; ?>" alt="">
    <div class="datos-empresa">
        <p><?php echo $data['empresa']['nombre']; ?></p>
        <p>Reg: <?php echo $data['empresa']['registro']; ?></p>
        <p>Tel:<?php echo $data['empresa']['telefono']; ?></p>
        <p>NIT: <?php echo $data['empresa']['ruc']; ?></p>
        <p>Dir:<?php echo $data['empresa']['direccion']; ?></p>        
    </div>
  <h5 class="title">CORTE Z</h5>
  <div class="datos-info">
<p><strong> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; Del : <?php echo $data['fInicio'] ?> Al : <?php echo $data['fFin']  ?></p>
	</div>
  <table>
  <thead>
  
  </thead>
  </table>
   <h5 class="title">Ventas Factura</h5>
   <?php if ($data['factura']['sumatoria']>0) { ?>
       <table>
        <thead>
            <tr>
                <th></th>
                <th></th>
				<th></th>
            </tr>
        </thead>
        <tbody>
           <tr>
		   <td>Del:</td>
		   <td><?php echo $data['factura']['minimo']; ?></td>
		   <td></td>
		   </tr>
		   <tr>
		   <td>Al :</td>
		   <td><?php echo $data['factura']['maximo']; ?></td>
		   <td></td>
		   </tr>
		   <tr>
		   <td class="text-right" colspan="2">TOTAL</td>
		   <td>$<?php echo $data['factura']['sumatoria']; ?></td>
		   </tr>
        </tbody>
    </table>
   <?php } else { ?>
   
   <div class="datos-info">
	<p><strong>No hay registros</p>
	</div>
	 <?php } ?>
	
	
	   <h5 class="title">Ventas Credito Fiscal</h5>
   <?php if ($data['credito']['sumatoria']>0) { ?>
       <table>
        <thead>
            <tr>
                <th></th>
                <th></th>
				<th></th>
            </tr>
        </thead>
        <tbody>
           <tr>
		   <td>Del:</td>
		   <td><?php echo $data['credito']['minimo']; ?></td>
		   <td></td>
		   </tr>
		   <tr>
		   <td>Al :</td>
		   <td><?php echo $data['credito']['maximo']; ?></td>
		   <td></td>
		   </tr>
		   <tr>
		   <td class="text-right" colspan="2">Gravadas</td>
		   <td>$<?php echo $data['credito']['sumatoria']; ?></td>
		   </tr>
		   <tr>
		   <td class="text-right" colspan="2">IVA</td>
		   <td>$<?php echo $data['credito']['iva']; ?></td>
		   </tr>
		   <tr>
		   <td class="text-right" colspan="2">EXENTAS</td>
		   <td>$<?php echo $data['credito']['exentas']; ?></td>
		   </tr>
		   <tr>
		   <td class="text-right" colspan="2">TOTAL</td>
		   <td>$<?php echo (number_format($data['credito']['exentas'] + $data['credito']['iva'] + $data['credito']['sumatoria'],2,'.','')); ?></td>
		   </tr>
        </tbody>
    </table>
   <?php } else { ?>
   
   <div class="datos-info">
	<p><strong>No hay registros</p>
	</div>
	 <?php } ?>
 
    <div class="mensaje">
        <h4>Hora impresion : <?php echo $hoy = date("Y-m-d H:i:s");  ?></h4>
    </div>

</body>

</html>