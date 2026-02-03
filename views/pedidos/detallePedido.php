<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-cotizaciones-tab" data-bs-toggle="tab" data-bs-target="#nav-cotizaciones" type="button" role="tab" aria-controls="nav-cotizaciones" aria-selected="true">Pedido</button>
				
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active p-3" id="nav-cotizaciones" role="tabpanel" aria-labelledby="nav-cotizaciones-tab" tabindex="0">
			<h5 class="card-title text-center"><i class="fas fa-list-alt"></i>Detalle Pedido</h5>
			<input type='text' id="idPedido" name="idPedido" value="<?php echo  $data['cotizacion']['id']; ?>" hidden>
                <div class="table-responsive">
				<div class="container-impresion">
				 <table class="table table-bordered table-striped table-hover align-middle nowrap" id="tblHistorial1" style="width: 100%;">
				 <thead>
                            <tr>
								<th>Pedido</th>
								<th>Fecha</th>
								<th>Hora</th>
								<th>Estado</th>
                            </tr>
                        </thead>

                                <tbody>
								 <td><?php echo  $data['cotizacion']['id']; ?></td>
								  <td><?php echo $data['cotizacion']['fecha']; ?></td>
								   <td><?php echo $data['cotizacion']['hora']; ?></td>
								    <td><?php echo $data['cotizacion']['estadoPedido']; ?></td>
								</tbody>
								</table>

                </div>
                    <table class="table table-bordered table-striped table-hover align-middle nowrap" id="tblHistorial" style="width: 100%;">
                        <thead>
                            <tr>

								<th>Cant</th>
								<th>Descripción</th>
                            </tr>
                        </thead>

                                <tbody>
            <?php
            $productos = json_decode($data['cotizacion']['productos'], true);

            foreach ($productos as $producto) { ?>
                <tr>
					
                    <td><?php echo $producto['cantidad']; ?></td>
                    <td><?php echo $producto['nombre']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
                    </table>
                </div>
				
				<div class="col-md-4">
				                        <div class="form-group mb-2">
                            <label for="metodo">Actualizar estado</label>
                            <select id="metodo" class="form-control">
                                <option value="EN PROCESO">EN PROCESO</option>
                                <option value="COMPLETADO">COMPLETADO</option>
								<option value="CANCELADO">CANCELADO</option>
                            </select>
                        </div>
						
						
						</div>
						<div class="col-md-4 mb-3">
                            <div class="form-group">
                                <label for="pe"><h4>Comentarios:</h4></label>
                                <textarea id="comentarios" class="form-control" name="comentarios" rows="3"><?php echo $data['cotizacion']['comentarios']; ?></textarea>
                            </div>

                        </div>
						
						<div class="col-md-4 mb-3">
						<div class="d-grid">
						<?php if($data['cotizacion']['metodo']=='COMPLETADO'){ ?> 
                            <button class="btn btn-primary" type="button" id="btnAccion" disabled>Completar</button>
						<?php }else{ ?>
						<button class="btn btn-primary" type="button" id="btnAccion">Completar</button>
						<?php } ?> 
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
								<th>Pedido</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Cliente</th>
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