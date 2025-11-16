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
                            <button type="button" class="btn btn-success mt-3" id="btnOrdenCompra">Realizar orden de compra</button>
                        </form>
                        <a href="javascript:history.back()" class="btn btn-secondary mt-3">Volver</a>
                    <script>
                    document.getElementById('btnOrdenCompra').addEventListener('click', function() {
                        const form = document.getElementById('formOrdenCompra');
                        // Obtener productos adjudicados
                        const adjudicados = Array.from(form.querySelectorAll('input[name="adjudicar[]"]:checked')).map(cb => cb.value);
                        if (adjudicados.length === 0) {
                            alert('Seleccione al menos un producto para adjudicar.');
                            return;
                        }
                        // Preparar datos para enviar
                        const proveedor = form.querySelector('input[name="proveedor"]').value;
                        // Aquí puedes hacer el envío por AJAX o redirigir a una URL para crear la orden
                        fetch('ordenesCompra/crear', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                productos: adjudicados,
                                proveedor: proveedor,
                                cotizacion: <?php echo json_encode($data['cotizacion']['id']); ?>
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success && data.idOrden) {
                                window.open('ordenesCompra/generarPDF/' + data.idOrden, '_blank');
                            } else {
                                alert('Error al generar la orden de compra.');
                            }
                        })
                        .catch(() => alert('Error de conexión.'));
                    });
                    </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require 'views/templates/footer.php'; ?>
