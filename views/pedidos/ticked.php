<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL . 'assets/css/ticked.css'; ?>">
</head>

<body>
<img src="<?php echo BASE_URL . 'assets/images/logo.png'; ?>" alt="">
  
    <div class="datos-empresa">
        <p><?php echo $data['empresa']['nombre']; ?></p>
        <p>Reg: <?php echo $data['empresa']['registro']; ?></p>
        <p>Tel:<?php echo $data['empresa']['telefono']; ?></p>
<br><br>
        
    </div>
    <h5 class="title">Datos del Cliente</h5>
    <div class="datos-info">
        
        <p><strong>Nombre: </strong> <?php echo $data['venta']['nombre']; ?></p>
        <p><strong>Telefono: </strong> <?php echo $data['venta']['telefono']; ?></p>
        <p><strong>Direccion: </strong> <?php echo $data['venta']['direccion']; ?></p>
        <p><strong>Correo: </strong> <?php echo $data['venta']['correo']; ?></p>
    </div>
    
    <h5 class="title">Comprobante de Pedido</h5>
     <div class="datos-factu"> 
     <p><strong>Fecha: </strong> <?php echo $data['venta']['fecha']; ?></p>
     <p><strong>Hora: </strong> <?php echo $data['venta']['hora']; ?></p>
     <p><strong>Tipo Documento: </strong> <?php echo $data['venta']['docuemi']; ?></p>
     <p><strong><?php echo $data['venta']['forma']; ?>: </strong> <?php echo $data['venta']['metodo']; ?></p>
     <p><strong>Numero de Pedido: </strong> <?php echo $data['idVenta']; ?></p>
     
        
    </div>
    <h5 class="title">Detalle de los Productos</h5>
    <table>
        <thead>
            <tr>
                <th>Cant</th>
                <th>Descripcion</th>
                <th>Precio</th>
                <th>SubTotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once __DIR__ .'../../../phpqrcode/qrlib.php';
            $tempDir = "qrimages/";
            $productos = json_decode($data['venta']['productos'], true);
            $codeContents = 'https://admin.factura.gob.sv/consultaPublica?ambiente=00&codGen='.$data['venta']['uuid'].'&fechaEmi='.$data['venta']['fecha'];
QRcode::png($codeContents, $tempDir.'008_4.png', QR_ECLEVEL_L, 3, 4);
            foreach ($productos as $producto) { ?>
                <tr>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td><?php echo ($data['venta']['metodo'] == "PLAZO") ? 'Prima de : '.$producto['nombre'] : $producto['nombre'] ; ?></td>
                    <td><?php echo number_format($producto['precio'], 2); ?></td>
                    <td><?php echo number_format($producto['cantidad'] * $producto['precio'], 2); ?></td>
                </tr>
            <?php } ?>
            
            <tr>
                <td class="text-right" colspan="3">Descuento</td>
                <td class="text-right"><?php echo number_format($data['venta']['descuento'], 2); ?></td>
            </tr>

             <tr>
                <td class="text-right" colspan="3">Total</td>
                <td class="text-right"><?php echo number_format($data['venta']['total'], 2); ?></td>
            </tr>
        </tbody>
        
    </table>
   
    <div class="mensaje">
        <h4><?php echo $data['venta']['metodo'] ?></h4>
        <?php echo $data['empresa']['mensaje']; ?>
        <?php if ($data['venta']['estado'] == 0) { ?>
            <h1>Venta Anulado</h1>
        <?php } ?>
        <br>
    </div>
</body>

</html>