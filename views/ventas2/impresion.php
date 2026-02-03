<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL . 'assets/css/impresion.css'; ?>">
</head>

<body>
    <table id="datos-empresa">
        <tr>
            <td class="logo">
                <img src="<?php echo BASE_URL . 'assets/images/logo.png'; ?>" alt="">
            </td>
            <td class="info-empresa">
                <p><?php echo $data['empresa']['nombre']; ?></p>
                <p>Registro: <?php echo $data['empresa']['ruc']; ?></p>
                <p>Teléfono: <?php echo $data['empresa']['telefono']; ?></p>
                <p>Dirección: <?php echo $data['empresa']['direccion']; ?></p>
            </td>
            <td class="info-compra">
                <div class="container-impresion">
                    <span class="impresion">Documento</span>
                    <p>N°: <strong><?php echo $data['venta2']['serie']; ?></strong></p>
                    <p>Fecha: <?php echo $data['venta2']['fecha']; ?></p>
                    <p>Hora: <?php echo $data['venta2']['hora']; ?></p>
                </div>
            </td>
        </tr>
    </table>


    <h5 class="title">Datos del proveedor</h5>
    <table id="container-info">
        <tr>
           
            <td>
                <strong>Nombre: </strong>
                <p><?php echo $data['venta2']['nombre'] ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Teléfono: </strong>
                <p><?php echo $data['venta2']['telefono'] ?></p>
            </td>
            <td>
                <strong>Dirección: </strong>
                <p><?php echo $data['venta2']['direccion'] ?></p>
            </td>
            <td>
                <strong>Numc: </strong>
                <p><?php echo $data['venta2']['numc'] ?></p>
            </td>
        </tr>
    </table>
    <h5 class="title">Detalle de los Productos</h5>
    <table id="container-producto">
        <thead>
            <tr>
                <th>Cant</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>SubTotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $productos = json_decode($data['venta2']['productos'], true);
            //IGV incluido
            $subTotal = $data['venta2']['vGravadas'];
            $igv = $data['venta2']['vIva'];
			$fovial = $data['venta2']['fovial'];
			$cotrans = $data['venta2']['cotrans'];
			$percepcion =0.00;
			if($data['venta2']['percepcion1']>0){
			$percepcion = $data['venta2']['percepcion1'];	
			}elseif($data['venta2']['percepcion2']>0){
			$percepcion = $data['venta2']['percepcion2'];	
			}
			
            $totalSD = $data['venta2']['total'] - $data['venta2']['descuento'];
            $totalCD = $data['venta2']['total'];

            //IGV no incluido
            // $subTotal = $data['venta2']['total'];
            // $igv = $subTotal * 0.13;
            // $total = $subTotal + $igv;

            foreach ($productos as $producto) { ?>
                <tr>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo number_format($producto['precio'], 2); ?></td>
                    <td><?php echo number_format($producto['cantidad'] * $producto['precio'], 2); ?></td>
                </tr>
            <?php } ?>
            <tr class="total">
                <td class="text-right" colspan="3">SubTotal</td>
                <td class="text-right"><?php echo number_format($subTotal, 2); ?></td>
            </tr>
            <tr class="total">
                <td class="text-right" colspan="3">IVA 13%</td>
                <td class="text-right"><?php echo number_format($igv, 2); ?></td>
            </tr>
			<tr class="total">
                <td class="text-right" colspan="3">Fovial</td>
                <td class="text-right"><?php echo number_format($fovial, 2); ?></td>
            </tr>
			<tr class="total">
                <td class="text-right" colspan="3">Cotrans</td>
                <td class="text-right"><?php echo number_format($cotrans, 2); ?></td>
            </tr>
			<tr class="total">
                <td class="text-right" colspan="3">Percepcion</td>
                <td class="text-right"><?php echo number_format($percepcion, 2); ?></td>
            </tr>
            <tr class="total">
                <td class="text-right" colspan="3">Total con Descuento</td>
                <td class="text-right"><?php echo number_format($totalSD, 2); ?></td>
            </tr>
            <tr class="total">
                <td class="text-right" colspan="3">Total sin Descuento</td>
                <td class="text-right"><?php echo number_format($totalCD, 2); ?></td>
            </tr>
        </tbody>
    </table>
    <div class="mensaje">
        <h4><?php echo $data['venta2']['metodo'] ?></h4>
        <?php echo $data['empresa']['mensaje']; ?>
        <?php if ($data['venta2']['estado'] == 0) { ?>
            <h1>Compra Anulada</h1>
        <?php } ?>
    </div>

</body>

</html>