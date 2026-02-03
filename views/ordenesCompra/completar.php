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
                        <form method="post" action="<?php echo BASE_URL . 'ordenesCompra/completar/' . $data['orden']['id']; ?>">
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label for="docuemi">Documentos</label>
                                <select id="docuemi" name="docuemi" class="form-control">
                                    <option value="COMPRA">COMPRA</option>
                                    <option value="SUJETO EXCLUIDO">SUJETO EXCLUIDO</option>
                                    <option value="NOTA DE CREDITO">NOTA DE CREDITO</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="claseDoc">Clase documento</label>
                                <select id="claseDoc" name="claseDoc" class="form-control">
                                    <option value="1">IMPRESO</option>
                                    <option value="4">DTE</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="tipoDoc">Tipo documento</label>
                                <select id="tipoDoc" name="tipoDoc" class="form-control">
                                    <option value="03">CREDITO FISCAL</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="aplicaciones">Aplicaciones</label>
                                <select id="aplicaciones" name="aplicaciones" class="form-control">
                                    <option value="sinAplicaciones">Sin aplicaciones</option>
                                    <option value="Percepcion1">Percepcion 1%</option>
                                    <option value="Percepcion2">Percepcion 2%</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="fechaFactura">Fecha</label>
                                <input type="date" id="fechaFactura" name="fechaFactura" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="tipoOperacionCompra">Tipo de operacion</label>
                                <select id="tipoOperacionCompra" name="tipoOperacionCompra" class="form-control">
                                    <option value="1">1Gravada</option>
                                    <option value="2">2No Gravada</option>
                                    <option value="3">3Excluido o no Constituye Renta</option>
                                    <option value="4">4Mixto Contribuyentes que gozan de Regímenes Especiales con incentivos fiscales</option>
                                    <option value="9">9Excepciones Instituciones públicas, no inscritos a IVA, operaciones no deducibles para renta, entre otros.</option>
                                    <option value="0">0Cuando se trate de periodos tributarios  anteriores a febrero de 2024</option>
                                </select>
                            </div>
                            <div class="col-md-2 mt-2">
                                <label for="clasificacion">Clasificacion</label>
                                <select id="clasificacion" name="clasificacion" class="form-control">
                                    <option value="1">1Costo</option>
                                    <option value="2">2Gasto</option>
                                    <option value="9">9Excepciones Instituciones públicas, no inscritos a IVA, operaciones no deducibles para renta, entre otros.</option>
                                    <option value="0">0Cuando se trate de periodos tributarios  anteriores a febrero de 2024</option>
                                </select>
                            </div>
                            <div class="col-md-2 mt-2">
                                <label for="sector">Sector</label>
                                <select id="sector" name="sector" class="form-control">
                                    <option value="1">1Industria</option>
                                    <option value="2">2Comercio</option>
                                    <option value="3">3Agropecuario</option>
                                    <option value="4">4Servicios, Profesiones, Artes y Oficios</option>
                                    <option value="9">9Excepciones Instituciones públicas, no inscritos a IVA, operaciones no deducibles para renta, entre otros.</option>
                                    <option value="0">0Cuando se trate de periodos tributarios  anteriores a febrero de 2024</option>
                                </select>
                            </div>
                            <div class="col-md-3 mt-2">
                                <label for="tipoGasto">Tipo de Costo / Gasto</label>
                                <select id="tipoGasto" name="tipoGasto" class="form-control">
                                    <option value="1">1Gastos de venta sin donacion</option>
                                    <option value="2">2Gastos de administracion sin donacion</option>
                                    <option value="3">3Gastos financieros sin donacion</option>
                                    <option value="4">4Costos articulos producidos/ comprados importaciones/ internacionales</option>
                                    <option value="5">5Costos articulos producidos/ Comprados interno</option>
                                    <option value="6">6Costos indirectos de fabricacion</option>
                                    <option value="7">7Mano de obra</option>
                                    <option value="9">9Excepciones Instituciones públicas, no inscritos a IVA, operaciones no deducibles para renta, entre otros.</option>
                                    <option value="0">0Cuando se trate de periodos tributarios  anteriores a febrero de 2024</option>
                                </select>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label for="bodegas">Bodegas <span class="text-danger">*</span></label>
                                <select id="bodegas" name="bodegas" class="form-control">
                                    <option value="">Seleccione una bodega</option>
                                    <!-- Aquí puedes cargar dinámicamente las bodegas si tienes el array $data['bodegas'] -->
                                </select>
                            </div>
                        </div>
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
