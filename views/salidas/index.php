<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-cotizaciones-tab" data-bs-toggle="tab" data-bs-target="#nav-cotizaciones" type="button" role="tab" aria-controls="nav-cotizaciones" aria-selected="true">Salidas</button>
                <button class="nav-link" id="nav-historial-tab" data-bs-toggle="tab" data-bs-target="#nav-historial" type="button" role="tab" aria-controls="nav-historial" aria-selected="false">Historial</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active p-3" id="nav-cotizaciones" role="tabpanel" aria-labelledby="nav-cotizaciones-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fas fa-list-alt"></i> Nueva Salida</h5>
                <hr>
                <div class="row mb-2">
                    <div class="col-md-12">

                    						 <div class="form-group">
                                <label for="for-bodegas">Bodega de Salida <span class="text-danger">*</span></label>
                                <select id="bodegaSalida" class="form-control" name="bodegaSalida">
                                    <?php foreach ($data['bodegas'] as $bodega) { ?>
                                        <option value="<?php echo $bodega['id']; ?>"><?php echo $bodega['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
							
							                            <div class="form-group">
                                <label for="for-bodegas">Bodega de Entrada <span class="text-danger">*</span></label>
                                <select id="bodegaEntrada" class="form-control" name="bodegaEntrada">
                                    <?php foreach ($data['bodegas'] as $bodega) { ?>
                                        <option value="<?php echo $bodega['id']; ?>"><?php echo $bodega['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                    </div>
                    <div class="col-md-6">
                        <div class="btn-group btn-group-toggle mb-2" data-toggle="buttons">
                            <label class="btn btn-primary">
                                <input type="radio" id="barcode" checked name="buscarProducto"><i class="fas fa-barcode"></i> Barcode
                            </label>
                            <label class="btn btn-info">
                                <input type="radio" id="nombre" name="buscarProducto"><i class="fas fa-list"></i> Nombre
                            </label>
							 <div class="btn-group-toggle float-end" data-toggle="buttons" hidden>
                            <label class="btn btn-warning">
                                <input type="checkbox" id="chekServicio"> Servicio
                            </label>
                        </div>
						 <div class="btn-group-toggle float-end" data-toggle="buttons" hidden>
                            <label class="btn btn-secondary">
                                <input type="checkbox" id="chekExento"> Exento
                            </label>
                        </div>
                        </div>
                    </div>

                </div>

                <!-- input para buscar codigo -->
                <div class="input-group mb-2" id="containerCodigo">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input class="form-control" type="text" id="buscarProductoCodigo" placeholder="Ingrese Barcode - Enter" autocomplete="off">
                </div>

                <!-- input para buscar nombre -->
                <div class="input-group d-none mb-2" id="containerNombre">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input class="form-control" type="text" id="buscarProductoNombre" placeholder="Buscar Producto" autocomplete="off">
                </div>
				 <div class="input-group d-none mb-2" id="containerGasto" hidden>
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input class="form-control" type="text" id="buscarGasto" placeholder="Ingresar gasto" autocomplete="off">
                </div>
				<input class="form-control" type="text" id="buscarGasto" placeholder="Ingresar gasto" autocomplete="off" hidden>
				<input type="radio" id="gasto" name="buscarProducto" hidden><i class="fas fa-list"></i> Servicio

                <span class="text-danger fw-bold mb-2" id="errorBusqueda"></span>

                <!-- table productos -->

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" id="tblNuevaCotizacion" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>SubTotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="row justify-content-between">
                    <div class="col-md-6">
					  <div class="col-md-6 mb-6"> 
                           
   
                        </div>
						

						


                        <label hidden>Telefono</label>
                        <div class="input-group mb-2" hidden>
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input class="form-control" type="text" id="telefonoCliente" placeholder="Telefono" disabled>
                        </div>

                        <label hidden>Dirección</label>
                        <ul class="list-group" hidden>
                            <li class="list-group-item" id="direccionCliente"><i class="fas fa-home"></i></li>
                        </ul>
                    </div>
					

                    <div class="col-md-6">
                        <label>Vendedor</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input class="form-control" type="text" value="<?php echo $_SESSION['nombre_usuario']; ?>" placeholder="Vendedor" disabled>
                        </div>

                        <label>Monto</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="totalPagar" placeholder="Monto" disabled>
                        </div>
						
						                         <label>Fecha de traslado</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input class="form-control" type="date" id="fecha_traslado" min="<?php echo date('Y-m-d'); ?>">
                        </div>
						
						<div class="input-group mb-2">
                        <div class="d-grid">
                            <button class="btn btn-primary" type="button" id="btnAccion">Completar</button>
                        </div>
                    </div>
						
                    </div>


                </div>
            </div>
            <div class="tab-pane fade p-3" id="nav-historial" role="tabpanel" aria-labelledby="nav-historial-tab" tabindex="0">
                <div class="d-flex justify-content-center mb-3">
                    <div class="form-group">
                        <label for="desde">Desde</label>
                        <input id="desde" class="form-control" type="date">
                    </div>
                    <div class="form-group">
                        <label for="hasta">Hasta</label>
                        <input id="hasta" class="form-control" type="date">
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle nowrap" id="tblHistorial" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Id traslado</th>
								<th>Bodega de Salida</th>
                                <th>Bodega de Ingreso</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/templates/footer.php'; ?>