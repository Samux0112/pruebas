<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div></div>
            <div class="dropdown ms-auto">
                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                </a>
            </div>
        </div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-usuarios-tab" data-bs-toggle="tab" data-bs-target="#nav-usuarios" type="button" role="tab" aria-controls="nav-usuarios" aria-selected="true">Corte Z</button>

            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active mt-2" id="nav-usuarios" role="tabpanel" aria-labelledby="nav-usuarios-tab" tabindex="0">
                <h5 class="card-title text-center"><i class="fas fa-user"></i>Corte Z</h5>
                <hr>
               <form class="p-4" id="formulario" autocomplete="off">
                    <input type="hidden" id="id" name="id">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 mb-2">
                            <label>Fecha Inicio</label>
                            <div class="input-group">
                                <span class="input-group-text"></span> 
                                <input type="date" id="fInicio" name="fInicio" class="form-control" placeholder="Fecha Inicio">
                            </div>
                            <span id="errordescripcion" class="text-danger"></span>
                        </div>
						<div class="col-lg-4 col-sm-6 mb-2">
                            <label>Fecha Fin</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="date" id="fFin" name="fFin" class="form-control" placeholder="Fecha Fin">
                            </div>
                            <span id="errordescripcion" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="text-end">
                        <button class="btn btn-primary" onclick="verReporte()"  id="btnAccion">Generar</button>  
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/templates/footer.php'; ?>