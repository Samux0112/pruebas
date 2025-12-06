<?php
require 'views/templates/header.php';
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2>Listado de Órdenes de Compra</h2>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Proveedor</th>
                                        <th>Monto</th>
                                        <th>Fecha</th>
                                        <th>Usuario</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($data['ordenes'])): ?>
                                        <?php foreach ($data['ordenes'] as $orden): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($orden['id']); ?></td>
                                                <td><?php echo htmlspecialchars(isset($orden['nombreProveedor']) ? $orden['nombreProveedor'] : (isset($orden['proveedor']) ? $orden['proveedor'] : '')); ?></td>
                                                <td><?php echo htmlspecialchars($orden['total']); ?></td>
                                                <td><?php echo htmlspecialchars($orden['fecha']); ?></td>
                                                <td><?php echo htmlspecialchars($orden['usuario']); ?></td>
                                                <td>
                                                    <?php if ($orden['estado'] === 'generada'): ?>
                                                        <span style="color:green;font-weight:bold;">Generada</span>
                                                    <?php elseif ($orden['estado'] === 'completado'): ?>
                                                        <span style="color:red;font-weight:bold;">Completado</span>
                                                    <?php else: ?>
                                                        <span><?php echo htmlspecialchars($orden['estado']); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo BASE_URL . 'ordenesCompra/generarPDF/' . $orden['id']; ?>" target="_blank" class="btn btn-primary btn-sm">Ver PDF</a>
                                                    <button type="button" class="btn btn-success btn-sm ms-1 btnCompletarCompra" data-id="<?php echo htmlspecialchars($orden['id']); ?>" <?php echo ($orden['estado'] === 'completado') ? 'disabled' : ''; ?>>Completar compra</button>
                                                    <?php if (isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['ADMINISTRADOR', 'SUPERVISOR'])): ?>
                                                        <button type="button" class="btn btn-warning btn-sm ms-1 btnAutorizarOrden" data-id="<?php echo htmlspecialchars($orden['id']); ?>">Autorizar</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center">No hay órdenes de compra registradas.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo BASE_URL; ?>assets/js/modulos/listadoOrdenesCompra.js"></script>
<?php
require 'views/templates/footer.php';
?>
