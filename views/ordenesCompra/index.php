<?php
require 'views/header.php';
?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2>Órdenes de Compra</h2>
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="buscarProveedor">Proveedor</label>
                                <input type="text" id="buscarProveedor" class="form-control" placeholder="Buscar proveedor">
                                <input type="hidden" id="idProveedor">
                                <div id="proveedorDireccion" class="text-muted"></div>
                            </div>
                            <div class="col-md-6 text-end">
                                <button id="btnAccion" class="btn btn-primary mt-4">Registrar Orden</button>
                            </div>
                        </div>

                        <table class="table table-bordered" id="tblNuevaOrden">
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
            </div>
        </div>
    </div>
</div>

<?php
require 'views/footer.php';
?>