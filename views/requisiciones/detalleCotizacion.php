<?php
require 'views/templates/header.php';
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Detalle de Cotización</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>ID Cotización:</strong> <?php echo htmlspecialchars($data['cotizacion']['id']); ?><br>
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
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>Descripción</th>
                                        <th>Precio Unitario</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['productos'])): ?>
                                        <?php foreach ($data['productos'] as $prod): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['cantidad']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['descripcion']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['precio']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['descuento']); ?></td>
                                            <td><?php echo htmlspecialchars($prod['subtotal']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="javascript:history.back()" class="btn btn-secondary mt-3">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'views/templates/footer.php'; ?>
