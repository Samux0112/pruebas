let tblCheques;
let idChequeAnular = 0;
const cuentasContables = [
    { cuenta: '2101-001', nombre: 'PROVEEDORES LOCALES' },
    { cuenta: '1101-001', nombre: 'CAJA GENERAL' },
    { cuenta: '1102-001', nombre: 'BANCOS MONEDA NACIONAL' },
    { cuenta: '2102-001', nombre: 'ACREEDORES VARIOS' },
    { cuenta: '5101-001', nombre: 'COMPRAS' },
    { cuenta: '4101-001', nombre: 'VENTAS' },
    { cuenta: '6101-001', nombre: 'COSTO DE VENTAS' }
];

document.addEventListener('DOMContentLoaded', function(){
    $('#id_banco').select2({
        placeholder: 'Seleccionar banco...',
        allowClear: true
    });
    
    $('#filtro_banco').select2({
        placeholder: 'Todos los bancos',
        allowClear: true
    });
    
    $('#id_banco').change(function(){
        let selected = $(this).find('option:selected');
        if ($(this).val()) {
            let banco_id = $(this).val();
            let cuenta = selected.data('cuenta');
            let cuenta_contable = selected.data('contable');
            let correlativo = selected.data('correlativo') || 1;
            
            $('#id_banco').val(banco_id);
            $('#cuenta_haber').val(cuenta_contable);
            
            // Generar número de cheque automáticamente con el correlativo del banco
            $('#numero_cheque').val(correlativo.toString().padStart(6, '0'));
            
            actualizarFilaHaber();
        } else {
            $('#cuenta_haber').val('');
            $('#numero_cheque').val('');
        }
    });
    
    $('#monto, #concepto').on('input', function(){
        actualizarFilaHaber();
    });
    
    inicializarDataTable();
    
    $('#formCheque').submit(function(e){
        e.preventDefault();
        registrarCheque();
    });
    
    $('#btnConfirmarAnular').click(function(){
        anularCheque();
    });
});

function inicializarDataTable() {
    tblCheques = $('#tblCheques').DataTable({
        ajax: {
            url: base_url + 'cheques/listar',
            dataSrc: function(json) {
                if (!Array.isArray(json)) {
                    return [];
                }
                return json;
            }
        },
        columns: [
            { data: 'numero_cheque' },
            { data: 'proveedor' },
            { data: 'banco' },
            { data: 'numero_cuenta_bancaria' },
            { data: 'concepto' },
            { 
                data: 'monto',
                render: function(data) {
                    return 'US$ ' + parseFloat(data || 0).toFixed(2);
                }
            },
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
        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        order: [[0, 'desc']]
    });
}

function agregarFilaDebe() {
    let html = `
        <tr class="fila-debe">
            <td>
                <select class="form-select form-select-sm" onchange="calcularTotales()">
                    <option value="">Seleccionar...</option>
                    ${cuentasContables.map(c => `<option value="${c.cuenta}">${c.cuenta} - ${c.nombre}</option>`).join('')}
                </select>
            </td>
            <td><span class="badge bg-primary">Debe</span></td>
            <td>
                <input type="number" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00" oninput="calcularTotales()">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm" placeholder="Concepto...">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)"><i class="fa-solid fa-times"></i></button>
            </td>
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
    let cuenta = $('#cuenta_haber').val();
    let idBanco = $('#id_banco').val();
    
    if (monto <= 0 || !cuenta || !idBanco) {
        return;
    }
    
    $('#tbodyPartida .fila-haber').remove();
    
    let html = `
        <tr class="fila-haber bg-light">
            <td>
                <input type="text" class="form-control form-control-sm" value="${cuenta}" readonly>
            </td>
            <td><span class="badge bg-info text-dark">Haber</span></td>
            <td>
                <input type="number" class="form-control form-control-sm" value="${monto}" readonly>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm" value="${concepto || 'Pago'}" readonly>
            </td>
            <td>
                <span class="text-muted small">Auto</span>
            </td>
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
        Swal.fire({
            icon: data.type,
            title: 'Mensaje',
            text: data.msg,
            timer: 3000,
            showConfirmButton: false
        });
        if (data.type == 'success') {
            $('#formCheque')[0].reset();
            $('#id_banco').val('').trigger('change');
            $('#cuenta_haber').val('');
            $('#proveedor').val('');
            $('#numero_cheque').val('');
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
        Swal.fire({
            icon: 'warning',
            title: 'Advertencia',
            text: 'El motivo de anulacion es requerido'
        });
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
        Swal.fire({
            icon: data.type,
            title: 'Mensaje',
            text: data.msg,
            timer: 3000,
            showConfirmButton: false
        });
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
