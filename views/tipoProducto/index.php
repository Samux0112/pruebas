<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div></div>
            <div class="dropdown ms-auto">
                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?php echo BASE_URL . 'usuarios/inactivos'; ?>"><i class="fas fa-trash text-danger"></i> Inactivos</a>
                    </li>
                </ul>
            </div>
        </div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-usuarios-tab" data-bs-toggle="tab" data-bs-target="#nav-usuarios" type="button" role="tab" aria-controls="nav-usuarios" aria-selected="true">Tipos de producto</button>
                <button class="nav-link" id="nav-nuevo-tab" data-bs-toggle="tab" data-bs-target="#nav-nuevo" type="button" role="tab" aria-controls="nav-nuevo" aria-selected="false">Nuevo</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active mt-2" id="nav-usuarios" role="tabpanel" aria-labelledby="nav-usuarios-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fas fa-user"></i> Listado de tipos de producto</h5>
                <hr>
                <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover nowrap" id="tblTipoProducto" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Código tipo de producto MH</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-nuevo" role="tabpanel" aria-labelledby="nav-nuevo-tab" tabindex="0">
                <form class="p-4" id="formulario" autocomplete="off">
                    <input type="hidden" id="id" name="id">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Descripción</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-list"></i></span>
                                <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Descripción">
                            </div>
                            <span id="errordescripcion" class="text-danger"></span>
                        </div>
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Código de tipo de producto</label>
                            <div class="input-group" hidden>
                                <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                <input type="text" id="codTipoProducto" name="codTipoProducto" class="form-control" placeholder="Código de tipo de producto">
                            </div>
                            <span id="errorcodTipoProducto" class="text-danger"></span>
                        </div>
                    </div>
                    <button class="btn btn-danger" type="button" id="btnNuevo">Nuevo</button>
                    <button class="btn btn-primary" type="submit" id="btnAccion">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include_once 'views/templates/footer.php'; ?>
