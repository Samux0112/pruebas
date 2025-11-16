<?php
// ...existing code...
// Aquí se arma el HTML del PDF con los datos de la orden de compra
?>
<html>
<head><title>Orden de Compra</title></head>
<body>
<h2>Orden de Compra</h2>
<p>Proveedor: <?php echo htmlspecialchars($data['proveedor']); ?></p>
<p>Cotización: <?php echo htmlspecialchars($data['cotizacion']); ?></p>
<table border="1">
<tr><th>Producto</th></tr>
<?php foreach ($data['productos'] as $prod): ?>
<tr><td><?php echo htmlspecialchars($prod['nombre']); ?></td></tr>
<?php endforeach; ?>
</table>
</body>
</html>
