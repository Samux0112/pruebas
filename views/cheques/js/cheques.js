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
    // Inicializar Select2 para proveedores
    $('#id_proveedor').select2({
        placeholder: 'Buscar proveedor por nombre o RUC...',
        allowClear: true
    });
    
    // Inicializar Select2 para filtros
    $('#filtro_banco').select2({
        placeholder: 'Todos los bancos',
        allowClear: true
    });
    
    // Manejar selección de proveedor
    $('#id_proveedor').change(function(){
        let selected = $(this).find('option:selected');
        if ($(this).val()) {
            $('#proveedor_info').html('<i class="fa-solid fa-user"></i> ' + selected.text() + ' | <i class="fa-solid fa-phone"></i> ' + selected.data('telefono') + ' | <i class="fa-solid fa-envelope"></i> ' + selected.data('correo'));
            
            // Buscar cuenta bancaria del proveedor
            let proveedor_id = $(this).val();
            $.ajax({
                url: base_url + 'cheques/getCuentasBancarias?proveedor_id=' + proveedor_id,
                success: function(response) {
                    try {
                        let data = JSON.parse(response);
                        if (data && data.numero_cuenta) {
                            // Auto-seleccionar banco (habilitar temporalmente)
                            $('#id_banco').prop('disabled', false);
                            $('#id_banco').val(data.banco_id).trigger('change');
                            $('#id_banco').prop('disabled', true);
                            // Auto-llenar input HABER
                            $('#cuenta_haber').val(data.numero_cuenta);
                            $('#cuenta_haber_info').html('<small class="text-success"><i class="fa-solid fa-check"></i> ' + data.numero_cuenta + ' - ' + data.banco + ' (' + (data.tipo_cuenta == '1' ? 'Corriente' : 'Ahorro') + ')</small>');
                        } else {
                            // Limpiar campos si no tiene cuenta
                            $('#cuenta_haber').val('');
                            $('#cuenta_haber_info').html('<small class="text-danger"><i class="fa-solid fa-exclamation-triangle"></i> Este proveedor no tiene cuentas bancarias asociadas</small>');
                            Swal.fire('Atención', 'Este proveedor no tiene cuentas bancarias asociadas', 'warning');
                        }
                    } catch(e) {
                        console.error('Error parsing response:', e);
                    }
                }
            });
        } else {
            $('#proveedor_info').html('');
            $('#cuenta_haber').val('');
            $('#cuenta_haber_info').html('');
            $('#id_banco').val('');
        }
    });
    
    tblCheques = $('#tblCheques').DataTable({
        ajax: {
            url: base_url + 'cheques/listar',
            dataSrc: function(json) {
                console.log('Data received:', json);
                if (!Array.isArray(json)) {
                    return [];
                }
                return json;
            }
        },
        columns: [
            { data: 'numero_cheque' },
            { data: 'banco' },
            { 
                data: 'proveedor',
                render: function(data, type, row) {
                    return (data || '') + '<br><small class="text-muted">RUC: ' + (row.ruc || '') + '</small>';
                }
            },
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
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fa-solid fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                },
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row:first-child c', sheet).attr('s', '2');
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa-solid fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                }
            }
        ],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
        order: [[0, 'desc']]
    });
    
    $('#formCheque').submit(function(e){
        e.preventDefault();
        registrarCheque();
    });
    
    $('#btnConfirmarAnular').click(function(){
        anularCheque();
    });
});

function generarCorrelativo() {
    let idBanco = $('#id_banco').val();
    if (idBanco) {
        $.ajax({
            url: base_url + 'cheques/getCorrelativo/' + idBanco,
            success: function(response) {
                try {
                    let data = JSON.parse(response);
                    if (data && data.correlativo !== undefined) {
                        let numero = data.prefijo + data.correlativo.toString().padStart(data.longitud, '0');
                        $('#numero_cheque').val(numero);
                    }
                } catch(e) {
                    // Si falla, el correlativo se generará en el servidor al registrar
                }
            }
        });
    }
}

function agregarFilaDebe() {
    let html = `
        <tr class="fila-debe">
            <td>
                <select class="form-select form-select-sm cuenta-select" onchange="calcularTotales()">
                    <option value="">Seleccionar...</option>
                    ${cuentasContables.map(c => `<option value="${c.cuenta}">${c.cuenta} - ${c.nombre}</option>`).join('')}
                </select>
            </td>
            <td><span class="badge bg-primary">Debe</span></td>
            <td>
                <input type="number" class="form-control form-control-sm monto-input" step="0.01" min="0" placeholder="0.00" oninput="calcularTotales()">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm concepto-input" placeholder="Concepto...">
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
    let proveedor_id = $('#id_proveedor').val();
    
    // Verificar que haya datos mínimos
    if (monto <= 0 || !cuenta || !idBanco || !proveedor_id) {
        return;
    }
    
    // Verificar si ya existe fila HABER
    let filaHaber = $('#tbodyPartida .fila-haber');
    
    if (filaHaber.length > 0) {
        // Actualizar fila existente
        filaHaber.find('td:eq(0) input').val(cuenta);
        filaHaber.find('td:eq(2) input').val(monto);
        filaHaber.find('td:eq(3) input').val(concepto || 'Pago a proveedor');
    } else {
        // Crear nueva fila HABER (al inicio)
        let html = `
            <tr class="fila-haber bg-light">
                <td>
                    <input type="text" class="form-control form-control-sm" value="${cuenta}" readonly>
                </td>
                <td><span class="badge bg-info text-dark">Haber</span></td>
                <td>
                    <input type="number" class="form-control form-control-sm haber-monto" value="${monto}" readonly>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" value="${concepto || 'Pago a proveedor'}" readonly>
                </td>
                <td>
                    <span class="text-muted small">Auto</span>
                </td>
            </tr>
        `;
        $('#tbodyPartida').prepend(html);
    }
    
    calcularTotales();
}

// Detectar cambios en monto y concepto para actualizar HABER automáticamente
$('#monto').on('input', function(){
    actualizarFilaHaber();
});

$('#concepto').on('input', function(){
    actualizarFilaHaber();
});

function registrarCheque() {
    let detalle = [];
    
    $('#tbodyPartida tr').each(function(){
        let cuenta = $(this).find('.cuenta-value').val() || $(this).find('td:eq(0) input[type="text"]').val();
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
    datos.append('id_proveedor', $('#id_proveedor').val());
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
            $('#id_proveedor').val('').trigger('change');
            $('#proveedor_info').html('');
            $('#cuenta_haber').val('');
            $('#cuenta_haber_info').html('');
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
    
    // Destruir DataTable actual y recrear con filtros
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
            { data: 'banco' },
            { 
                data: null,
                render: function(data) {
                    return data.proveedor + '<br><small class="text-muted">RUC: ' + data.ruc + '</small>';
                }
            },
            { data: 'concepto' },
            { 
                data: 'monto',
                render: function(data) {
                    return 'US$ ' + parseFloat(data).toFixed(2);
                }
            },
            { 
                data: 'fecha_emision',
                render: function(data) {
                    let fecha = new Date(data);
                    let dia = String(fecha.getDate()).padStart(2, '0');
                    let mes = String(fecha.getMonth() + 1).padStart(2, '0');
                    let anio = fecha.getFullYear();
                    return dia + '/' + mes + '/' + anio;
                }
            },
            { 
                data: 'estado',
                render: function(data, row) {
                    let badge = 'bg-secondary';
                    let texto = data;
                    if (data == 'emitido') { badge = 'bg-success'; texto = 'Emitido'; }
                    else if (data == 'entregado') { badge = 'bg-warning text-dark'; texto = 'Entregado'; }
                    else if (data == 'cobrado') { badge = 'bg-info text-dark'; texto = 'Cobrado'; }
                    else if (data == 'anulado') { 
                        badge = 'bg-danger'; 
                        texto = 'Anulado';
                        if (row.anulado_por_nombre) {
                            texto += '<br><small class="text-danger">Por: ' + row.anulado_por_nombre + '</small>';
                        }
                    }
                    return '<span class="badge ' + badge + '">' + texto + '</span>';
                }
            },
            {
                data: null,
                render: function(data) {
                    let btnImprimir = '<a href="' + base_url + 'cheques/reporte/' + data.id + '" target="_blank" class="btn btn-primary btn-sm" title="Imprimir"><i class="fa-solid fa-print"></i></a> ';
                    let btnAnular = '';
                    if (data.estado != 'anulado') {
                        btnAnular = '<button class="btn btn-danger btn-sm" onclick="abrirModalAnular(' + data.id + ', \'' + data.numero_cheque + '\')" title="Anular"><i class="fa-solid fa-ban"></i></button>';
                    }
                    return btnImprimir + btnAnular;
                }
            }
        ],
        language: {
            url: base_url + 'assets/js/espanol.json'
        },
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fa-solid fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa-solid fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'landscape'
            }
        ],
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
            text: 'El motivo de anulación es requerido'
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
