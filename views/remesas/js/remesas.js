// Variables globales
let table;
let detalle = [];
let idVer = null;

$(document).ready(function() {
    // Inicializar DataTable
    table = $('#remesasTable').DataTable({
        ajax: {
            url: BASE_URL + 'remesas/listar',
            type: 'GET',
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { 
                data: 'fecha_creacion',
                render: function(data) {
                    return data ? moment(data).format('DD/MM/YYYY HH:mm') : '';
                }
            },
            { 
                data: null,
                render: function(data) {
                    return (data.banco_nombre || '') + ' - ' + (data.cuenta_contable || '');
                }
            },
            { 
                data: 'tipo_transaccion',
                render: function(data) {
                    let clase = data || 'remesa';
                    let texto = data ? data.toUpperCase() : 'REMESA';
                    return `<span class="tipo-remesa ${clase}">${texto}</span>`;
                }
            },
            { data: 'tipo_partida_remesa' },
            { data: 'concepto' },
            { 
                data: 'monto',
                render: function(data) {
                    return '$' + parseFloat(data || 0).toFixed(2);
                },
                className: 'text-end'
            },
            { 
                data: 'estado',
                render: function(data) {
                    if (data == 'anulado') {
                        return '<span class="badge bg-danger">ANULADO</span>';
                    }
                    return '<span class="badge bg-success">ACTIVO</span>';
                }
            },
            {
                data: null,
                render: function(data) {
                    let btnPdf = `<a href="${BASE_URL}remesas/pdf/${data.id}" target="_blank" class="btn btn-info btn-sm btn-remesa" title="PDF"><i class="fas fa-file-pdf"></i></a>`;
                    let btnVer = `<button class="btn btn-primary btn-sm btn-remesa" onclick="verRemesa(${data.id})" title="Ver"><i class="fas fa-eye"></i></button>`;
                    return btnVer + btnPdf;
                },
                orderable: false,
                className: 'text-center'
            }
        ],
        language: {
            url: BASE_URL + 'assets/js/espanol.json'
        },
        order: [[0, 'desc']]
    });

    // Cargar datos iniciales al abrir modal
    $('#modalRemesa').on('shown.bs.modal', function() {
        if (!$('#correlativo').val()) {
            $.get(BASE_URL + 'remesas/nuevo', function(response) {
                $('#correlativo').val(response.correlativo);
                // Cargar bancos
                let options = '<option value="">Seleccionar banco...</option>';
                response.bancos.forEach(banco => {
                    options += `<option value="${banco.id}">${banco.nombre} (${banco.cuenta_contable})</option>`;
                });
                $('#id_banco').html(options);
            });
        }
    });

    // Formulario de remesa
    $('#formRemesa').on('submit', function(e) {
        e.preventDefault();
        guardarRemesa();
    });

    // Formulario de anulación
    $('#formAnular').on('submit', function(e) {
        e.preventDefault();
        anularRemesa();
    });

    // Agregar fila de detalle inicial
    agregarFilaDetalle();
});

// Abrir modal
function abrirModal() {
    $('#formRemesa')[0].reset();
    $('#tbodyDetalle').html('');
    detalle = [];
    agregarFilaDetalle();
    $('#modalRemesa').modal('show');
}

// Agregar fila al detalle
function agregarFilaDetalle() {
    let index = $('#tbodyDetalle tr').length;
    let html = `
        <tr data-index="${index}">
            <td>
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm cuenta-input" 
                        placeholder="Código" onkeyup="buscarCuenta(this)" autocomplete="off">
                    <input type="hidden" class="cuenta-hidden" name="detalle[${index}][cuenta]">
                    <div class="dropdown-menu cuenta-dropdown" style="max-height: 200px; overflow-y: auto;"></div>
                </div>
            </td>
            <td>
                <select class="form-select form-select-sm tipo-select">
                    <option value="Debe">DEBE</option>
                    <option value="Haber">HABER</option>
                </select>
                <input type="hidden" name="detalle[${index}][tipo]" value="Debe">
            </td>
            <td>
                <input type="number" step="0.01" class="form-control form-control-sm monto-input text-end" 
                    placeholder="0.00" oninput="calcularTotales()">
                <input type="hidden" name="detalle[${index}][monto]" value="0">
            </td>
            <td>
                <input type="text" class="form-control form-select-sm concepto-input" 
                    placeholder="Concepto" oninput="actualizarConcepto(this)">
                <input type="hidden" name="detalle[${index}][concepto]" value="">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarFila(this)">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>
    `;
    $('#tbodyDetalle').append(html);
}

// Buscar cuenta contable
function buscarCuenta(input) {
    let valor = $(input).val();
    let dropdown = $(input).siblings('.cuenta-dropdown');
    
    if (valor.length < 2) {
        dropdown.hide();
        return;
    }

    $.get(BASE_URL + 'remesas/cuentas', { search: valor }, function(response) {
        let html = '';
        response.forEach(cuenta => {
            html += `<a class="dropdown-item" href="#" onclick="seleccionarCuenta('${cuenta.codigo}', '${cuenta.nombre_cuenta}', this)">${cuenta.codigo} - ${cuenta.nombre_cuenta}</a>`;
        });
        if (html) {
            dropdown.html(html).show();
        } else {
            dropdown.hide();
        }
    });
}

// Seleccionar cuenta
function seleccionarCuenta(codigo, nombre, link) {
    let row = $(link).closest('tr');
    row.find('.cuenta-input').val(codigo);
    row.find('.cuenta-hidden').val(codigo);
    row.find('.concepto-input').val(nombre);
    row.find('input[name$="[concepto]"]').val(nombre);
    row.find('.cuenta-dropdown').hide();
}

// Eliminar fila
function eliminarFila(btn) {
    $(btn).closest('tr').remove();
    calcularTotales();
}

// Calcular totales
function calcularTotales() {
    let totalDebe = 0;
    let totalHaber = 0;

    $('#tbodyDetalle tr').each(function() {
        let tipo = $(this).find('.tipo-select').val();
        let monto = parseFloat($(this).find('.monto-input').val()) || 0;

        // Actualizar hidden
        $(this).find('input[name$="[monto]"]').val(monto);
        $(this).find('input[name$="[tipo]"]').val(tipo);

        if (tipo == 'Debe') {
            totalDebe += monto;
        } else {
            totalHaber += monto;
        }
    });

    $('#totalDebe').text(totalDebe.toFixed(2));
    $('#totalHaber').text(totalHaber.toFixed(2));
    $('#monto').val(totalDebe.toFixed(2));
}

// Guardar remesa
function guardarRemesa() {
    // Recopilar detalle
    let detalle = [];
    $('#tbodyDetalle tr').each(function() {
        let cuenta = $(this).find('.cuenta-hidden').val();
        let tipo = $(this).find('.tipo-select').val();
        let monto = parseFloat($(this).find('.monto-input').val()) || 0;
        let concepto = $(this).find('input[name$="[concepto]"]').val();

        if (cuenta && monto > 0) {
            detalle.push({ cuenta, tipo, monto, concepto });
        }
    });

    if (detalle.length === 0) {
        Swal.fire('Error', 'Debe agregar al menos un detalle contable', 'error');
        return;
    }

    let datos = {
        id_banco: $('#id_banco').val(),
        tipo_transaccion: $('#tipo_transaccion').val(),
        concepto: $('#concepto').val(),
        tipo_partida_remesa: $('#tipo_partida_remesa').val(),
        monto: $('#monto').val(),
        detalle: JSON.stringify(detalle)
    };

    $('#btnGuardar').prop('disabled', true);

    $.post(BASE_URL + 'remesas/registrar', datos, function(response) {
        if (response.success) {
            Swal.fire('Éxito', 'Remesa registrada correctamente', 'success');
            $('#modalRemesa').modal('hide');
            table.ajax.reload();
        } else {
            Swal.fire('Error', response.error || 'Error al registrar', 'error');
        }
        $('#btnGuardar').prop('disabled', false);
    }).fail(function() {
        Swal.fire('Error', 'Error de conexión', 'error');
        $('#btnGuardar').prop('disabled', false);
    });
}

// Ver remesa
function verRemesa(id) {
    idVer = id;
    $.get(BASE_URL + 'remesas/ver/' + id, function(response) {
        let r = response.remesa;
        let d = response.detalle;

        let html = `
            <div class="row mb-3">
                <div class="col-md-4"><strong>Correlativo:</strong> REM-${String(r.id).padStart(6, '0')}</div>
                <div class="col-md-4"><strong>Tipo:</strong> ${r.tipo_transaccion.toUpperCase()}</div>
                <div class="col-md-4"><strong>Fecha:</strong> ${moment(r.fecha_creacion).format('DD/MM/YYYY HH:mm')}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Banco:</strong> ${r.banco_nombre} - ${r.banco_cuenta || ''}</div>
                <div class="col-md-6"><strong>Monto:</strong> ${parseFloat(r.monto).toFixed(2)}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Tipo Partida:</strong> ${r.tipo_partida_remesa || '-'}</div>
                <div class="col-md-6"><strong>Concepto:</strong> ${r.concepto || '-'}</div>
            </div>
            ${r.estado == 'anulado' ? `<div class="alert alert-danger"><strong>ANULADO:</strong> ${r.motivo_anulacion}</div>` : ''}
            <hr>
            <h6>Detalle Contable</h6>
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>CUENTA</th>
                        <th>CONCEPTO</th>
                        <th>DEBE</th>
                        <th>HABER</th>
                    </tr>
                </thead>
                <tbody>
        `;

        let tDebe = 0, tHaber = 0;
        d.forEach(item => {
            let debe = item.tipo == 'Debe' ? parseFloat(item.monto) : 0;
            let haber = item.tipo == 'Haber' ? parseFloat(item.monto) : 0;
            tDebe += debe;
            tHaber += haber;
            html += `
                <tr>
                    <td>${item.cuenta_contable}</td>
                    <td>${item.concepto || item.nombre_cuenta || '-'}</td>
                    <td class="text-end">${debe > 0 ? '$' + debe.toFixed(2) : ''}</td>
                    <td class="text-end">${haber > 0 ? '$' + haber.toFixed(2) : ''}</td>
                </tr>
            `;
        });

        html += `
                    <tr style="background: #e0e0e0; font-weight: bold;">
                        <td colspan="2">TOTALES</td>
                        <td class="text-end">$${tDebe.toFixed(2)}</td>
                        <td class="text-end">$${tHaber.toFixed(2)}</td>
                    </tr>
                </tbody>
            </table>
        `;

        $('#verDetalle').html(html);
        $('#btnPdf').attr('href', BASE_URL + 'remesas/pdf/' + id);
        
        if (r.estado == 'anulado') {
            $('#btnAnular').hide();
        } else {
            $('#btnAnular').show();
        }
        
        $('#modalVer').modal('show');
    });
}

// Confirmar anulación
function confirmarAnulacion() {
    $('#id_anular').val(idVer);
    $('#modalVer').modal('hide');
    $('#modalAnular').modal('show');
}

// Anular remesa
function anularRemesa() {
    let id = $('#id_anular').val();
    let motivo = $('#motivo_anulacion').val();

    $.post(BASE_URL + 'remesas/anular', { id, motivo }, function(response) {
        if (response.success) {
            Swal.fire('Éxito', 'Remesa anulada correctamente', 'success');
            $('#modalAnular').modal('hide');
            $('#modalVer').modal('hide');
            table.ajax.reload();
        } else {
            Swal.fire('Error', response.error || 'Error al anular', 'error');
        }
    });
}
