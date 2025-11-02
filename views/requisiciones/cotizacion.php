<?php
require 'views/templates/header.php';
$id = isset($data['id']) ? intval($data['id']) : '';
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">Cotización de Requisición</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="requisicion_id" class="form-label">ID Requisición</label>
                                <input type="text" class="form-control" id="requisicion_id" name="requisicion_id" value="<?php echo htmlspecialchars($id); ?>" readonly required>
                            </div>
                            <div class="mb-3">
                                <label for="proveedor" class="form-label">Proveedor</label>
                                <input type="text" class="form-control" id="proveedor" name="proveedor" required>
                            </div>
                            <div class="mb-3">
                                <label for="monto" class="form-label">Monto Cotización</label>
                                <input type="number" class="form-control" id="monto" name="monto" required>
                            </div>
                            <div class="mb-3">
                                <label for="detalle" class="form-label">Detalle</label>
                                <textarea class="form-control" id="detalle" name="detalle" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Guardar Cotización</button>
                            <a href="index.php" class="btn btn-secondary ms-2">Volver</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'views/templates/footer.php'; ?>
