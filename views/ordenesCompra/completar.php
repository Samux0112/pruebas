<?php
require 'views/templates/header.php';
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2>Completar Compra - Orden #<?php echo htmlspecialchars($data['orden']['id']); ?></h2>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['productos'] as $prod): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                                        <td><?php echo htmlspecialchars($prod['cantidad']); ?></td>
                                        <td><?php echo htmlspecialchars($prod['precio']); ?></td>
                                        <td><?php echo htmlspecialchars($prod['subtotal']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <form method="post" action="<?php echo BASE_URL . 'ordenesCompra/completar/' . $data['orden']['id']; ?>">
                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Completar compra</button>
                            <a href="<?php echo BASE_URL . 'ordenesCompra/listado'; ?>" class="btn btn-secondary ms-2">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require 'views/templates/footer.php';
?>
