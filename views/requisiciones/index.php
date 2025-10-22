<?php
require 'views/header.php';
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2>Requisiciones de Compra</h2>
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-9">
                                <label for="observaciones">Observaciones</label>
                                <textarea id="observaciones" class="form-control" placeholder="Notas/observaciones (opcional)"></textarea>
                            </div>
                            <div class="col-md-3 text-end">
                                <button id="btnAccion" class="btn btn-primary mt-4">Crear Requisición</button>
                            </div>
                        </div>

                        <table class="table table-bordered" id="tblNuevaRequisicion">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- rows from localStorage -->
                            </tbody>
                        </table>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5>Total: <span id="totalPagar">0</span></h5>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4>Historial de Requisiciones</h4>
                        <table class="table table-striped" id="tblHistorialRequisiciones">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Solicitante</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
require 'views/footer.php';
?>