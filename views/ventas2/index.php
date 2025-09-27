<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-ventas2-tab" data-bs-toggle="tab" data-bs-target="#nav-ventas2" type="button" role="tab" aria-controls="nav-ventas2" aria-selected="true">Compra</button>
                <button class="nav-link" id="nav-historial2-tab" data-bs-toggle="tab" data-bs-target="#nav-historial2" type="button" role="tab" aria-controls="nav-historial2" aria-selected="false">Historial</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active p-3" id="nav-ventas2" role="tabpanel" aria-labelledby="nav-ventas2-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fas fa-cash-register"></i> Nueva Compra</h5>
                <hr>
				 <div class="row mb-2">
				                     <div class="col-md-2">
                        <label for="docuemi">Documentos</label>
                        <select id="docuemi" class="form-control">
                            <option value="COMPRA">COMPRA</option>
                            <option value="SUJETO EXCLUIDO">SUJETO EXCLUIDO</option>
							 <option value="NOTA DE CREDITO">NOTA DE CREDITO</option>
                        </select>
                    </div>
					
						<div class="col-md-2">
                        <label for="claseDoc">Clase documento</label>
                        <select id="claseDoc" class="form-control">
                            <option value="1">IMPRESO</option>
                            <option value="4">DTE</option>
                        </select>
                    </div>
					
						<div class="col-md-2">
                        <label for="tipoDoc">Tipo documento</label>
                        <select id="tipoDoc" class="form-control">
                            <option value="03">CREDITO FISCAL</option>
                        </select>
                    </div>
					
															<div class="col-md-2">
                        <label for="aplicaciones">Aplicaciones</label>
                        <select id="aplicaciones" class="form-control">
                            <option value="sinAplicaciones">Sin aplicaciones</option>
                            <option value="Percepcion1">Percepcion 1%</option>
							<option value="Percepcion2">Percepcion 2%</option>
                        </select>
                    </div>
					
					<div class="col-md-2">
                        <label for="tipoDoc">Fecha</label>
                        <input type ="date" id="fechaFactura" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label for="tipoOperacionCompra">Tipo de operacion</label>
                        <select id="tipoOperacionCompra" class="form-control">
                            <option value="1">1Gravada</option>
                            <option value="2">2No Gravada</option>
							<option value="3">3Excluido o no Constituye Renta</option>
							<option value="4">4Mixto Contribuyentes que gozan de Regímenes Especiales con incentivos fiscales</option>
							<option value="9">9Excepciones Instituciones públicas, no inscritos a IVA, operaciones no deducibles para renta, entre otros.</option>
                            <option value="0">0Cuando se trate de periodos tributarios  anteriores a febrero de 2024</option>
                        </select>
                    </div>
					
					
					<div class="col-md-2">
                        <label for="clasificacion">Clasificacion</label>
                        <select id="clasificacion" class="form-control">
                            <option value="1">1Costo</option>
                            <option value="2">2Gasto</option>
                            <option value="9">9Excepciones Instituciones públicas, no inscritos a IVA, operaciones no deducibles para renta, entre otros.</option>
                            <option value="0">0Cuando se trate de periodos tributarios  anteriores a febrero de 2024</option>
                        </select>
                    </div>
					
					<div class="col-md-2">
                        <label for="sector">Sector</label>
                        <select id="sector" class="form-control">
                            <option value="1">1Industria</option>
                            <option value="2">2Comercio</option>
							<option value="3">3Agropecuario</option>
							<option value="4">4Servicios, Profesiones, Artes y Oficios</option>
							<option value="9">9Excepciones Instituciones públicas, no inscritos a IVA, operaciones no deducibles para renta, entre otros.</option>
                            <option value="0">0Cuando se trate de periodos tributarios  anteriores a febrero de 2024</option>
                        </select>
                    </div>
					
					<div class="col-md-3">
                        <label for="tipoGasto">Tipo de Costo / Gasto</label>
                        <select id="tipoGasto" class="form-control">
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
					
					  <div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="for-bodegas">Bodegas <span class="text-danger">*</span></label>
                                <select id="bodegas" class="form-control" name="bodegas">
                                    <?php foreach ($data['bodegas'] as $bodega) { ?>
                                        <option value="<?php echo $bodega['id']; ?>"><?php echo $bodega['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <span id="errorMedida" class="text-danger"></span>
                        </div>
					
				 </div>
				 <hr>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="btn-group btn-group-toggle mb-2" data-toggle="buttons">
                            <label class="btn btn-primary">
                                <input type="radio" id="barcode" checked name="buscarProducto"><i class="fas fa-barcode"></i> Barcode
                            </label>
                            <label class="btn btn-info">
                                <input type="radio" id="nombre" name="buscarProducto"><i class="fas fa-list"></i> Nombre
                            </label>
							 <label class="btn btn-success">
                                <input type="radio" id="gasto" name="buscarProducto"><i class="fas fa-list"></i> Gasto
                            </label>
							 <div class="btn-group-toggle float-end" data-toggle="buttons" hidden>
                            <label class="btn btn-warning">
                                <input type="checkbox" id="chekServicio"> Servicio
                            </label>
                        </div>
						 <div class="btn-group-toggle float-end" data-toggle="buttons">
                            <label class="btn btn-secondary">
                                <input type="checkbox" id="chekExento"> Exento
                            </label>
                        </div>
                               
                           
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <div class="input-group">
                            <span class="input-group-text">Serie</span>
                            <input class="form-control" type="text" value="<?php echo $data['serie'][0]; ?>" disabled>
                        </div>

                        <label>Numc/ Codigo de Generacion(DTE)</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="numc" placeholder="NumC">
                        </div>



                    </div>
                    <div class="col-md-3">
                        <div class="btn-group-toggle float-end" data-toggle="buttons">
                            <label class="btn btn-primary">
                                <input type="checkbox" id="impresion_directa"> Impresion Directa
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
				
				                <!-- input para buscar gasto -->
                <div class="input-group d-none mb-2" id="containerGasto">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input class="form-control" type="text" id="buscarGasto" placeholder="Ingresar gasto" autocomplete="off">
                </div>

                <span class="text-danger fw-bold mb-2" id="errorBusqueda"></span>

                <!-- table productos -->

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" id="tblNuevaVenta2" style="width: 100%;">
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
                        <label>Buscar Proveedor</label>
                        <div class="input-group mb-2">
                            <input type="hidden" id="idCliente2" value="">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input class="form-control" type="text" id="buscarCliente2" placeholder="Buscar Proveedor">
                        </div>

                        <span class="text-danger fw-bold mb-2" id="errorCliente2"></span>

                        <label>Telefono</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input class="form-control" type="text" id="telefonoCliente2" placeholder="Telefono" disabled>
                        </div>

                        <label>Direccion</label>
                        <ul class="list-group">
                            <li class="list-group-item" id="direccionCliente2"><i class="fas fa-home"></i></li>
                        </ul>
                    </div>
                    

                    <div class="col-md-4">
                        <label>Comprador</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input class="form-control" type="text" value="<?php echo $_SESSION['nombre_usuario']; ?>" placeholder="Vendedor" disabled>
                        </div>

                       
                        <label>Descuento</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="descuento" placeholder="Descuento">
                        </div> 
						<label>Sub Total</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="subTotal" placeholder="Sub Total" disabled>
                        </div>
						
						 <label>Retencion Renta</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="rRenta" placeholder="Retencion Renta">
                        </div>
						
						<label>Compras gravadas</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
							
                            <input class="form-control" type="text" id="cGravadas" placeholder="Compras gravadas" disabled>
                        </div>
						
						<label>Compras Exentas</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
							
                            <input class="form-control" type="text" id="cExentas" placeholder="Compras Exentas" disabled>
                        </div>
						
						<label>IVA 13%</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
							
                            <input class="form-control" type="text" id="cIva" placeholder="IVA" disabled>
                        </div>
						<label>Fovial</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
							
                            <input class="form-control" type="text" id="cFovial" placeholder="FOVIAL" disabled>
                        </div>
						<label>Cotrans</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
							
                            <input class="form-control" type="text" id="cCotrans" placeholder="Cotrans" disabled>
                        </div>
						<label>Percepcion</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
							
                            <input class="form-control" type="text" id="cPercepcion" placeholder="Percepcion" disabled>
                        </div>

                        <label>Total a Pagar</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
							
                            <input class="form-control" type="text" id="totalPagar" placeholder="Total Pagar" disabled>
                        </div>


                        <div class="form-group mb-2">
                            <label for="metodo">Metodo</label>
                            <select id="metodo" class="form-control">
                                <option value="CONTADO">CONTADO</option>
                                <option value="CREDITO">CREDITO</option>
                            </select>
                        

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
            </div>
                      

                   

            <div class="tab-pane fade p-3" id="nav-historial2" role="tabpanel" aria-labelledby="nav-historial2-tab" tabindex="0">
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
                    <table class="table table-bordered table-striped table-hover align-middle nowrap" id="tblHistorial2" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Total</th>
                                <th>Proveedor</th>
                                <th>Serie</th>
                                <th>numc</th>
                                <th>Metodo</th>
								<th>Docuemi</th>
								<th>NumeroControl</th>
								<th>Codigo generacion</th>
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