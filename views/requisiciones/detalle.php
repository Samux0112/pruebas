<?php
require 'views/templates/header.php';
$requisicion = $data['requisicion'];
$productos = $data['productos'];
$msg = $data['msg'];
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Detalle de Requisición #<?php echo htmlspecialchars($requisicion['id']); ?></h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($msg)) echo $msg; ?>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Estado actual:</strong> <?php echo htmlspecialchars($requisicion['estado']); ?></p>
                            </div>
                            <div class="col-md-6 text-end">
                                <form method="post" action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                                    <div class="input-group">
                                        <label class="input-group-text" for="estado">Actualizar estado</label>
                                        <select name="estado" id="estado" class="form-select">
                                            <option value="Pendiente" <?php if($requisicion['estado']=='Pendiente') echo 'selected'; ?>>Pendiente</option>
                                            <option value="Aprobada" <?php if($requisicion['estado']=='Aprobada') echo 'selected'; ?>>Aprobada</option>
                                            <option value="Rechazada" <?php if($requisicion['estado']=='Rechazada') echo 'selected'; ?>>Rechazada</option>
                                        </select>
                                        <button type="submit" class="btn btn-success">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <h5 class="mt-4">Productos de la Requisición</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
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
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (!empty($data['cotizaciones'])){ ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Cotizaciones asociadas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Proveedor</th>
                                    <th>Monto</th>
                                    <th>Detalle</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['cotizaciones'] as $cot): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cot['id']); ?></td>
                                    <td><?php echo htmlspecialchars($cot['proveedor']); ?></td>
                                    <td><?php echo htmlspecialchars($cot['monto']); ?></td>
                                    <td><?php echo htmlspecialchars($cot['detalle']); ?></td>
                                    <td><?php echo htmlspecialchars($cot['fecha']); ?></td>
                                    <td>
                                       <?php 
                                        $rolesPermitidos = ['ADMINISTRADOR', 'SUPERVISOR', 'CONTADOR', 'INVENTARIO', 'VENDEDOR ADMINISTRATIVO'];
                                        if (isset($_SESSION['rol_usuario']) && in_array($_SESSION['rol_usuario'], $rolesPermitidos)) {
                                        ?>
                                            <button class="btn btn-info btn-sm btnVerCotizacion" data-id="<?php echo $cot['id']; ?>">Ver</button>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3 text-end">
                            <button id="btnCompararCotizaciones" class="btn btn-warning" data-id="<?php echo htmlspecialchars($requisicion['id']); ?>">Comparar cotizaciones</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } else { ?>
<div class="mb-3 text-end" hidden>
    <button id="btnCompararCotizaciones" class="btn btn-warning" data-id="<?php echo htmlspecialchars($requisicion['id']); ?>">Comparar cotizaciones</button>
</div>
<?php }?>

<?php if (!empty($data['ordenesCompra'])){ ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Órdenes de compra asociadas</h5>
                </div>
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
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['ordenesCompra'] as $orden): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($orden['id']); ?></td>
                                    <td><?php echo htmlspecialchars($orden['nombreProveedor']); ?></td>
                                    <td><?php echo htmlspecialchars($orden['total']); ?></td>
                                    <td><?php echo htmlspecialchars($orden['fecha']); ?></td>
                                    <td><?php echo htmlspecialchars($orden['usuario']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
                        </div>
                            <div class="mt-3 d-flex gap-2">
                                <button id="btnVolverRequisiciones" class="btn btn-secondary">Volver a la lista de requisiciones</button>
                                <button id="btnCotizarRequisicion" class="btn btn-info" data-id="<?php echo htmlspecialchars($requisicion['id']); ?>">Ingresar Cotización</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'views/templates/footer.php'; ?>

