<?php
require 'views/templates/header.php';
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Detalle de Cotización</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>ID Cotización:</strong> <?php echo htmlspecialchars($data['cotizacion']['id']); ?><br>
                            <strong>ID Proveedor:</strong> <?php echo isset($data['cotizacion']['id_proveedor']) ? htmlspecialchars($data['cotizacion']['id_proveedor']) : ''; ?><br>
                            <strong>Proveedor:</strong> <?php echo htmlspecialchars($data['cotizacion']['proveedor']); ?><br>
                            <strong>Monto:</strong> <?php echo htmlspecialchars($data['cotizacion']['monto']); ?><br>
                            <strong>Detalle:</strong> <?php echo htmlspecialchars($data['cotizacion']['detalle']); ?><br>
                            <strong>Fecha:</strong> <?php echo htmlspecialchars($data['cotizacion']['fecha']); ?><br>
                        </div>
                        <h5>Productos ofertados</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th hidden>ID</th>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>Descripción</th>
                                        <th>Precio Unitario</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                        <th>Adjudicar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total = 0;
                                    if (!empty($data['productos'])): 
                                        foreach ($data['productos'] as $prod): 
                                            $total += floatval($prod['subtotal']);
                                    ?>
                                        <tr>
                                            <td hidden><?php echo htmlspecialchars($prod['id_producto']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['cantidad']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['descripcion']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['precio']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['descuento']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['subtotal']); ?></td>
                                            <td class="text-center">
                                                <input type="checkbox" name="adjudicar[]" value="<?php echo htmlspecialchars($prod['id']); ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <!-- Fila de total -->
                                    <tr class="table-info">
                                        <td colspan="5" class="text-end"><strong>Total</strong></td>
                                        <td><strong><?php echo number_format($total, 2); ?></strong></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <form id="formOrdenCompra" method="post">
                            <input type="hidden" name="proveedor" value="<?php echo htmlspecialchars($data['cotizacion']['proveedor']); ?>">
                            <input type="hidden" name="cotizacion_id" value="<?php echo htmlspecialchars($data['cotizacion']['id']); ?>">
                            <input type="hidden" name="requisicion_id" value="<?php echo htmlspecialchars($data['requisicion']['id']); ?>">
                            <button type="button" class="btn btn-success mt-3" id="btnOrdenCompra">Realizar orden de compra</button>
                        </form>
                        <a href="javascript:history.back()" class="btn btn-secondary mt-3">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'views/templates/footer.php'; ?>
