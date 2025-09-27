<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
         <div class="row mb-12">
		  <div class="col-2">
                        <label for="docuemiPlan">Documentos</label>
                        <select id="docuemiPlan" class="form-control"> 
                            <option value="FACTURA">FACTURA</option>
                            <option value="CREDITO FISCAL">CREDITO FISCAL</option>
							 <option value="EXPORTACION" hidden>EXPORTACION</option>
                        </select>
                    </div>
					<div class="col-md-3">
					                        <label>Nombre de Cliente</label>
                        <div class="input-group mb-2">

                            <input class="form-control" type="text" id="cliente" value="<?php echo $data["Venta"]["nombre"]; ?>" disabled>
                        </div>
						</div>
						                    <div class="col-md-3">

                        <label>Usuario Activo</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input class="form-control" type="text" id="vende" value="<?php echo $_SESSION['nombre_usuario']; ?>" placeholder="Vendedor" disabled>
                        </div>
                    </div>
					<div class="col-md-3">
					                        <div class="form-group mb-2">
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
						</div>
						
						
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

						
		</div>
					
					
				<div class="col-md-2">
				<input class="form-control" type="text" id="idClientePlan" value="<?php echo $data["Venta"]["id_cliente"]; ?>" hidden>
				<input class="form-control" type="text" id="idVentaPlan" value="<?php echo $data['id']; ?>" hidden>
				<input class="form-control" type="text" id="pdv" value="<?php echo $data['pdv']; ?>" hidden>
				<input class="form-control" type="text" id="idPlan" value="<?php echo $data['idPlan']; ?>" hidden>
				<input class="form-control" type="text" id="idCredito" value="<?php echo $data['idCredito']; ?>" hidden>
				
				                <div class="input-group mb-2" id="containerCodigo" hidden>
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input class="form-control" type="text" id="buscarProductoCodigo" placeholder="Ingrese Barcode - Enter" autocomplete="off">
                </div>

                <!-- input para buscar nombre -->
                <div class="input-group d-none mb-2" id="containerNombre" hidden>
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input class="form-control" type="text" id="buscarProductoNombre" placeholder="Buscar Producto" autocomplete="off">
                </div>
				
				 <div class="input-group d-none mb-2" id="containerGasto" hidden>
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input class="form-control" type="text" id="buscarGasto" placeholder="Ingresar gasto" autocomplete="off">
                </div>
				
				<div class="col-md-6" hidden>
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

		 										<div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle" id="tblPlanes" style="width: 100%;">
                        <thead>
                            <tr>
							    <th>Num Cuota</th>
                                <th>Vencimiento de cuota</th>
								<th>Capital</th>
                                <th>Interes</th>
                                <th>Seguro</th>
								<th>Monto Cuota</th>
								<th>Mora</th>
								<th>Estado</th>
								<th></th>
                            </tr>
						</thead>
						<tbody>
	<?php  $planDetalle = json_decode($data['planPago']['detalle_plan'],true);   foreach ($planDetalle as $detalle) { ?>
<tr>
     <td><?php echo $detalle['numCuota'];?></td> 
       <td><?php echo $detalle['fechaPago'];?></td>
       <td><?php echo '$'.$detalle['capital'];?></td>
	   <td><?php echo '$'.(number_format($detalle['interes'],2,'.',''));?></td>
	   <td><?php echo '$'.(number_format($detalle['Seguro'],2,'.',''));?></td>
	   <td><?php echo '$'.$detalle['Cuota'];?></td>
	   <?php if ($detalle['Pago'] == "Pendiente"){?>
	   <td><input class="form-control" type="text" id="<?php echo 'mora_'.$detalle['numCuota'];?>" value="<?php $hoy = date("Y-m-d");   $date1 = new DateTime($hoy);   $fechaPago = new DateTime($detalle['fechaPago']);   if($date1>$fechaPago){echo ($data['interesMora']['tasa']/100) * $detalle['Cuota'];}else{echo "0.0";}?>" disabled></td>
		<td> <button class="btn btn-success" type="button" id="<?php echo 'btnaccion_'.$detalle['numCuota'];?>" onclick="pagarCuota(<?php echo $detalle['numCuota'].','.$detalle['Cuota'];?>)">Pagar</button> 
		<button class="btn btn-danger" type="button" id="<?php echo 'btnaccion_'.$detalle['numCuota'];?>" onclick="pagarCuotaContingencia(<?php echo $detalle['numCuota'].','.$detalle['Cuota'];?>)">Contingencia</button>
		</td>  	   
	   <?php } else { ?>
	   <td><input class="form-control" type="text" id="<?php echo $detalle['numCuota'];?>" disabled></td>
	    <td><?php echo $detalle['Pago'];?></td>
		 <?php if ($detalle['Pago'] == "Pagado"){?>
		<td><a class="btn btn-warning" href="#" onclick="anularVenta(<?php echo $data['id'].','.$data['idPlan'].','.$detalle['numCuota'];?>)"><i class="fas fa-trash"></i></a></td>
	   <?php } ?>
		
	   <?php } ?>
</tr>
<?php } ?>
                        </tbody>
                    </table>
                </div>
    </div>
</div>

<?php include_once 'views/templates/footer.php'; ?>