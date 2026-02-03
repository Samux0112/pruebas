<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-ventas-tab" data-bs-toggle="tab" data-bs-target="#nav-ventas" type="button" role="tab" aria-controls="nav-ventas" aria-selected="true">Notas de crédito</button>
                <button class="nav-link" id="nav-historial-tab" data-bs-toggle="tab" data-bs-target="#nav-historial" type="button" role="tab" aria-controls="nav-historial" aria-selected="false">Historial</button>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active p-3" id="nav-ventas" role="tabpanel" aria-labelledby="nav-ventas-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fas fa-cash-register"></i>Nota de Crédito</h5>
                <hr>


                <div class="row mb-2">
                    <div class="col-md-2">
                        <label for="docuemi">Documentos</label>
                        <select id="docuemi" class="form-control" disabled>
                            <option value="Nota de credito">Nota de credito</option>
                        </select>
                    </div>
					
					    <div class="col-md-2">
                        <label>Tipo Documento</label>
                        <div class="input-group mb-2">

                            <input class="form-control" type="text" id="numdocu" placeholder="Tipo Documento" disabled>
                        </div>
                    </div>


				<div class="col-md-6 mb-3">
                            <label for="departamento">Numero Control</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input class="form-control" list="listaCodigoGeneracion" name="codigoGeneracion" id="codigoGeneracion" placeholder = "Codigo Generacion">
									<datalist id="listaCodigoGeneracion">

									<?php
									$servername = HOST;
									$username = USER;
									$password = PASS;
									$dbname = DBNAME;

									// Create connection
									$conn = new mysqli($servername, $username, $password, $dbname);
									// Check connection
									if ($conn->connect_error) {
									  die("Connection failed: " . $conn->connect_error);
									}
									date_default_timezone_set('America/El_Salvador');
		                            $anio = date("Y");
									$sql = "select numeroControlDte from ventas where docuemi = 'CREDITO FISCAL' AND estado !=0 and fecha like '%".$anio."%'";
									$result = $conn->query($sql);

									if ($result->num_rows > 0) {
									  // output data of each row
									  while($row = $result->fetch_assoc()) {
										echo "<option value='".$row["numeroControlDte"]."'>";
									  }
									} else {
									  echo "0";
									}
									$conn->close();

									?>
									 
									</datalist>
                            </div>
                            <span id="errorCorreo" class="text-danger"></span>
                        </div>

					
					<div class="col-md-2">
                        <label for="aplicaciones">Aplicaciones</label>
                        <select id="aplicaciones" class="form-control">
                            <option value="sinAplicaciones">Sin aplicaciones</option>
                            <option value="retencion">Retencion</option>
                            <option value="Percepcion">Percepcion</option>
                        </select>
                    </div>
                </div>

                <br>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="btn-group btn-group-toggle mb-2" data-toggle="buttons">
                            <label class="btn btn-primary">
                                <input type="radio" id="barcode" checked name="buscarProducto"><i class="fas fa-barcode"></i> Barcode
                            </label>
                            <label class="btn btn-info">
                                <input type="radio" id="nombre" name="buscarProducto"><i class="fas fa-list"></i> Nombre
                            </label>
							 
                            <label class="btn btn-warning">
                                <input type="checkbox" id="chekExento"> Exento
                            </label>
							<label class="btn btn-success">
                                <input type="radio" id="gasto" name="buscarProducto"><i class="fas fa-list"></i> Servicio
                            </label>
                        
                   
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
				
				<div class="input-group d-none mb-2" id="containerGasto">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input class="form-control" type="text" id="buscarGasto" placeholder="Ingresar gasto" autocomplete="off">
                </div>

                <span class="text-danger fw-bold mb-2" id="errorBusqueda"></span>

                <!-- table productos -->

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" id="tblNuevaVenta" style="width: 100%;">
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
                    <div class="col-md-4">
					<label>Fecha Emision</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input class="form-control" type="text" id="fEmi" disabled>
                        </div>
					   <label>Codigo de Generacion</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input class="form-control" type="text" id="codigoGen" disabled>
                        </div>
					
                        <label>Nombre Cliente</label>
                        <div class="input-group mb-2">
                            <input type="hidden" id="idCliente" value="1">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input class="form-control" type="text" id="buscarCliente" disabled>
                        </div>
						
						<label>NIT</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input class="form-control" type="text" id="nitCliente" disabled>
                        </div>

                        <span class="text-danger fw-bold mb-2" id="errorCliente"></span>

                    <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" id="tblDetalleVenta" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>SubTotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
				        <label>Descuento en Ventas Gravadas </label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="desGravadas" disabled>
                        </div>
				       <label>IVA(13%)</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="ivaVenta" disabled>
                        </div>
						
					     <label>Subtotal</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="subTotalVenta" disabled>
                        </div>
						
						<label>Monto Total</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="montoTotalVenta" disabled>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <label>Usuario Activo</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input class="form-control" type="text" value="<?php echo $_SESSION['nombre_usuario']; ?>" placeholder="Vendedor" disabled>
                        </div>


                        <div class="row">
                            <div class="input-group mb-2">
                                <input class="form-control" type="text" id="pagar_con" placeholder="0.00" hidden>
                            </div>

                            
                            <div class="input-group mb-2">
                                
                                <input class="form-control" type="text" id="cambio" placeholder="0.00" readonly hidden>
                            </div>
                        </div>


                        <label>Descuento</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="descuento" placeholder="Descuento">
                        </div>
						<label>Exentas</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="exentas" placeholder="exentas" disabled >
                        </div>
						 <label>Gravadas</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="gravadas" placeholder="gravadas" disabled >
                        </div>
						<label>Iva(13%)</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="iva" placeholder="iva" disabled>
                        </div>
						<label>(-)Iva Retenido</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="ivaRetenido" placeholder="ivaRetenido" disabled>
                        </div>


                        <label>Monto total</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="totalPagar" placeholder="Total Pagar" disabled >
                            <input class="form-control" type="hidden" id="totalPagarHidden">
                        </div>

                        <div class="form-group mb-2">
                            <label for="metodo">Metodo</label>
                            <select id="metodo" class="form-control" disabled>
                                <option value="CONTADO">CONTADO</option>
                                <option value="CREDITO">CREDITO</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-primary" type="button" id="btnAccion">Completar</button>
                        </div>
						<br>
						 <div class="d-grid">
                            <button class="btn btn-danger" type="button" id="btnContingencia">Cotingencia</button>
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
                                <th>Hora</th>
                                <th>Total</th>
                                <th>Descuento</th>
                                <th>Cliente</th>
                                <th>forma de pago</th>
                                <th>Docuemitido</th>
								<th>Número de Control</th>
								<th>Codigo generación</th>




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