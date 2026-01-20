<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-ventas-tab" data-bs-toggle="tab" data-bs-target="#nav-ventas" type="button" role="tab" aria-controls="nav-ventas" aria-selected="true">Ventas</button>
                <button class="nav-link" id="nav-historial-tab" data-bs-toggle="tab" data-bs-target="#nav-historial" type="button" role="tab" aria-controls="nav-historial" aria-selected="false">Historial</button>
				<button class="nav-link" id="nav-planes-tab" data-bs-toggle="tab" data-bs-target="#nav-planes" type="button" role="tab" aria-controls="nav-planes" aria-selected="false">Planes de pago</button>
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active p-3" id="nav-ventas" role="tabpanel" aria-labelledby="nav-ventas-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fas fa-cash-register"></i> Nueva Ventass</h5>
                <hr>


                <div class="row mb-2">
								<?php 
				if($data['cotizacion']>0){ ?>
				       <div class="col-md-2">
                        <label for="docuemi">Documentos</label>
                        <select id="docuemi" class="form-control">
                            <option value="<?php echo $data['pedido']['docuemi']; ?>"><?php echo $data['pedido']['docuemi']; ?></option>
                            <option value="CREDITO FISCAL">CREDITO FISCAL</option>
                            <option value="FACTURA">FACTURA</option>
                            <option value="EXPORTACION">EXPORTACION</option>
                            <option value="RECIBO DE VENTA">RECIBO DE VENTA</option>
                            <option value="NOTA DE REMISION">NOTA DE REMISION</option>
                        </select>
                    </div>
					
				<?php }else{ ?>
				
				<div class="col-md-2">
                        <label for="docuemi">Documentos</label>
                        <select id="docuemi" class="form-control">
                            <option value="CREDITO FISCAL">CREDITO FISCAL</option>
                            <option value="FACTURA">FACTURA</option>
                            <option value="EXPORTACION">EXPORTACION</option>
                            <option value="RECIBO DE VENTA">RECIBO DE VENTA</option>
                            <option value="NOTA DE REMISION">NOTA DE REMISION</option>
                        </select>
                    </div>
				<?php
				}
				?>


					<input class="form-control" type="text" id="pdv" name="pdv" value="<?php echo $data['pdv']['codPuntoVentaMH']; ?>" hidden>
                   <div class="col-md-2">
                        <label>Tipo Documento</label>
                        <div class="input-group mb-2">

                            <input class="form-control" type="text" id="numdocu" placeholder="Tipo Documento" disabled>
                        </div>
                    </div>
                    
                    <div class="col-md-3">

                        <label>Usuario Activo</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input class="form-control" type="text" value="<?php echo $_SESSION['nombre_usuario']; ?>" placeholder="Vendedor" disabled>
                        </div>
                    </div>
                        

                    <div class="col-md-3">
                        <br>
                        <div class="input-group mb-2">
                            <span class="input-group-text">Serie</span>
                            <input class="form-control" type="text" value="<?php echo $data['serie'][0]; ?>" disabled>
                        </div>
                    </div>


                    <div class="col-md-2">
                        <br>
                        <div class="btn-group-toggle float-end" data-toggle="buttons">
                            <label class="btn btn-primary">
                                <input type="checkbox" id="impresion_directa"> Impresion Directa
                            </label>
                        </div>
                    </div>
					
					<div class="col-md-2">
                        <label for="aplicaciones">Aplicaciones</label>
                        <select id="aplicaciones" class="form-control">
                            <option value="sinAplicaciones">Sin aplicaciones</option>
                            <option value="retencion">Retencion</option>
                            <option value="Percepcion">Percepcion</option>
                        </select>
                    </div>
                    
                    <!-- inicio cambios nuevos mh -->
                    
                     <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Tipo Operacion</label>
                            <div class="input-group">
                                <label class="input-group-text" for="tipo_operacion"><i class="fas fa-id-card"></i></label>
                                <select class="form-select" id="tipo_operacion" name="tipo_operacion"> 
                                    <option value="1">Gravada</option>
									<option value="2">No Gravada o Exento</option>
									<option value="3">Exluido o No Constituye Renta</option>
									<option value="4">Mixta</option>
									<option value="12">Ingresos que ya fueron sujetos de retención en F910</option>
									<option value="13">Sujetos pasivos excluidos (art. 6 LISR) e ingresos que no constituyen hecho generador del ISR</option>
                                </select>
                            </div>
                     </div>
							<div class="col-lg-4 col-sm-6 mb-2">
                            <label>Tipo Ingreso</label>
                            <div class="input-group">
                                <label class="input-group-text" for="tipo_ingreso"><i class="fas fa-id-card"></i></label>
                                <select class="form-select" id="tipo_ingreso" name="tipo_ingreso"> 
                                    <option value="1">Profesiones, Artes y Oficios</option>
									<option value="2">Actividades de Servicios</option>
									<option value="3">Actividades Comerciales</option>
									<option value="4">Actividades Industriales</option>
									<option value="5">Actividades Agropecuarias</option>
									<option value="6">Utilidades y Dividendos</option>
									<option value="7"> Exportaciones de bienes</option>
									<option value="8">Servicios Realizados en el Exterior y Utilizados en El Salvador</option>
									<option value="9">Exportaciones de servicios</option>
									<option value="10"> Otras Rentas Gravables</option>
									<option value="12"> Ingresos que ya fueron sujetos de retención en F910</option>
									<option value="13"> Sujetos pasivos excluidos (art. 6 LISR) e ingresos que no constituyen hecho generador del ISR</option>
                                </select>
                            </div>
                </div>
				                    <div class="col-md-3">
                        <br>
                        <div class="input-group mb-2">
                            <span class="input-group-text">N. Pedido</span>
                            <input class="form-control" id="idPedido" name="idPedido" type="text" value="<?php echo $data['cotizacion']; ?>" disabled>
                        </div>
                    </div>
                            <div class="col-lg-4 col-sm-6 mb-2">
                           <label for="codigoValores">Título a que se remiten los bienes</label>
                           <div class="input-group">
                           <label class="input-group-text" for="codigoValores"><i class="fas fa-id-card"></i></label>
                           <select id="codigoValores" class="form-control">
                               <option value="01">01 Depósito</option>
                               <option value="02">02 Propiedad</option>
                               <option value="03">03 Consignación</option>
                               <option value="04">04 Traslado</option>
                               <option value="05">05 Otros</option>
                           </select>
                            </div>
                       </div>
                       <div class="col-md-12 mb-2">
                                                <label for="observaciones">Observaciones</label>
                                                <textarea id="observaciones" name="observaciones" class="form-control" rows="2" placeholder="Observaciones para la factura..." ><?php echo $data['pedido']['observaciones']; ?></textarea>
                                            </div>
                        </div>
                 <!-- fin cambios nuevos mh -->
                
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
							  <div class="btn-group-toggle float-end" data-toggle="buttons">
                            <label class="btn btn-warning">
                                <input type="checkbox" id="chekExento"> Exento
                            </label>
							<label class="btn btn-success">
                                <input type="radio" id="gasto" name="buscarProducto"><i class="fas fa-list"></i> Servicio
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
                        <label>Buscar Cliente</label>
                        <div class="input-group mb-2">
                            <input type="hidden" id="idCliente" value="<?php echo $data['cliente']['id']; ?>">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input class="form-control" type="text" id="buscarCliente" placeholder="Buscar Cliente" value="<?php echo $data['cliente']['nombre']; ?>">
                        </div>

                        <span class="text-danger fw-bold mb-2" id="errorCliente"></span>

                        <label>Telefono</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input class="form-control" type="text" id="telefonoCliente" placeholder="Telefono" value="<?php echo $data['cliente']['telefono']; ?>" disabled>
                        </div>

                      

                        <label>Direccion</label>
                        <ul class="list-group">
                            <li class="list-group-item" id="direccionCliente"><i class="fas fa-home"></i><?php echo $data['cliente']['direccion']; ?></li>
                        </ul>
                        
                        
                      
                        <div class="form-group mb-2" hidden>
                            <label for="forma">Forma de Pago</label>
                            <select id="forma" class="form-control">
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Bitcoin">Bitcoin</option>
                                <option value="Gift Card">Gift Card</option>
                            </select>
                        </div>


                        <div class="form-group mb-2">
                            <label>Forma de Pago</label> 
                            <div class="input-group"><label class="input-group-text" for="formaPago"><i class="fas fa-id-card"></i></label>
                            <select class="form-select" id="formaPago" name="formaPago">
                            <?php foreach ($data['formasPago'] as $formas) { ?>
                            <option value="<?php echo $formas['catMH'];?>"><?php echo $formas['nombre'];?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>

                        <label>Metodo forma de pago</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="forma2" placeholder="Forma2">
                        </div>
                        
                        
                            <?php if($data['cotizacion']>0){ ?>

                        <div class="form-group mb-2">
                            <label for="vende">Vendedor</label>
                            <select id="vende" class="form-control">
                                <option value="<?php echo $data['pedido']['vende']; ?>"><?php echo $data['pedido']['vende']; ?></option>
                                <option value="Casa Matriz">Casa Matriz</option>
                                <option value="LUIS ESCOBAR">LUIS ESCOBAR</option>
                                <option value="JULIO LOPEZ">JULIO LOPEZ</option>
                                <option value="RUBEN ESCOBAR">RUBEN ESCOBAR</option>
                                <option value="JOSE MARIA ROMERO">JOSE MARIA ROMERO</option>				
                                <option value="NICOLAS GONZALEZ">NICOLAS GONZALEZ</option>
                                <option value="LUIS BAUTISTA">LUIS BAUTISTA</option>
                                <option value="DEPARTAMENTO DE REPUESTOS">DEPARTAMENTO DE REPUESTOS</option>
                                <option value="ROSA ALAS">ROSA ALAS</option>
                                <option value="MARLON TORRES">MARLON TORRES</option>
                                <option value="VENTA DE SALA SUCURSAL LAYCO">VENTA DE SALA SUCURSAL LAYCO</option>
                                <option value="CHRISTOPHER OSORIO">CHRISTOPHER OSORIO</option>
                                <option value="EDWIN JHOAN AGUILAR">EDWIN JHOAN AGUILAR</option>
                                <option value="JOCELYN MEJIA">JOCELYN MEJIA</option>
                                <option value="VICTOR BONIFACIO">VICTOR BONIFACIO</option>
                            </select>
                        </div>
                        <?php }else{ ?>
                            <div class="form-group mb-2">
                            <label for="vende">Vendedor</label>
                            <select id="vende" class="form-control">
                                <option value="Casa Matriz">Casa Matriz</option>
                                <option value="LUIS ESCOBAR">LUIS ESCOBAR</option>
                                <option value="JULIO LOPEZ">JULIO LOPEZ</option>
                                <option value="RUBEN ESCOBAR">RUBEN ESCOBAR</option>
                                <option value="JOSE MARIA ROMERO">JOSE MARIA ROMERO</option>				
                                <option value="NICOLAS GONZALEZ">NICOLAS GONZALEZ</option>
                                <option value="LUIS BAUTISTA">LUIS BAUTISTA</option>
                                <option value="DEPARTAMENTO DE REPUESTOS">DEPARTAMENTO DE REPUESTOS</option>
                                <option value="ROSA ALAS">ROSA ALAS</option>
                                <option value="MARLON TORRES">MARLON TORRES</option>
                                <option value="VENTA DE SALA SUCURSAL LAYCO">VENTA DE SALA SUCURSAL LAYCO</option>
                                <option value="CHRISTOPHER OSORIO">CHRISTOPHER OSORIO</option>
                                <option value="EDWIN JHOAN AGUILAR">EDWIN JHOAN AGUILAR</option>
                                <option value="JOCELYN MEJIA">JOCELYN MEJIA</option>
                            </select>
                        </div>
                        <?php } ?>
                        
                        
                        <label>Pagar con</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input class="form-control" type="text" id="pagar_con" placeholder="0.00">
                            </div>
                            
                            
                             <label>Cambio</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                <input class="form-control" type="text" id="cambio" placeholder="0.00" readonly>
                            </div>
							
							<label>Monto</label>
                        <div class="input-group mb-2"> 
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="monto" placeholder="Monto" disabled>
                        </div>
						
						<label>Plazo en meses</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-spinner"></i></span>
                            <input class="form-control" type="text" id="plazo" placeholder="Plazo en meses" disabled>
                        </div>
						
						<label>Interes</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fa-solid fa-percent"></i></span>
                            <input class="form-control" type="text" id="interes" placeholder="Interes %" disabled>
                        </div>
						
						<label>Cuota seguro</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="plazoSeguro" placeholder="Cuota seguro" disabled>
                        </div>
						
						<div class="d-grid">
                            <button class="btn btn-primary" type="button" id="btnPlanPago" disabled>Generar plan de pago</button>
							 <button class="btn btn-danger" type="button" id="btnPlanPagoPDF" disabled>PDF</button>
                        </div>
						
						<div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" id="tblPlan" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th>Capital</th>
                                <th>Interes</th>
                                <th>Seguro</th>
								<th>Cuota</th>
								<th>Saldo Final</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                        

                    </div>

                    <div class="col-md-4">
                       
                      
                        <div class="row">
						
 
                       
						<?php 
						if($data['cotizacion']>0){?>
						<label>Descuento</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="descuento" value = "<?php echo $data['pedido']['descuento']; ?>" placeholder="Descuento">
                        </div>
						<?php
						}else{
						?>
						<label>Descuento</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="descuento" value="0" placeholder="Descuento">
                        </div>
						<?php } ?>

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


                        <label>Total a Pagar</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                            <input class="form-control" type="text" id="totalPagar" placeholder="Total Pagar" disabled >
                            <input class="form-control" type="hidden" id="totalPagarHidden">
                        </div>

                        <div class="form-group mb-2">
                            <label for="metodo">Metodo</label>
                            <select id="metodo" class="form-control">
                                <option value="CONTADO">CONTADO</option>
                                <option value="CREDITO">CREDITO</option>
								<option value="PLAZO">A PLAZO</option>
                            </select>
                        </div>
                        
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
                                <th>Referencia de pago</th>
                                <th>Metodo</th>
                                <th>Docuemitido</th>
								 <th>Correlativo</th>
                                <th>Vendedor</th>
                                <th>Cajero</th>
                                <th>Serie</th>
								<th>Numero de Control</th>
								<th>Codigo de generacion</th>




                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>
                    </table>

                </div>
            </div>
			
            <div class="tab-pane fade p-3" id="nav-planes" role="tabpanel" aria-labelledby="nav-planes-tab" tabindex="0">
			    <h5 class="card-title text-center"><i class="fas fa-cash-register"></i> Planes de pago</h5>
				                    <div class="col-md-4">
                        <label>Buscar Cliente</label>
                        <div class="input-group mb-2">
                            <input type="hidden" id="idClientePlan" value="1">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input class="form-control" type="text" id="buscarClientePlan" placeholder="Buscar Cliente">
                        </div>

                        <span class="text-danger fw-bold mb-2" id="errorCliente"></span>
						</div>
                <hr>
										<div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" id="tblPlanes" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>referencia</th>
                                <th>fecha</th>
                                <th>Monto Total</th>
                                <th>Estado</th>
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