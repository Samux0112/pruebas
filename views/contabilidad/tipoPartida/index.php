<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0"><i class="fa-solid fa-book"></i> Tipos de Partida</h5>
        </div>
        <hr>
        
        <div class="text-end mb-3">
            <button class="btn btn-primary" onclick="modalTipoPartida()"><i class="fa-solid fa-plus"></i> Nuevo Tipo</button>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover nowrap" id="tblTipoPartida" style="width: 100%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalTipoPartida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModal">Nuevo Tipo de Partida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTipoPartida" autocomplete="off">
                <input type="hidden" id="id_original" name="id_original">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id">ID <span class="text-danger">*</span></label>
                        <input type="text" id="id" name="id" class="form-control" placeholder="pdia, PEGR, Prem, Ping" maxlength="10">
                        <small class="text-muted">Código identificador (puede usar mayúsculas y minúsculas)</small>
                    </div>
                    <div class="mb-3">
                        <label for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre del tipo de partida">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnAccion">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once 'views/templates/footer.php'; ?>
<script src="<?php echo BASE_URL; ?>assets/js/tipoPartida.js"></script>
