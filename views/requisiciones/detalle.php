<?php
// Vista limpia, solo muestra datos recibidos por $data desde el controlador
$requisicion = $data['requisicion'];
$productos = $data['productos'];
$msg = $data['msg'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Requisición</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <h2>Detalle de Requisición #<?php echo htmlspecialchars($requisicion['id']); ?></h2>
    <p>Estado actual: <strong><?php echo htmlspecialchars($requisicion['estado']); ?></strong></p>
    <form method="post" action="actualizar_estado.php?id=<?php echo urlencode($requisicion['id']); ?>">
        <label for="estado">Actualizar estado:</label>
        <select name="estado" id="estado">
            <option value="Pendiente" <?php if($requisicion['estado']=='Pendiente') echo 'selected'; ?>>Pendiente</option>
            <option value="Aprobada" <?php if($requisicion['estado']=='Aprobada') echo 'selected'; ?>>Aprobada</option>
            <option value="Rechazada" <?php if($requisicion['estado']=='Rechazada') echo 'selected'; ?>>Rechazada</option>
        </select>
        <button type="submit">Actualizar</button>
    </form>
    <h3>Productos de la Requisición</h3>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $prod): ?>
            <tr>
                <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                <td><?php echo htmlspecialchars($prod['cantidad']); ?></td>
                <td><?php echo htmlspecialchars($prod['descripcion']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="index.php">Volver a la lista de requisiciones</a>
</body>
</html>
