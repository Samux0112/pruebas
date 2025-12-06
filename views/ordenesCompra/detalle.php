<?php
require 'views/templates/header.php';
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Detalle de Orden de Compra</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($data['orden'])): ?>
                        <div class="mb-3">
                            <strong>ID Orden:</strong> <?php echo htmlspecialchars($data['orden']['id']); ?><br>
                            <strong>Proveedor:</strong> <?php echo htmlspecialchars($data['orden']['nombreProveedor'] ?? $data['orden']['proveedor']); ?><br>
                            <strong>Monto:</strong> <?php echo htmlspecialchars($data['orden']['total']); ?><br>
                            <strong>Fecha:</strong> <?php echo htmlspecialchars($data['orden']['fecha']); ?><br>
                            <strong>Usuario:</strong> <?php echo htmlspecialchars($data['orden']['usuario']); ?><br>
                            <strong>Estado:</strong> <?php echo htmlspecialchars($data['orden']['estado']); ?><br>
                        </div>
                        <h5>Productos</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
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
                                    <?php 
                                    $productos = json_decode($data['orden']['productos'], true);
                                    $subtotal = 0;
                                    foreach ($productos as $prod): 
                                        $subtotal += floatval($prod['subtotal']);
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($prod['cantidad']); ?></td>
                                        <td><?php echo htmlspecialchars($prod['descripcion']); ?></td>
                                        <td><?php echo htmlspecialchars($prod['precio']); ?></td>
                                        <td><?php echo htmlspecialchars($prod['descuento']); ?></td>
                                        <td><?php echo htmlspecialchars($prod['subtotal']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-info">
                                        <td colspan="5" class="text-end"><strong>Subtotal</strong></td>
                                        <td><strong><?php echo number_format($subtotal, 2); ?></strong></td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td colspan="5" class="text-end"><strong>IVA (13%)</strong></td>
                                        <td><strong><?php $iva = $subtotal * 0.13; echo number_format($iva, 2); ?></strong></td>
                                    </tr>
                                    <tr class="table-success">
                                        <td colspan="5" class="text-end"><strong>Total con IVA</strong></td>
                                        <td><strong><?php echo number_format($subtotal + $iva, 2); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-danger">No se encontró la orden de compra.</div>
                        <?php endif; ?>
                        <?php if (!empty($data['orden'])): ?>
                        <button type="button" class="btn btn-warning mt-3" id="btnAutorizarDetalleOrden" data-id="<?php echo htmlspecialchars($data['orden']['id']); ?>">Autorizar</button>
                        <button type="button" class="btn btn-danger mt-3 ms-2" id="btnRechazarDetalleOrden" data-id="<?php echo htmlspecialchars($data['orden']['id']); ?>">Rechazar</button>
                        <?php endif; ?>
                        <a href="javascript:history.back()" class="btn btn-secondary mt-3">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'views/templates/footer.php'; ?>
