<?php
require 'views/templates/header.php';
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">Comparativo de Cotizaciones</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($data['productosComparados'])): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Producto</th>
                                        <?php foreach ($data['cotizaciones'] as $cot): ?>
                                            <th>Proveedor: <?php echo htmlspecialchars($cot['proveedor']); ?><br>ID: <?php echo htmlspecialchars($cot['id']); ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['productosComparados'] as $nombre => $comparativo): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($nombre); ?></td>
                                        <?php foreach ($data['cotizaciones'] as $cot): ?>
                                            <?php if (isset($comparativo[$cot['id']])): ?>
                                                <td>
                                                    <strong>Cantidad:</strong> <?php echo htmlspecialchars($comparativo[$cot['id']]['cantidad'] ?? ''); ?><br>
                                                    <strong>Precio:</strong> <?php echo htmlspecialchars($comparativo[$cot['id']]['precio']); ?><br>
                                                    <strong>Descuento:</strong> <?php echo htmlspecialchars($comparativo[$cot['id']]['descuento']); ?><br>
                                                    <strong>Subtotal:</strong> <?php echo htmlspecialchars($comparativo[$cot['id']]['subtotal']); ?>
                                                </td>
                                            <?php else: ?>
                                                <td class="text-muted">No ofertado</td>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <!-- Fila de totales por cotización -->
                                <tr class="table-info">
                                    <td class="text-end"><strong>Total</strong></td>
                                    <?php foreach ($data['cotizaciones'] as $cot): ?>
                                        <?php 
                                            $totalCot = 0;
                                            foreach ($data['productosComparados'] as $comparativo) {
                                                if (isset($comparativo[$cot['id']])) {
                                                    $totalCot += floatval($comparativo[$cot['id']]['subtotal']);
                                                }
                                            }
                                        ?>
                                        <td><strong><?php echo number_format($totalCot, 2); ?></strong></td>
                                    <?php endforeach; ?>
                                </tr>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-info">No hay cotizaciones asociadas o productos para comparar.</div>
                        <?php endif; ?>
                        <a href="javascript:history.back()" class="btn btn-secondary mt-3">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'views/templates/footer.php'; ?>
