<?php include_once 'views/templates/header.php'; ?>

<style>
.nav-tabs .nav-link.active { font-weight: bold; }
.saldo-actual { font-size: 1.1rem; font-weight: bold; }
.saldo-positivo { color: #198754; }
.saldo-negativo { color: #dc3545; }
</style>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0"><i class="fa-solid fa-money-check"></i> Emisión de Cheques</h5>
        </div>
        <hr>
        
        <form id="formCheque">
            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <label for="id_banco">Banco <span class="text-danger">*</span></label>
                    <select id="id_banco" name="id_banco" class="form-control select2" required>
                        <option value="">Seleccionar banco...</option>
                        <?php foreach($data['bancos'] as $banco): ?>
                        <option value="<?php echo $banco['id']; ?>"
                                data-correlativo="<?php echo $banco['correlativo_cheque'] ?? 1; ?>"
                                data-cuenta="<?php echo $banco['numero_cuenta']; ?>"
                                data-contable="<?php echo $banco['cuenta_contable']; ?>"
                                data-saldo="<?php echo $banco['saldo_actual'] ?? 0; ?>">
                            <?php echo $banco['nombre']; ?> - <?php echo $banco['numero_cuenta']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <div id="saldoDisplay" class="mt-1"></div>
                </div>
                
                <div class="col-md-2 mb-3">
                    <label for="numero_cheque">No. Cheque</label>
                    <input type="text" id="numero_cheque" name="numero_cheque" class="form-control bg-light" readonly>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="proveedor">Proveedor</label>
                    <input type="text" id="proveedor" name="proveedor" class="form-control" placeholder="Escriba el nombre del proveedor..." list="lista_proveedores">
                    <datalist id="lista_proveedores">
                        <?php if(!empty($data['proveedores'])): ?>
                        <?php foreach($data['proveedores'] as $p): ?>
                        <option value="<?php echo $p['nombre']; ?>">
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </datalist>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="concepto">Concepto <span class="text-danger">*</span></label>
                    <input type="text" id="concepto" name="concepto" class="form-control" placeholder="Detalle del pago..." required>
                </div>
            </div>
            
            <div class="row">
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
                            <th style="width: 5%;"><button type="button" class="btn btn-success btn-sm" onclick="agregarFilaDebe()"><i class="fa-solid fa-plus"></i> DEBE</button></th>
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
                        <th>Proveedor</th>
                        <th>Banco</th>
                        <th>No. Cuenta</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Usuario</th>
                        <th>Anulado Por</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Anular Cheque -->
<div class="modal fade" id="modalAnular" tabindex="-1" role="dialog" aria-labelledby="modalAnularLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalAnularLabel"><i class="fa-solid fa-ban"></i> Anular Cheque</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea anular el cheque <strong id="chequeAnular"></strong>?</p>
                <div class="form-group">
                    <label for="motivoAnulacion">Motivo de Anulación <span class="text-danger">*</span></label>
                    <textarea id="motivoAnulacion" class="form-control" rows="3" placeholder="Ingrese el motivo de la anulación..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="btnConfirmarAnular">Anular Cheque</button>
            </div>
        </div>
    </div>
</div>

<script>
// Datos de cuentas contables predefinidas
const cuentasContables = [
    { cuenta: '1101-001', nombre: 'CAJA GENERAL' },
    { cuenta: '1102-001', nombre: 'BANCOS MONEDA NACIONAL' },
    { cuenta: '2101-001', nombre: 'PROVEEDORES LOCALES' },
    { cuenta: '2102-001', nombre: 'ACREEDORES VARIOS' },
    { cuenta: '5101-001', nombre: 'COMPRAS' },
    { cuenta: '4101-001', nombre: 'VENTAS' },
    { cuenta: '6101-001', nombre: 'COSTO DE VENTAS' }
];

document.addEventListener('DOMContentLoaded', function(){
    // Inicializar Select2
    $('#id_banco').select2({ placeholder: 'Seleccionar banco...', allowClear: true });
    $('#filtro_banco').select2({ placeholder: 'Todos los bancos', allowClear: true });
    
    // Evento change del banco
    $('#id_banco').change(function(){
        let selected = $(this).find('option:selected');
        if ($(this).val()) {
            let banco_id = $(this).val();
            let correlativo = selected.data('correlativo') || 1;
            let cuenta_contable = selected.data('contable');
            let saldo = parseFloat(selected.data('saldo')) || 0;
            
            $('#id_banco').val(banco_id);
            
            // Generar número de cheque
            $('#numero_cheque').val(correlativo.toString().padStart(6, '0'));
            
            // Mostrar saldo
            let saldoClass = saldo > 0 ? 'saldo-positivo' : (saldo < 0 ? 'saldo-negativo' : '');
            let saldoText = saldo >= 0 ? '$' + saldo.toFixed(2) : '-$' + Math.abs(saldo).toFixed(2);
            $('#saldoDisplay').html('<span class="saldo-actual ' + saldoClass + '">Saldo: US$ ' + saldoText + '</span>');
            
            // Actualizar fila haber
            actualizarFilaHaber();
        } else {
            $('#cuenta_haber').val('');
            $('#numero_cheque').val('');
            $('#saldoDisplay').html('');
        }
    });
    
    // Calcular totales cuando cambian monto o concepto
    $('#monto, #concepto').on('input', function(){
        actualizarFilaHaber();
    });
    
    // Inicializar DataTable
    inicializarDataTable();
    
    // Submit del formulario cheque
    $('#formCheque').submit(function(e){
        e.preventDefault();
        registrarCheque();
    });
    
    // Confirmar anular
    $('#btnConfirmarAnular').click(function(){
        anularCheque();
    });
});

function inicializarDataTable() {
    tblCheques = $('#tblCheques').DataTable({
        ajax: {
            url: base_url + 'cheques/listar',
            dataSrc: function(json) {
                if (!Array.isArray(json)) { return []; }
                return json;
            }
        },
        columns: [
            { data: 'numero_cheque' },
            { data: 'proveedor' },
            { data: 'banco' },
            { data: 'numero_cuenta_bancaria' },
            { data: 'concepto' },
            { data: 'monto', render: function(data) { return 'US$ ' + parseFloat(data || 0).toFixed(2); } },
            { data: 'fecha_emision' },
            { data: 'estado' },
            { data: 'usuario' },
            { data: 'anulado_por_nombre' },
            {
                data: 'id',
                render: function(data, type, row) {
                    let btnImprimir = '<a href="' + base_url + 'cheques/reporte/' + data + '" target="_blank" class="btn btn-primary btn-sm"><i class="fa-solid fa-print"></i></a> ';
                    let btnAnular = '';
                    if (row.estado != 'anulado') {
                        btnAnular = '<button class="btn btn-danger btn-sm" onclick="abrirModalAnular(' + data + ', \'' + (row.numero_cheque || '') + '\')"><i class="fa-solid fa-ban"></i></button>';
                    }
                    return btnImprimir + btnAnular;
                }
            }
        ],
        language: { url: base_url + 'assets/js/espanol.json' },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        order: [[0, 'desc']]
    });
}

function agregarFilaDebe() {
    let opcionesCuentas = cuentasContables.map(c => `<option value="${c.cuenta}">${c.cuenta} - ${c.nombre}</option>`).join('');
    
    let html = `
        <tr class="fila-debe">
            <td>
                <select class="form-select form-select-sm" onchange="calcularTotales()">
                    <option value="">Seleccionar...</option>
                    ${opcionesCuentas}
                </select>
            </td>
            <td><span class="badge bg-primary">Debe</span></td>
            <td><input type="number" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00" oninput="calcularTotales()"></td>
            <td><input type="text" class="form-control form-control-sm" placeholder="Concepto..."></td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)"><i class="fa-solid fa-times"></i></button></td>
        </tr>
    `;
    $('#tbodyPartida').append(html);
    calcularTotales();
}

function eliminarFila(btn) {
    $(btn).closest('tr').remove();
    calcularTotales();
}

function calcularTotales() {
    let totalDebe = 0;
    let totalHaber = 0;
    
    $('#tbodyPartida tr').each(function(){
        let monto = parseFloat($(this).find('td:eq(2) input').val()) || 0;
        if ($(this).hasClass('fila-debe')) {
            totalDebe += monto;
        } else if ($(this).hasClass('fila-haber')) {
            totalHaber += monto;
        }
    });
    
    $('#totalDebe').text(totalDebe.toFixed(2));
    $('#totalHaber').text(totalHaber.toFixed(2));
    
    let diferencia = totalDebe - totalHaber;
    $('#diferencia').text(Math.abs(diferencia).toFixed(2));
    
    if (Math.abs(diferencia) < 0.01 && totalHaber > 0) {
        $('#diferenciaContainer').removeClass('alert-danger').addClass('alert-success');
        $('#btnEmitir').prop('disabled', false);
    } else {
        $('#diferenciaContainer').removeClass('alert-success').addClass('alert-danger');
        $('#btnEmitir').prop('disabled', true);
    }
}

function actualizarFilaHaber() {
    let monto = parseFloat($('#monto').val()) || 0;
    let concepto = $('#concepto').val().trim();
    let idBanco = $('#id_banco').val();
    let selected = $('#id_banco').find('option:selected');
    let cuenta_contable = selected.data('contable');
    let numero_cuenta = selected.data('cuenta');
    
    if (monto <= 0 || !idBanco || !cuenta_contable) {
        return;
    }
    
    $('#tbodyPartida .fila-haber').remove();
    
    let html = `
        <tr class="fila-haber bg-light">
            <td>
                <input type="text" class="form-control form-control-sm" value="${cuenta_contable}" readonly>
            </td>
            <td><span class="badge bg-info text-dark">Haber</span></td>
            <td><input type="number" class="form-control form-control-sm" value="${monto}" readonly></td>
            <td><input type="text" class="form-control form-control-sm" value="${concepto || 'Pago'}" readonly></td>
            <td><span class="text-muted small">Auto</span></td>
        </tr>
    `;
    $('#tbodyPartida').prepend(html);
    calcularTotales();
}

function registrarCheque() {
    let detalle = [];
    
    $('#tbodyPartida tr').each(function(){
        let cuenta = $(this).find('td:eq(0) input').val() || $(this).find('td:eq(0) select').val();
        let tipo = $(this).hasClass('fila-debe') ? 'Debe' : 'Haber';
        let monto = parseFloat($(this).find('td:eq(2) input').val()) || 0;
        let concepto = $(this).find('td:eq(3) input').val();
        
        if (cuenta && monto > 0) {
            detalle.push({ cuenta, tipo, monto, concepto });
        }
    });
    
    let datos = new FormData();
    datos.append('numero_cheque', $('#numero_cheque').val());
    datos.append('id_banco', $('#id_banco').val());
    datos.append('proveedor', $('#proveedor').val());
    datos.append('concepto', $('#concepto').val());
    datos.append('monto', $('#monto').val());
    datos.append('fecha_emision', $('#fecha_emision').val());
    datos.append('detalle', JSON.stringify(detalle));
    
    fetch(base_url + 'cheques/registrar', {
        method: 'POST',
        body: datos
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({ icon: data.type, title: 'Mensaje', text: data.msg, timer: 3000, showConfirmButton: false });
        if (data.type == 'success') {
            $('#formCheque')[0].reset();
            $('#id_banco').val('').trigger('change');
            $('#numero_cheque').val('');
            $('#proveedor').val('');
            $('#saldoDisplay').html('');
            $('#tbodyPartida').html('');
            $('#totalDebe').text('0.00');
            $('#totalHaber').text('0.00');
            $('#diferencia').text('0.00');
            $('#diferenciaContainer').removeClass('alert-success').addClass('alert-danger');
            $('#btnEmitir').prop('disabled', true);
            tblCheques.ajax.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function filtrarCheques() {
    let banco = $('#filtro_banco').val();
    let desde = $('#filtro_desde').val();
    let hasta = $('#filtro_hasta').val();
    
    tblCheques.destroy();
    tblCheques = $('#tblCheques').DataTable({
        ajax: {
            url: base_url + 'cheques/listar',
            dataSrc: function(json) {
                let filtrado = json.filter(item => {
                    let pasaBanco = !banco || item.id_banco == banco;
                    let fecha = new Date(item.fecha_emision);
                    let pasaDesde = !desde || fecha >= new Date(desde);
                    let pasaHasta = !hasta || fecha <= new Date(hasta);
                    return pasaBanco && pasaDesde && pasaHasta;
                });
                return filtrado;
            }
        },
        columns: [
            { data: 'numero_cheque' },
            { data: 'proveedor' },
            { data: 'banco' },
            { data: 'numero_cuenta_bancaria' },
            { data: 'concepto' },
            { data: 'monto', render: function(data) { return 'US$ ' + parseFloat(data).toFixed(2); } },
            { data: 'fecha_emision' },
            { data: 'estado' },
            { data: 'usuario' },
            { data: 'anulado_por_nombre' },
            {
                data: 'id',
                render: function(data, type, row) {
                    let btnImprimir = '<a href="' + base_url + 'cheques/reporte/' + data + '" target="_blank" class="btn btn-primary btn-sm"><i class="fa-solid fa-print"></i></a> ';
                    let btnAnular = '';
                    if (row.estado != 'anulado') {
                        btnAnular = '<button class="btn btn-danger btn-sm" onclick="abrirModalAnular(' + data + ', \'' + row.numero_cheque + '\')"><i class="fa-solid fa-ban"></i></button>';
                    }
                    return btnImprimir + btnAnular;
                }
            }
        ],
        language: { url: base_url + 'assets/js/espanol.json' },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        order: [[0, 'desc']]
    });
    tblCheques.ajax.reload();
}

function limpiarFiltros() {
    $('#filtro_banco').val('');
    $('#filtro_desde').val('');
    $('#filtro_hasta').val('');
    filtrarCheques();
}

function anularCheque() {
    let motivo = $('#motivoAnulacion').val().trim();
    
    if (!motivo) {
        Swal.fire({ icon: 'warning', title: 'Advertencia', text: 'El motivo de anulacion es requerido' });
        return;
    }
    
    let datos = new FormData();
    datos.append('id', idChequeAnular);
    datos.append('motivo', motivo);
    
    fetch(base_url + 'cheques/anular', {
        method: 'POST',
        body: datos
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({ icon: data.type, title: 'Mensaje', text: data.msg, timer: 3000, showConfirmButton: false });
        if (data.type == 'success') {
            $('#modalAnular').modal('hide');
            tblCheques.ajax.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function abrirModalAnular(id, numeroCheque) {
    idChequeAnular = id;
    $('#chequeAnular').text(numeroCheque);
    $('#motivoAnulacion').val('');
    $('#modalAnular').modal('show');
}
</script>

<?php include_once 'views/templates/footer.php'; ?>
