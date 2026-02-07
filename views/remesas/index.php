<?php include_once 'views/templates/header.php'; ?>

<style>
.nav-tabs .nav-link.active { font-weight: bold; }
.saldo-actual { font-size: 1.1rem; font-weight: bold; }
.saldo-positivo { color: #198754; }
.saldo-negativo { color: #dc3545; }
.tipo-remesa { padding: 3px 8px; border-radius: 3px; font-size: 0.85em; }
.tipo-remesa.remesa { background: #17a2b8; color: white; }
.tipo-remesa.transferencia { background: #28a745; color: white; }
.tipo-remesa.cheque { background: #ffc107; color: black; }
</style>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0"><i class="fas fa-money-bill-transfer"></i> Remesas y Transferencias</h5>
            <button class="btn btn-primary btn-sm ms-auto" onclick="abrirModal()">
                <i class="fas fa-plus me-1"></i>Nueva Remesa
            </button>
        </div>
        <hr>
        
        <div class="table-responsive">
            <table id="remesasTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Banco / Cuenta</th>
                        <th>Tipo</th>
                        <th>Tipo Partida</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para nueva remesa -->
<div class="modal fade" id="modalRemesa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Nueva Remesa / Transferencia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRemesa">
                <div class="modal-body">
                    <div class="row mt-3">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Correlativo</label>
                            <input type="text" class="form-control bg-light" id="correlativo" readonly>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tipo Transacción *</label>
                            <select class="form-select" id="tipo_transaccion" required>
                                <option value="remesa">REMESA</option>
                                <option value="transferencia">TRANSFERENCIA</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Banco / Cuenta *</label>
                            <select class="form-select" id="id_banco" required>
                                <option value="">Seleccionar banco...</option>
                                <?php if(isset($data['bancos'])): ?>
                                <?php foreach($data['bancos'] as $banco): ?>
                                <option value="<?php echo $banco['id']; ?>">
                                    <?php echo $banco['nombre']; ?> - <?php echo $banco['cuenta_contable']; ?>
                                </option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo Partida Remesa *</label>
                            <input type="text" class="form-control" id="tipo_partida_remesa" placeholder="Ej: Partida de Remesa No. 001" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Concepto</label>
                            <input type="text" class="form-control" id="concepto" placeholder="Concepto de la transacción">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Monto Total</label>
                            <input type="number" step="0.01" class="form-control bg-light" id="monto" readonly>
                        </div>
                    </div>
                    
                    <!-- Detalle contable -->
                    <h5 class="mt-4">Partida Contable</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tablaDetalle">
                            <thead>
                                <tr>
                                    <th style="width: 20%">Cuenta</th>
                                    <th style="width: 10%">Tipo</th>
                                    <th style="width: 15%">Monto</th>
                                    <th>Concepto</th>
                                    <th style="width: 5%"><button type="button" class="btn btn-success btn-sm" onclick="agregarFilaDetalle()"><i class="fas fa-plus"></i></button></th>
                                </tr>
                            </thead>
                            <tbody id="tbodyDetalle">
                            </tbody>
                            <tfoot>
                                <tr class="table-secondary">
                                    <td colspan="2" class="text-end"><strong>TOTALES:</strong></td>
                                    <td>
                                        <div class="text-success"><strong>Debe: US$ <span id="totalDebe">0.00</span></strong></div>
                                        <div class="text-primary"><strong>Haber: US$ <span id="totalHaber">0.00</span></strong></div>
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver/anular -->
<div class="modal fade" id="modalVer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detalle de Remesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="verDetalle">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" target="_blank" class="btn btn-primary" id="btnPdf">
                    <i class="fas fa-file-pdf me-1"></i>PDF
                </a>
                <button type="button" class="btn btn-danger" id="btnAnular" onclick="confirmarAnulacion()">
                    <i class="fas fa-ban me-1"></i>Anular
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de anulación -->
<div class="modal fade" id="modalAnular" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Anular Remesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAnular">
                <div class="modal-body">
                    <input type="hidden" id="id_anular">
                    <div class="mb-3">
                        <label class="form-label">Motivo de Anulación *</label>
                        <textarea class="form-control" id="motivo_anulacion" rows="3" required placeholder="Ingrese el motivo de la anulación..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Anular</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>assets/DataTables/datatables.min.js"></script>
<script src="<?php echo BASE_URL; ?>views/remesas/js/remesas.js"></script>

<?php include_once 'views/templates/footer.php'; ?>
