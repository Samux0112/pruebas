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
                    <span class="factura">Credito</span>
                    <p>N°: <strong><?php echo $data['credito2']['id']; ?></strong></p>
                    <p>Fecha: <?php echo $data['credito2']['fecha']; ?></p>
                    <p>Hora: <?php echo $data['credito2']['hora']; ?></p>
                </div>
            </td>
        </tr>
    </table>


    <h5 class="title">Datos del Cliente</h5>
    <table id="container-info">
        <tr>
            <td>
                <strong><?php echo $data['credito2']['identidad'] ?>: </strong>
                <p><?php echo $data['credito2']['num_identidad'] ?></p>
            </td>
            <td>
                <strong>Nombre: </strong>
                <p><?php echo $data['credito2']['nombre'] ?></p>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Teléfono: </strong>
                <p><?php echo $data['credito2']['telefono'] ?></p>
            </td>
            <td>
                <strong>Dirección: </strong>
                <p><?php echo $data['credito2']['direccion'] ?></p>
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
            $productos = json_decode($data['credito2']['productos'], true);

            foreach ($productos as $producto) { ?>
                <tr>
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo number_format($producto['precio'], 2); ?></td>
                    <td><?php echo number_format($producto['cantidad'] * $producto['precio'], 2); ?></td>
                </tr>
            <?php } ?>
            <tr class="total">
                <td class="text-right" colspan="3">Monto</td>
                <td class="text-right"><?php echo number_format($data['credito2']['monto'], 2); ?></td>
            </tr>
        </tbody>
    </table>

    <h5 class="title">Detalle de los Abonos</h5>
    <table id="container-producto">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Abono2</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $abonado2 = 0;
            foreach ($data['abonos2'] as $abono2) {
                $abonado2 += $abono2['abono2'];
                ?>
                <tr>
                    <td class="text-center"><?php echo $abono2['fecha']; ?></td>
                    <td class="text-center"><?php echo number_format($abono2['abono2'], 2); ?></td>
                </tr>
            <?php } ?>
            <tr class="total">
                <td class="text-right">Abonado2</td>
                <td class="text-right"><?php echo number_format($abonado2, 2); ?></td>
            </tr>
            <tr class="total">
                <td class="text-right">Restante</td>
                <td class="text-right"><?php echo number_format($data['credito2']['monto'] -  $abonado2, 2); ?></td>
            </tr>
        </tbody>
    </table>


    <div class="mensaje">
        <?php echo $data['empresa']['mensaje']; ?>
        <?php if ($data['credito2']['estado'] == 0) { ?>
            <h1>CREDITO FINALIZADO</h1>
        <?php } else { ?>
            <h1>CREDITO PENDIENTE</h1>
        <?php } ?>
    </div>

</body>

</html>