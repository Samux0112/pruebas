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
                <p>Nit: <?php echo $data['empresa']['ruc']; ?></p>
                <p>Teléfono: <?php echo $data['empresa']['telefono']; ?></p>
                <p>Dirección: <?php echo $data['empresa']['direccion']; ?></p>
            </td>
            <td class="info-compra">
                <div class="container-impresion">
                    <span class="impresion">Salida por traslado</span>
                    <p>N°: <strong><?php echo $data['cotizacion']['id']; ?></strong></p>
                    <p>Fecha: <?php echo $data['cotizacion']['fecha_create']; ?></p>
                </div>
            </td>
        </tr>
    </table>


    <h5 class="title">Datos de la bodega</h5>
    <table id="container-info">
        <tr>
            
            <td>
                <strong>Bodega de salida: </strong>
                <p><?php echo $data['cotizacion']['salida'] ?></p>
            </td>
			 <td>
                <strong>Bodega de entrada: </strong>
                <p><?php echo $data['cotizacion']['entrada'] ?></p>
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
            $productos = json_decode($data['cotizacion']['productos'], true);

            foreach ($productos as $producto) { ?>
                <tr>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo number_format($producto['precio'], 2); ?></td>
                    <td><?php echo number_format($producto['cantidad'] * $producto['precio'], 2); ?></td>
                </tr>
            <?php } ?>

            <tr class="total">
                <td class="text-right" colspan="3">Total</td>
                <td class="text-right"><?php echo number_format($data['cotizacion']['total'], 2); ?></td>
            </tr>
        </tbody>
    </table>

</body>

</html>