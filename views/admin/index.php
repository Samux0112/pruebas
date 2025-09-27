<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <h5 class="card-title text-center">Datos de de Empresa</h5>
        <hr>
        <form class="p-4" id="formulario" autocomplete="off">
            <input type="hidden" id="id" name="id" value="<?php echo $data['empresa']['id']; ?>">
            <div class="row">
                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>IVA/Nit <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                        <input type="text" id="ruc" name="ruc" class="form-control" value="<?php echo $data['empresa']['ruc']; ?>" placeholder="Ruc">
                    </div>
                    <span id="errorRuc" class="text-danger"></span>
                </div>
                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>Nombre <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $data['empresa']['nombre']; ?>" placeholder="Nombre">
                    </div>
                    <span id="errorNombre" class="text-danger"></span>
                </div>
                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>Teléfono <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="number" id="telefono" name="telefono" class="form-control" value="<?php echo $data['empresa']['telefono']; ?>" placeholder="Teléfono">
                    </div>
                    <span id="errorTelefono" class="text-danger"></span>
                </div>
                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>Correo <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" id="correo" name="correo" class="form-control" value="<?php echo $data['empresa']['correo']; ?>" placeholder="Correo Electrónico">
                    </div>
                    <span id="errorCorreo" class="text-danger"></span>
                </div>
                <div class="col-lg-8 col-sm-6 mb-2">
                    <label>Dirección <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                        <input type="text" id="direccion" name="direccion" class="form-control" value="<?php echo $data['empresa']['direccion']; ?>" placeholder="Dirección">
                    </div>
                    <span id="errorDireccion" class="text-danger"></span>
                </div>
                <div class="col-lg-3 col-sm-6 mb-2">
                    <label>Impuesto (Opcional)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-percent"></i></span>
                        <input type="number" id="impuesto" name="impuesto" class="form-control" value="<?php echo $data['empresa']['impuesto']; ?>" placeholder="Impuesto">
                    </div>
                </div>

                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>numero de rango factura <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                        <input type="text" id="rango" name="rango" class="form-control" value="<?php echo $data['empresa']['rango']; ?>" placeholder="Rango">
                    </div>
                    <span id="errorRango" class="text-danger"></span>
                </div>

                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>numero de rango Credito Fiscal <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                        <input type="text" id="rangoc" name="rangoc" class="form-control" value="<?php echo $data['empresa']['rangoc']; ?>" placeholder="Rangoc">
                    </div>
                    <span id="errorRangoc" class="text-danger"></span>
                </div>

                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>Numero de Ticket (Opcional)<span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                        <input type="text" id="ticket" name="ticket" class="form-control" value="<?php echo $data['empresa']['ticket']; ?>" placeholder="Ticket">
                    </div>
                    <span id="errorTicket" class="text-danger"></span>
                </div>


                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>Notas de credito (Opcional)<span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                        <input type="text" id="notasc" name="notasc" class="form-control" value="<?php echo $data['empresa']['notasc']; ?>" placeholder="Notasc">
                    </div>
                    <span id="errorNotasc" class="text-danger"></span>
                </div>


                
                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>Numero de Recibo (Opcional)<span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                        <input type="text" id="recibo" name="recibo" class="form-control" value="<?php echo $data['empresa']['recibo']; ?>" placeholder="Recibo">
                    </div>
                    <span id="errorRecibo" class="text-danger"></span>
                </div>


                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>Numero de Dui <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                        <input type="text" id="dui" name="dui" class="form-control" value="<?php echo $data['empresa']['dui']; ?>" placeholder="Dui">
                    </div>
                    <span id="errorDui" class="text-danger"></span>
                </div>


                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>Registro <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                        <input type="text" id="registro" name="registro" class="form-control" value="<?php echo $data['empresa']['registro']; ?>" placeholder="Registro">
                    </div>
                    <span id="errorRegistro" class="text-danger"></span>
                </div>


                <div class="col-lg-4 col-sm-6 mb-2">
                    <label>Giro o actividad econimica <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-list"></i></span>
                        <input type="text" id="giro" name="giro" class="form-control" value="<?php echo $data['empresa']['giro']; ?>" placeholder="Giro">
                    </div>
                    <span id="errorGiro" class="text-danger"></span>
                </div>





                <div class="col-lg-9 col-sm-6 mb-2">
                    <div class="form-group">
                        <label for="mensaje">Mensaje (Opcional)</label>
                        <textarea id="mensaje" class="form-control" name="mensaje" rows="3" placeholder="Mensaje de Agradecimiento"><?php echo $data['empresa']['mensaje']; ?></textarea>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label for="foto">Logo (PNG)</label>
                        <input id="foto" class="form-control" type="file" name="foto">
                    </div>
                    <div id="containerPreview">
                        <img class="img-thumbnail" src="<?php echo BASE_URL . 'assets/images/logo.png'; ?>" alt="LOGO_PNG" width="400">
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button class="btn btn-primary" type="submit" id="btnAccion">Actualizar</button>
            </div>
        </form>
    </div>
</div>


<?php include_once 'views/templates/footer.php'; ?>