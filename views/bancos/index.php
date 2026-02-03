<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0"><i class="fa-solid fa-university"></i> Bancos</h5>
            <div class="dropdown ms-auto">
                <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                </a>
            </div>
        </div>
        <hr>
        
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-transaccion-tab" data-bs-toggle="tab" data-bs-target="#nav-transaccion" type="button" role="tab" aria-controls="nav-transaccion" aria-selected="true">Tipos de Transacción</button>
                <button class="nav-link" id="nav-bancos-tab" data-bs-toggle="tab" data-bs-target="#nav-bancos" type="button" role="tab" aria-controls="nav-bancos" aria-selected="false">Bancos</button>
                <button class="nav-link" id="nav-cuentas-tab" data-bs-toggle="tab" data-bs-target="#nav-cuentas" type="button" role="tab" aria-controls="nav-cuentas" aria-selected="false">Cuentas Bancarias</button>
            </div>
        </nav>
        
        <div class="tab-content" id="nav-tabContent">
            <!-- Tab 1: Tipos de Transacción -->
            <div class="tab-pane fade show active" id="nav-transaccion" role="tabpanel" aria-labelledby="nav-transaccion-tab" tabindex="0">
                <div class="text-end mb-3">
                    <button class="btn btn-primary" onclick="modalTransaccion()"><i class="fa-solid fa-plus"></i> Nuevo Tipo</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover nowrap" id="tblTransaccion" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Tipo Partida</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Tab 2: Bancos -->
            <div class="tab-pane fade" id="nav-bancos" role="tabpanel" aria-labelledby="nav-bancos-tab" tabindex="0">
                <div class="text-end mb-3">
                    <button class="btn btn-primary" onclick="modalBanco()"><i class="fa-solid fa-plus"></i> Nuevo Banco</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover nowrap" id="tblBancos" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Número Cuenta</th>
                                <th>Cuenta Contable</th>
                                <th>POS</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Tab 3: Cuentas Bancarias -->
            <div class="tab-pane fade" id="nav-cuentas" role="tabpanel" aria-labelledby="nav-cuentas-tab" tabindex="0">
                <div class="text-end mb-3">
                    <button class="btn btn-primary" onclick="modalCuentaBancaria()"><i class="fa-solid fa-plus"></i> Nueva Cuenta</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover nowrap" id="tblCuentasBancarias" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Banco</th>
                                <th>Número</th>
                                <th>Propietario</th>
                                <th>Cuenta Contable</th>
                                <th>Tipo</th>
                                <th>Acciones</th>
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

<!-- Modal para Tipos de Transacción -->
<div class="modal fade" id="modalTransaccion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalTransaccion">Nuevo Tipo de Transacción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formTransaccion" autocomplete="off">
                <input type="hidden" id="id_transaccion" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_transaccion">Nombre</label>
                        <input type="text" id="nombre_transaccion" name="nombre" class="form-control" placeholder="Nombre del tipo">
                    </div>
                    <div class="mb-3">
                        <label for="tipo_partida">Tipo Partida Contable</label>
                        <select id="tipo_partida" name="tipo_partida" class="form-control select2">
                            <option value="">Seleccionar tipo partida...</option>
                            <?php if(!empty($data['partidas'])): ?>
                            <?php foreach($data['partidas'] as $p): ?>
                            <option value="<?php echo $p['id']; ?>">
                                <?php echo $p['id'] . ' - ' . $p['nombre']; ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_transaccion">Tipo Transacción</label>
                        <select id="tipo_transaccion" name="tipo_transaccion" class="form-control">
                            <option value="Debe">Debe</option>
                            <option value="Haber">Haber</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnAccionTransaccion">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Bancos -->
<div class="modal fade" id="modalBanco" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalBanco">Nuevo Banco</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formBanco" autocomplete="off">
                <input type="hidden" id="id_banco" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_banco">Nombre del Banco</label>
                        <input type="text" id="nombre_banco" name="nombre" class="form-control" placeholder="Nombre del banco">
                    </div>
                    <div class="mb-3">
                        <label for="numero_cuenta_banco">Número de Cuenta</label>
                        <input type="text" id="numero_cuenta_banco" name="numero_cuenta" class="form-control" placeholder="Número de cuenta">
                    </div>
                    <div class="mb-3">
                        <label for="cuenta_contable_banco">Cuenta Contable</label>
                        <select id="cuenta_contable_banco" name="cuenta_contable" class="form-control select2">
                            <option value="">Seleccionar cuenta...</option>
                            <?php if(!empty($data['cuentas'])): ?>
                            <?php foreach($data['cuentas'] as $cta): ?>
                            <option value="<?php echo $cta['codigo']; ?>">
                                <?php echo $cta['codigo'] . ' - ' . $cta['nombre_cuenta']; ?>
                            </option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cuenta_pos_banco">Tiene POS</label>
                        <select id="cuenta_pos_banco" name="cuenta_pos" class="form-control">
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnAccionBanco">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Cuentas Bancarias -->
<div class="modal fade" id="modalCuentaBancaria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalCuenta">Nueva Cuenta Bancaria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCuentaBancaria" autocomplete="off">
                <input type="hidden" id="id_cuenta_bancaria" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="banco_id">Banco</label>
                            <select id="banco_id" name="banco_id" class="form-control select2" required>
                                <option value="">Seleccionar banco...</option>
                                <?php if(!empty($data['bancos'])): ?>
                                <?php foreach($data['bancos'] as $banco): ?>
                                <option value="<?php echo $banco['id']; ?>">
                                    <?php echo $banco['nombre']; ?>
                                </option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="numero_cuenta_cb">Número de Cuenta</label>
                            <input type="text" id="numero_cuenta_cb" name="numero_cuenta" class="form-control" placeholder="Número de cuenta" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cuenta_contable_cb">Cuenta Contable</label>
                            <select id="cuenta_contable_cb" name="cuenta_contable" class="form-control select2" required>
                                <option value="">Seleccionar cuenta...</option>
                                <?php if(!empty($data['cuentas'])): ?>
                                <?php foreach($data['cuentas'] as $cta): ?>
                                <option value="<?php echo $cta['codigo']; ?>">
                                    <?php echo $cta['codigo'] . ' - ' . $cta['nombre_cuenta']; ?>
                                </option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="propietario_cb">Propietario</label>
                            <select id="propietario_cb" name="propietario_id" class="form-control select2" required>
                                <option value="">Seleccionar proveedor...</option>
                                <?php if(!empty($data['proveedores'])): ?>
                                <?php foreach($data['proveedores'] as $prov): ?>
                                <option value="<?php echo $prov['id']; ?>">
                                    <?php echo $prov['ruc'] . ' - ' . $prov['nombre']; ?>
                                </option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipo_cuenta_cb">Tipo Cuenta</label>
                            <select id="tipo_cuenta_cb" name="tipo_cuenta" class="form-control" required>
                                <option value="1">Corriente</option>
                                <option value="2">Ahorro</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnAccionCuenta">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once 'views/templates/footer.php'; ?>
<script src="<?php echo BASE_URL; ?>views/bancos/js/bancos.js"></script>
<script>
$(document).ready(function() {
    // Select2 para modal Banco
    $('#modalBanco').on('shown.bs.modal', function() {
        $('#cuenta_contable_banco').select2({
            dropdownParent: $('#modalBanco'),
            placeholder: 'Buscar cuenta contable...',
            allowClear: true
        });
    });
    
    // Select2 para modal Transacción
    $('#modalTransaccion').on('shown.bs.modal', function() {
        $('#tipo_partida').select2({
            dropdownParent: $('#modalTransaccion'),
            placeholder: 'Buscar tipo partida...',
            allowClear: true
        });
    });
});
</script>
