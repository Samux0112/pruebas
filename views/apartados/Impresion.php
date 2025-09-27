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
                <p>Ruc: <?php echo $data['empresa']['ruc']; ?></p>
                <p>Teléfono: <?php echo $data['empresa']['telefono']; ?></p>
                <p>Dirección: <?php echo $data['empresa']['direccion']; ?></p>
            </td>
            <td class="info-compra">
                <div class="container-impresion">
                    <span class="impresion">Documento</span>
                    <p>N°: <strong><?php echo $data['apartado']['id']; ?></strong></p>
                    <p>Fecha y Hora: <?php echo $data['apartado']['fecha_apartado']; ?></p>
                </div>
            </td>
        </tr>
    </table>


    <h5 class="title">Datos del Cliente</h5>
    <table id="container-info">
        <tr>

            <td>
                <strong>Nombre: </strong>
                <p><?php echo $data['apartado']['nombre'] ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Teléfono: </strong>
                <p><?php echo $data['apartado']['telefono'] ?></p>
            </td>
            <td>
                <strong>Dirección: </strong>
                <p><?php echo $data['apartado']['direccion'] ?></p>
            </td>
        </tr>
    </table>
    <h5 class="title">Detalle de los Productos</h5>
    <table id="container-producto">
        <thead>
            <tr>
                <th>Cant</th>
                <th>Descripción</th>
                <th></th>
                <th>Precio</th>
                <th>SubTotal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $productos = json_decode($data['apartado']['productos'], true);

            foreach ($productos as $producto) { ?>
                <tr>
                    <td class="text-right" colspan="1"><?php echo $producto['cantidad']; ?></td>
                    <td class="text-right" colspan="1"><?php echo $producto['nombre']; ?></td>
                   
                    <td class="text-right" colspan="2"><?php echo number_format($producto['precio'], 2); ?></td>
                    <td class="text-right" colspan="1"><?php echo number_format($producto['cantidad'] * $producto['precio'], 2); ?></td>
                </tr>
            <?php } ?>
            <tr class="total">
                <td class="text-right" colspan="4">Total</td>
                <td class="text-right"><?php echo number_format($data['apartado']['total'], 2); ?></td>
            </tr>
            <tr class="abono">
                <td class="text-right" colspan="4">Abono</td>
                <td class="text-right"><?php echo number_format($data['apartado']['abono'], 2); ?></td>
            </tr>

        </tbody>
    </table>
    <div class="mensaje">
        <?php if ($data['apartado']['estado'] == 0) { ?>
            <h1>Productos Entregado</h1>
        <?php } else { ?>
            <h1>Productos por Recoger</h1>
        <?php } ?>
        <?php echo $data['empresa']['mensaje']; ?>
    </div>

</body>

</html>