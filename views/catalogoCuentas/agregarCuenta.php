<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div></div>
        </div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-usuarios-tab" data-bs-toggle="tab" data-bs-target="#nav-usuarios" type="button" role="tab" aria-controls="nav-usuarios" aria-selected="true">Cuentas Contables</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active mt-2" id="nav-usuarios" role="tabpanel" aria-labelledby="nav-usuarios-tab" tabindex="0">
			<form class="p-4" id="formulario" autocomplete="off">
				<input type="text" id="ctaMayor" name="ctaMayor" class="form-control" value = "<?php echo $data['id'];?>" hidden>
				<input type="text" id="idCuenta" name="idCuenta" class="form-control" value = "<?php echo $data['idCuenta'];?>" hidden>
				<input type="text" id="idNaturaleza" name="idNaturaleza" class="form-control" value = "<?php echo $data['cuenta']['id_naturaleza'];?>" hidden>
                    <div class="row">
					<div class="col-lg-4 col-sm-6 mb-2">
					
                            <label>Codigo de Cuenta</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                <input type="text" id="codCuenta" name="codCuenta" class="form-control" value="<?php if($data['idCuenta']==""){if($data['maxCuenta']['maxCuenta']<7){ echo $data['maxCuenta']['maxCuenta'].'1';}else{ $cta = strlen($data['maxCuenta']['maxCuenta']) -2; $corr = substr($data['maxCuenta']['maxCuenta'],$cta); if($corr==99){ $corr = $corr+1; $ctaP = substr($data['maxCuenta']['maxCuenta'],0,$cta); echo $ctaP.''.$corr;}else{ echo $data['maxCuenta']['maxCuenta'] +1; }} }else{ echo $data['maxCuenta']['maxCuenta']; }?>">
                            </div>
                            <span id="errorcodPuntoVenta" class="text-danger"></span>
                        </div>
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Descripción</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input type="text" id="descripcion" name="descripcion" class="form-control" value="<?php if($data['idCuenta']==""){ echo "";}else{echo $data['cuenta']['nombre_cuenta'];} ?>">
                            </div>
                            <span id="errordescripcion" class="text-danger"></span>
                        </div>
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Cuenta mayor</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                <input type="text" id="ctaMayorNombre" name="ctaMayorNombre" class="form-control" value="<?php if($data['idCuenta']=="") {echo $data['id'].' - '.$data['cuenta']['nombre_cuenta'];} else{ echo $data['cuenta']['padre']['codigo'].' - '.$data['cuenta']['padre']['nombre_cuenta']; } ?>" disabled>
                            </div>
                            <span id="errorcodPuntoVenta" class="text-danger"></span>
                        </div>
						 <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Nivel</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                <input type="text" id="nivel" name="nivel" class="form-control" value="<?php if($data['idCuenta']=="") {echo $data['cuenta']['nivel'] + 1;} else { echo $data['cuenta']['nivel']; } ?>">
                            </div>
                            <span id="errorcodPuntoVenta" class="text-danger"></span>
                        </div>
						<div class="col-lg-4 col-sm-6 mb-2">
                            <label>Naturaleza</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                <input type="text" id="naturaleza" name="naturaleza" class="form-control" value="<?php echo $data['cuenta']['naturaleza'] ?>">
                            </div>
                            <span id="errorcodPuntoVenta" class="text-danger"></span>
                        </div>
                    <div class="col-md-2">
                        <label for="mayor">Mayor</label>
                        <select id="mayor" name="mayor" class="form-control" value="<?php if($data['idCuenta']!="") {} ?>">
						<?php
						if($data['idCuenta']!=""){?>
						<option value="<?php echo $data['cuenta']['mayor'];?>"><?php echo $data['cuenta']['mayor'];?></option>
						<option value="NO">NO</option>
                        <option value="SI">SI</option>
						<?php }else{ ?>

						<option value="NO">NO</option>
                        <option value="SI">SI</option>
						<?php }?> 
                        </select>
                    </div>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-danger" type="button" id="btnNuevo">Regresar</button>
                        <button class="btn btn-primary" type="submit" id="btnAccion">Registrar</button>
                    </div>
                </form>

            </div>
            <div class="tab-pane fade" id="nav-nuevo" role="tabpanel" aria-labelledby="nav-nuevo-tab" tabindex="0">
			                <h5 class="card-title text-center"><i class="fas fa-user"></i> Listado de puntos de venta</h5>
                <hr>
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover nowrap" id="tblUsuarios" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Codigo punto de venta MH</th>
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