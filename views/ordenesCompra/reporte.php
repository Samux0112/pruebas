<?php
// ...existing code...
// Aquí se arma el HTML del PDF con los datos de la orden de compra
?>
<html>
<head><title>Orden de Compra</title></head>
<body>
<h2>Orden de Compra</h2>
<p>Proveedor: <?php echo htmlspecialchars($data['proveedor']); ?></p>
<p>Cotización: <?php echo htmlspecialchars($data['requisicion_id'] ?? $data['cotizacion'] ?? ''); ?></p>
<?php $productos = json_decode($data['productos'], true); ?>
<table border="1">
	<tr>
		<th>Nombre</th>
		<th>Cantidad</th>
		<th>Descripción</th>
		<th>Precio Unitario</th>
		<th>Descuento</th>
		<th>Subtotal</th>
	</tr>
	<?php foreach ($productos as $prod): ?>
	<tr>
		<td><?php echo htmlspecialchars($prod['nombre']); ?></td>
		<td><?php echo htmlspecialchars($prod['cantidad']); ?></td>
		<td><?php echo htmlspecialchars($prod['descripcion']); ?></td>
		<td><?php echo htmlspecialchars($prod['precio']); ?></td>
		<td><?php echo htmlspecialchars($prod['descuento']); ?></td>
		<td><?php echo htmlspecialchars($prod['subtotal']); ?></td>
	</tr>
	<?php endforeach; ?>
</table>
</body>
</html>
