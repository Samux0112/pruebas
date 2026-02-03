// DataTables para Tipos de Transacción
let tblTransaccion = $('#tblTransaccion').DataTable({
    ajax: {
        url: base_url + 'bancos/listarTransaccion',
        dataSrc: '',
        xhrFields: { withCredentials: true }
    },
    columns: [
        { data: 'id' },
        { data: 'nombre' },
        { data: 'nombre_partida' },
        { data: 'tipo_transaccion' },
        {
            data: null,
            render: function(data, type, row) {
                return `
                    <button class="btn btn-warning btn-sm" onclick="editarTransaccion(${row.id})"><i class="fa-solid fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarTransaccion(${row.id})"><i class="fa-solid fa-trash"></i></button>
                `;
            }
        }
    ],
    language: {
        url: base_url + 'assets/js/spanish.json'
    },
    dom: 'Bfrtip',
    responsive: true,
    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
});

// DataTables para Bancos
let tblBancos = $('#tblBancos').DataTable({
    ajax: {
        url: base_url + 'bancos/listarBancos',
        dataSrc: '',
        xhrFields: { withCredentials: true }
    },
    columns: [
        { data: 'id' },
        { data: 'nombre' },
        { data: 'numero_cuenta' },
        { 
            data: null,
            render: function(data) {
                if (data.nombre_cuenta) {
                    return data.cuenta_contable + ' - ' + data.nombre_cuenta;
                }
                return data.cuenta_contable;
            }
        },
        {
            data: null,
            render: function(data, type, row) {
                if (row.pos == 1) {
                    return '<span class="badge bg-success">SI</span>';
                } else {
                    return '<span class="badge bg-secondary">NO</span>';
                }
            }
        },
        {
            data: null,
            render: function(data, type, row) {
                return `
                    <button class="btn btn-warning btn-sm" onclick="editarBanco(${row.id})"><i class="fa-solid fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarBanco(${row.id})"><i class="fa-solid fa-trash"></i></button>
                `;
            }
        }
    ],
    language: {
        url: base_url + 'assets/js/spanish.json'
    },
    dom: 'Bfrtip',
    responsive: true,
    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
});

// DataTables para Cuentas Bancarias
let tblCuentasBancarias = $('#tblCuentasBancarias').DataTable({
    ajax: {
        url: base_url + 'bancos/listarCuentasBancarias',
        dataSrc: '',
        xhrFields: { withCredentials: true }
    },
    columns: [
        { data: 'id' },
        { data: 'nombre_banco' },
        { data: 'numero_cuenta' },
        { 
            data: null,
            render: function(data) {
                return data.nombre_propietario + '<br><small class="text-muted">RUC: ' + data.ruc + '</small>';
            }
        },
        { 
            data: null,
            render: function(data) {
                if (data.nombre_cuenta) {
                    return data.cuenta_contable + ' - ' + data.nombre_cuenta;
                }
                return data.cuenta_contable;
            }
        },
        {
            data: null,
            render: function(data) {
                if (data.tipo_cuenta == '1') {
                    return '<span class="badge bg-primary">Corriente</span>';
                } else {
                    return '<span class="badge bg-info">Ahorro</span>';
                }
            }
        },
        {
            data: null,
            render: function(data, type, row) {
                return `
                    <button class="btn btn-warning btn-sm" onclick="editarCuentaBancaria(${row.id})"><i class="fa-solid fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="eliminarCuentaBancaria(${row.id})"><i class="fa-solid fa-trash"></i></button>
                `;
            }
        }
    ],
    language: {
        url: base_url + 'assets/js/spanish.json'
    },
    dom: 'Bfrtip',
    responsive: true,
    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
});

// Funciones para Tipos de Transacción
function modalTransaccion() {
    $('#formTransaccion')[0].reset();
    $('#id_transaccion').val('');
    $('#tipo_partida').val('').trigger('change');
    $('#titleModalTransaccion').text('Nuevo Tipo de Transacción');
    $('#btnAccionTransaccion').text('Registrar');
    $('#modalTransaccion').modal('show');
}

function editarTransaccion(id) {
    $.ajax({
        url: base_url + 'bancos/getTransaccion/' + id,
        type: 'GET',
        xhrFields: { withCredentials: true },
        success: function(response) {
            const data = JSON.parse(response);
            $('#id_transaccion').val(data.id);
            $('#nombre_transaccion').val(data.nombre);
            $('#tipo_partida').val(data.tipo_partida_contable).trigger('change');
            $('#tipo_transaccion').val(data.tipo_transaccion);
            $('#titleModalTransaccion').text('Editar Tipo de Transacción');
            $('#btnAccionTransaccion').text('Actualizar');
            $('#modalTransaccion').modal('show');
        }
    });
}

function eliminarTransaccion(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'El tipo de transacción será eliminado',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: base_url + 'bancos/eliminarTransaccion/' + id,
                type: 'GET',
                xhrFields: { withCredentials: true },
                success: function(response) {
                    const res = JSON.parse(response);
                    Swal.fire('Eliminado', res.msg, res.type);
                    tblTransaccion.ajax.reload();
                }
            });
        }
    });
}

$('#formTransaccion').submit(function(e) {
    e.preventDefault();
    const id = $('#id_transaccion').val();
    const nombre = $('#nombre_transaccion').val();
    const tipo_partida = $('#tipo_partida').val();
    const tipo_transaccion = $('#tipo_transaccion').val();

    if (nombre == '' || tipo_partida == '' || tipo_transaccion == '') {
        Swal.fire('Atención', 'Todos los campos son requeridos', 'warning');
        return;
    }

    const url = id ? base_url + 'bancos/modificarTransaccion' : base_url + 'bancos/registrarTransaccion';
    const formData = new FormData();
    formData.append('id', id);
    formData.append('nombre', nombre);
    formData.append('tipo_partida', tipo_partida);
    formData.append('tipo_transaccion', tipo_transaccion);

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhrFields: { withCredentials: true },
        success: function(response) {
            const res = JSON.parse(response);
            Swal.fire('Éxito', res.msg, res.type);
            if (res.type === 'success') {
                $('#modalTransaccion').modal('hide');
                tblTransaccion.ajax.reload();
            }
        }
    });
});

// Funciones para Bancos
function modalBanco() {
    $('#formBanco')[0].reset();
    $('#id_banco').val('');
    $('#cuenta_pos').val('0');
    $('#titleModalBanco').text('Nuevo Banco');
    $('#btnAccionBanco').text('Registrar');
    $('#cuenta_contable').val('').trigger('change');
    $('#modalBanco').modal('show');
}

function editarBanco(id) {
    $.ajax({
        url: base_url + 'bancos/getBanco/' + id,
        type: 'GET',
        xhrFields: { withCredentials: true },
        success: function(response) {
            const data = JSON.parse(response);
            $('#id_banco').val(data.id);
            $('#nombre_banco').val(data.nombre);
            $('#numero_cuenta').val(data.numero_cuenta);
            $('#cuenta_contable').val(data.cuenta_contable).trigger('change');
            $('#cuenta_pos').val(data.pos);
            $('#titleModalBanco').text('Editar Banco');
            $('#btnAccionBanco').text('Actualizar');
            $('#modalBanco').modal('show');
        }
    });
}

function eliminarBanco(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'El banco será eliminado',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: base_url + 'bancos/eliminarBanco/' + id,
                type: 'GET',
                xhrFields: { withCredentials: true },
                success: function(response) {
                    const res = JSON.parse(response);
                    Swal.fire('Eliminado', res.msg, res.type);
                    tblBancos.ajax.reload();
                }
            });
        }
    });
}

$('#formBanco').submit(function(e) {
    e.preventDefault();
    const id = $('#id_banco').val();
    const nombre = $('#nombre_banco').val();
    const numero_cuenta = $('#numero_cuenta_banco').val();
    const cuenta_contable = $('#cuenta_contable_banco').val();
    const cuenta_pos = $('#cuenta_pos_banco').val();

    if (nombre == '' || numero_cuenta == '' || cuenta_contable == '') {
        Swal.fire('Atención', 'Todos los campos son requeridos', 'warning');
        return;
    }

    const url = id ? base_url + 'bancos/modificarBanco' : base_url + 'bancos/registrarBanco';
    const formData = new FormData();
    formData.append('id', id);
    formData.append('nombre', nombre);
    formData.append('numero_cuenta', numero_cuenta);
    formData.append('cuenta_contable', cuenta_contable);
    formData.append('cuenta_pos', cuenta_pos);

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhrFields: { withCredentials: true },
        success: function(response) {
            const res = JSON.parse(response);
            Swal.fire('Éxito', res.msg, res.type);
            if (res.type === 'success') {
                $('#modalBanco').modal('hide');
                tblBancos.ajax.reload();
            }
        }
    });
});

// Funciones para Cuentas Bancarias
function modalCuentaBancaria() {
    $('#formCuentaBancaria')[0].reset();
    $('#id_cuenta_bancaria').val('');
    $('#banco_id').val('').trigger('change');
    $('#cuenta_contable_cb').val('').trigger('change');
    $('#propietario_cb').val('').trigger('change');
    $('#titleModalCuenta').text('Nueva Cuenta Bancaria');
    $('#btnAccionCuenta').text('Registrar');
    $('#modalCuentaBancaria').modal('show');
}

function editarCuentaBancaria(id) {
    $.ajax({
        url: base_url + 'bancos/getCuentaBancaria/' + id,
        type: 'GET',
        xhrFields: { withCredentials: true },
        success: function(response) {
            const data = JSON.parse(response);
            $('#id_cuenta_bancaria').val(data.id);
            $('#banco_id').val(data.banco_id).trigger('change');
            $('#numero_cuenta_cb').val(data.numero_cuenta);
            $('#cuenta_contable_cb').val(data.cuenta_contable).trigger('change');
            $('#propietario_cb').val(data.proveedor_id).trigger('change');
            $('#tipo_cuenta_cb').val(data.tipo_cuenta);
            $('#titleModalCuenta').text('Editar Cuenta Bancaria');
            $('#btnAccionCuenta').text('Actualizar');
            $('#modalCuentaBancaria').modal('show');
        }
    });
}

function eliminarCuentaBancaria(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'La cuenta bancaria será eliminada',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: base_url + 'bancos/eliminarCuentaBancaria/' + id,
                type: 'GET',
                xhrFields: { withCredentials: true },
                success: function(response) {
                    const res = JSON.parse(response);
                    Swal.fire('Eliminado', res.msg, res.type);
                    tblCuentasBancarias.ajax.reload();
                }
            });
        }
    });
}

$('#formCuentaBancaria').submit(function(e) {
    e.preventDefault();
    const id = $('#id_cuenta_bancaria').val();
    const banco_id = $('#banco_id').val();
    const numero_cuenta = $('#numero_cuenta_cb').val();
    const cuenta_contable = $('#cuenta_contable_cb').val();
    const propietario_id = $('#propietario_cb').val();
    const tipo_cuenta = $('#tipo_cuenta_cb').val();

    if (banco_id == '' || numero_cuenta == '' || cuenta_contable == '' || 
        propietario_id == '' || tipo_cuenta == '') {
        Swal.fire('Atención', 'Todos los campos son requeridos', 'warning');
        return;
    }

    const url = id ? base_url + 'bancos/modificarCuentaBancaria' : base_url + 'bancos/registrarCuentaBancaria';
    const formData = new FormData();
    formData.append('id', id);
    formData.append('banco_id', banco_id);
    formData.append('numero_cuenta', numero_cuenta);
    formData.append('cuenta_contable', cuenta_contable);
    formData.append('propietario_id', propietario_id);
    formData.append('tipo_cuenta', tipo_cuenta);

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhrFields: { withCredentials: true },
        success: function(response) {
            const res = JSON.parse(response);
            Swal.fire('Éxito', res.msg, res.type);
            if (res.type === 'success') {
                $('#modalCuentaBancaria').modal('hide');
                tblCuentasBancarias.ajax.reload();
            }
        }
    });
});

// Inicializar Select2 para los campos del modal Cuentas Bancarias
$(document).ready(function() {
    $('#modalCuentaBancaria').on('shown.bs.modal', function() {
        // Banco
        $('#banco_id').select2({
            dropdownParent: $('#modalCuentaBancaria'),
            placeholder: 'Buscar banco...',
            allowClear: true
        });
        
        // Cuenta Contable
        $('#cuenta_contable_cb').select2({
            dropdownParent: $('#modalCuentaBancaria'),
            placeholder: 'Buscar cuenta contable...',
            allowClear: true
        });
        
        // Propietario (Proveedor)
        $('#propietario_cb').select2({
            dropdownParent: $('#modalCuentaBancaria'),
            placeholder: 'Buscar proveedor...',
            allowClear: true
        });
    });
});
