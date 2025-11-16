<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Orden de Compra</title>
	<link rel="stylesheet" href="<?php echo BASE_URL . 'assets/css/impresion.css'; ?>">
</head>
<body>
	<table id="datos-empresa">
		<tr>
			<td class="logo">
				<img src="<?php echo BASE_URL . 'assets/images/logo.png'; ?>" alt="">
			</td>
			<td class="info-empresa">
				<p><?php echo $data['empresa']['nombre'] ?? ''; ?></p>
				<p>Nit: <?php echo $data['empresa']['ruc'] ?? ''; ?></p>
				<p>Teléfono: <?php echo $data['empresa']['telefono'] ?? ''; ?></p>
				<p>Dirección: <?php echo $data['empresa']['direccion'] ?? ''; ?></p>
			</td>
			<td class="info-compra">
				<div class="container-impresion">
					<span class="impresion">Orden de Compra</span>
					<p>N°: <strong><?php echo $data['id']; ?></strong></p>
					<p>Fecha: <?php echo $data['fecha'] ?? date('Y-m-d'); ?></p>
				</div>
			</td>
		</tr>
	</table>

	<h5 class="title">Datos de la orden</h5>
	<table id="container-info">
		<tr>
			<td>
				<strong>Proveedor: </strong>
				<p><?php echo htmlspecialchars($data['nombreProveedor'] ?? $data['proveedor'] ?? ''); ?></p>
			</td>
			<td>
				<strong>Cotización: </strong>
				<p><?php echo htmlspecialchars($data['requisicion_id'] ?? $data['cotizacion'] ?? ''); ?></p>
			</td>
		</tr>
	</table>

	<h5 class="title">Detalle de los Productos</h5>
	<?php $productos = json_decode($data['productos'], true); ?>
	<table id="container-producto">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Cantidad</th>
				<th>Descripción</th>
				<th>Precio Unitario</th>
				<th>Descuento</th>
				<th>Subtotal</th>
			</tr>
		</thead>
		<tbody>
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
			<tr class="total">
				<td class="text-right" colspan="5">Total</td>
				<td class="text-right">
					<?php echo isset($data['total']) ? number_format($data['total'], 2) : ''; ?>
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>
