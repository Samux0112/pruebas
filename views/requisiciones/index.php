<?php
require 'views/templates/header.php';
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
                                <button id="btnAccion" class="btn btn-primary mt-4">Crear Requisicion</button>
                            </div>
                        </div>

                        <!-- Search / Add product area (copied structure from cotizaciones) -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="btn-group btn-group-toggle mb-2" data-toggle="buttons">
                                    <label class="btn btn-primary">
                                        <input type="radio" id="barcode" checked name="buscarProducto"><i class="fas fa-barcode"></i> Barcode
                                    </label>
                                    <label class="btn btn-info">
                                        <input type="radio" id="nombre" name="buscarProducto"><i class="fas fa-list"></i> Nombre
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" id="gasto" name="buscarProducto"><i class="fas fa-list"></i> Gasto
                                    </label>
                                    <!-- hidden flags used by agregarProducto / busqueda.js -->
                                    <input type="checkbox" id="chekExento" style="display:none;">
                                    <input type="checkbox" id="chekServicio" style="display:none;">
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-2" id="containerCodigo">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input class="form-control" type="text" id="buscarProductoCodigo" placeholder="Ingrese Barcode - Enter" autocomplete="off">
                        </div>

                        <div class="input-group d-none mb-2" id="containerNombre">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input class="form-control" type="text" id="buscarProductoNombre" placeholder="Buscar Producto" autocomplete="off">
                        </div>

                        <div class="input-group d-none mb-2" id="containerGasto" hidden>
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input class="form-control" type="text" id="buscarGasto" placeholder="Ingresar gasto" autocomplete="off">
                        </div>

                        <span class="text-danger fw-bold mb-2" id="errorBusqueda"></span>

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
                            <div class="col-md-6 d-none">
                                <!-- Total a Pagar oculto -->
                                <label>Total a Pagar</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    <input class="form-control" type="text" id="totalPagar" name="totalPagar" placeholder="Total Pagar" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Fecha desde</label>
                                <input id="desde" class="form-control" type="date">
                                <label class="mt-2">Fecha hasta</label>
                                <input id="hasta" class="form-control" type="date">
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
                                    <!-- <th>Total</th> -->
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
require 'views/templates/footer.php';
?>