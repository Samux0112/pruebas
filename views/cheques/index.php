<?php include_once 'views/templates/header.php'; ?>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0"><i class="fa-solid fa-money-check"></i> Emisión de Cheques</h5>
        </div>
        <hr>
        
        <form id="formCheque">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_proveedor">Proveedor <span class="text-danger">*</span></label>
                    <select id="id_proveedor" name="id_proveedor" class="form-control select2" required>
                        <option value="">Seleccionar proveedor...</option>
                        <?php foreach($data['proveedores'] as $prov): ?>
                        <option value="<?php echo $prov['id']; ?>" data-ruc="<?php echo $prov['ruc']; ?>" data-telefono="<?php echo $prov['telefono']; ?>" data-correo="<?php echo $prov['correo']; ?>">
                            <?php echo $prov['ruc'] . ' - ' . $prov['nombre']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="proveedor_info" class="text-muted small mt-1"></div>
                    <div id="cuenta_haber_info" class="text-muted small mt-1"></div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="id_banco">Banco</label>
                    <select id="id_banco" name="id_banco" class="form-control" disabled>
                        <option value="">Seleccionar...</option>
                        <?php foreach($data['bancos'] as $banco): ?>
                        <option value="<?php echo $banco['id']; ?>"><?php echo $banco['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="numero_cheque">No. Cheque</label>
                    <div class="input-group">
                        <input type="text" id="numero_cheque" name="numero_cheque" class="form-control" readonly>
                        <button type="button" class="btn btn-info" onclick="generarCorrelativo()">
                            <i class="fa-solid fa-sync"></i>
                        </button>
                    </div>
                </div>
            </div>
                
            <input type="hidden" id="cuenta_haber" name="cuenta_haber">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="concepto">Concepto <span class="text-danger">*</span></label>
                    <textarea id="concepto" name="concepto" class="form-control" rows="2" placeholder="Detalle del pago..." required></textarea>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="monto">Monto US$ <span class="text-danger">*</span></label>
                    <input type="number" id="monto" name="monto" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="fecha_emision">Fecha Emisión</label>
                    <input type="date" id="fecha_emision" name="fecha_emision" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
            
            <h5 class="mt-4">Partida Contable</h5>
            <div class="table-responsive">
                <table class="table table-bordered" id="tablaPartida">
                    <thead>
                        <tr>
                            <th style="width: 20%;">Cuenta</th>
                            <th style="width: 10%;">Tipo</th>
                            <th style="width: 15%;">Monto</th>
                            <th>Concepto</th>
                            <th style="width: 5%;"><button type="button" class="btn btn-success btn-sm" onclick="agregarFilaDebe()"><i class="fa-solid fa-plus"></i> Agregar DEBE</button></th>
                        </tr>
                    </thead>
                    <tbody id="tbodyPartida">
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary">
                            <td colspan="2" class="text-end"><strong>TOTALES:</strong></td>
                            <td>
                                <div class="text-success"><strong>Debe: US$ <span id="totalDebe">0.00</span></strong></div>
                                <div class="text-primary"><strong>Haber: US$ <span id="totalHaber">0.00</span></strong></div>
                            </td>
                            <td colspan="2">
                                <div id="diferenciaContainer" class="alert alert-success py-1 px-2 mb-0">
                                    <strong>Diferencia: US$ <span id="diferencia">0.00</span></strong>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary" id="btnEmitir" disabled>Emitir Cheque</button>
            </div>
        </form>
        
        <hr>
        
        <h5 class="mt-4"><i class="fa-solid fa-list"></i> Cheques Emitidos</h5>
        
        <form id="formFiltroCheques" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label for="filtro_banco">Banco</label>
                    <select id="filtro_banco" class="form-control select2">
                        <option value="">Todos</option>
                        <?php foreach($data['bancos'] as $banco): ?>
                        <option value="<?php echo $banco['id']; ?>"><?php echo $banco['nombre']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro_desde">Desde</label>
                    <input type="date" id="filtro_desde" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="filtro_hasta">Hasta</label>
                    <input type="date" id="filtro_hasta" class="form-control">
                </div>
                <div class="col-md-3" style="display: flex; align-items: flex-end;">
                    <button type="button" class="btn btn-primary me-2" onclick="filtrarCheques()">
                        <i class="fa-solid fa-search"></i> Buscar
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">
                        <i class="fa-solid fa-broom"></i>
                    </button>
                </div>
            </div>
        </form>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover nowrap" id="tblCheques" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No. Cheque</th>
                        <th>Banco</th>
                        <th>Proveedor</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Creado por</th>
                        <th>Anulado por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAnular" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa-solid fa-ban"></i> Anular Cheque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de anular el cheque <strong id="chequeAnular"></strong>?</p>
                <div class="mb-3">
                    <label for="motivoAnulacion" class="form-label">Motivo de Anulación <span class="text-danger">*</span></label>
                    <textarea id="motivoAnulacion" class="form-control" rows="3" placeholder="Ingrese el motivo de la anulación..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarAnular">Anular Cheque</button>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/templates/footer.php'; ?>
