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
                                                        <span class="badge bg-warning text-dark" style="font-size:1em;">Generada</span>
                                                    <?php elseif ($orden['estado'] === 'aprobado'): ?>
                                                        <span class="badge bg-success" style="font-size:1em;">Aprobado</span>
                                                    <?php elseif ($orden['estado'] === 'rechazado'): ?>
                                                        <span class="badge bg-secondary" style="font-size:1em;">Rechazado</span>
                                                    <?php elseif ($orden['estado'] === 'completado'): ?>
                                                        <span class="badge bg-primary" style="font-size:1em;">Completado</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-dark" style="font-size:1em;"> <?php echo htmlspecialchars($orden['estado']); ?> </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $rolesPermitidos = ['ADMINISTRADOR', 'SUPERVISOR', 'CONTADOR', 'INVENTARIO', 'VENDEDOR ADMINISTRATIVO'];
                                                    if (isset($_SESSION['rol_usuario']) && in_array($_SESSION['rol_usuario'], $rolesPermitidos)) {
                                                    ?>
                                                    <a href="<?php echo ($orden['estado'] === 'aprobado') ? (BASE_URL . 'ordenesCompra/generarPDF/' . $orden['id']) : '#'; ?>" target="_blank" class="btn btn-primary btn-sm<?php echo ($orden['estado'] !== 'aprobado') ? ' disabled' : ''; ?>" tabindex="-1" aria-disabled="<?php echo ($orden['estado'] !== 'aprobado') ? 'true' : 'false'; ?>">Ver PDF</a>
                                                    <?php } ?>
                                                    <?php 
                                                    $rolesCompletar = ['ADMINISTRADOR', 'SUPERVISOR', 'CONTADOR', 'AUXILIAR CONTABLE'];
                                                    if (isset($_SESSION['rol_usuario']) && in_array($_SESSION['rol_usuario'], $rolesCompletar)) {
                                                    ?>
                                                    <button type="button" class="btn btn-success btn-sm ms-1 btnCompletarCompra" data-id="<?php echo htmlspecialchars($orden['id']); ?>" <?php echo ($orden['estado'] !== 'aprobado') ? 'disabled' : ''; ?>>Completar compra</button>
                                                    <?php } ?>
                                                    <?php if (isset($_SESSION['rol_usuario']) && in_array($_SESSION['rol_usuario'], ['ADMINISTRADOR', 'SUPERVISOR','CONTADOR'])): ?>
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
