<?php
require 'views/templates/header.php';
$id = isset($data['id']) ? intval($data['id']) : '';
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
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
                            <div class="mb-4">
                                <h5>Productos asociados</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th hidden>Id Producto</th>
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
                                                    <td hidden><?php echo htmlspecialchars($prod['id']); ?></td>
                                                    <td><?php echo htmlspecialchars($prod['nombre']); ?></td>
                                                    <td><input type="number" class="form-control input-cantidad" value="<?php echo htmlspecialchars($prod['cantidad']); ?>" readonly></td>
                                                    <td><?php echo htmlspecialchars($prod['descripcion'] ?? ''); ?></td>
                                                    <td><input type="number" class="form-control input-precio" value="0" min="0" step="0.01"></td>
                                                    <td><input type="number" class="form-control input-descuento" value="0" min="0" step="0.01"></td>
                                                    <td><input type="number" class="form-control input-subtotal" value="0.00" readonly></td> 
                                                </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="proveedor" class="form-label">Proveedor</label>
                                <input type="text" class="form-control" id="proveedor" name="proveedor" list="proveedoresList" autocomplete="off" required>
                                <datalist id="proveedoresList">
                                    <?php if (!empty($data['proveedores'])): ?>
                                        <?php foreach ($data['proveedores'] as $prov): ?>
                                            <option value="<?php echo htmlspecialchars($prov['nombre']); ?>">
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </datalist>
                            </div>
                            <div class="mb-3">
                                <label for="monto" class="form-label">Monto Cotización</label>
                                <input type="number" class="form-control" id="monto" name="monto" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="iva" class="form-label">IVA (13%)</label>
                                <input type="number" class="form-control" id="iva" name="iva" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="detalle" class="form-label">Detalle</label>
                                <textarea class="form-control" id="detalle" name="detalle" rows="3"></textarea>
                            </div>
                            <button type="button" class="btn btn-success" id="btnGuardarCotizacion">Guardar Cotización</button>
                            <a href="index.php" class="btn btn-secondary ms-2">Volver</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'views/templates/footer.php'; ?>

<script>
// Array de proveedores para búsqueda por nombre
window.proveedoresData = [
<?php if (!empty($data['proveedores'])): ?>
<?php foreach ($data['proveedores'] as $prov): ?>
    { id: '<?php echo addslashes($prov['id']); ?>', nombre: '<?php echo addslashes($prov['nombre']); ?>' },
<?php endforeach; ?>
<?php endif; ?>
];

// Calcular IVA dinámicamente cuando cambian los subtotales
function calcularIVA() {
    let suma = 0;
    document.querySelectorAll('.input-subtotal').forEach(function(input) {
        suma += parseFloat(input.value) || 0;
    });
    document.getElementById('iva').value = (suma * 0.13).toFixed(2);
}

document.querySelectorAll('.input-precio, .input-descuento').forEach(function(input) {
    input.addEventListener('input', function() {
        // Aquí debe ir la lógica de cálculo de subtotal si no existe
        // y luego recalcular IVA
        calcularIVA();
    });
});
// También recalcular al cargar la página
window.addEventListener('DOMContentLoaded', calcularIVA);
</script>
