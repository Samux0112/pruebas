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

