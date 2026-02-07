// DataTables para Tipos de Transacción - Lazy initialization
let tblTransaccion;
function inicializarTablaTransaccion() {
    if (tblTransaccion) return;
    tblTransaccion = $('#tblTransaccion').DataTable({
        ajax: {
            url: base_url + 'bancos/listarTransaccion',
            dataSrc: ''
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
            url: base_url + 'assets/js/espanol.json'
        },
        responsive: true
    });
}

// DataTables para Bancos - Lazy initialization
let tblBancos;
function inicializarTablaBancos() {
    if (tblBancos) return;
    tblBancos = $('#tblBancos').DataTable({
        ajax: {
            url: base_url + 'bancos/listarBancos',
            dataSrc: ''
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
            { data: 'correlativo_cheque' },
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
            url: base_url + 'assets/js/espanol.json'
        },
        responsive: true
    });
}

// Inicializar cuando se muestra cada tab
$('#nav-transaccion-tab').on('shown.bs.tab', function() {
    inicializarTablaTransaccion();
});

$('#nav-bancos-tab').on('shown.bs.tab', function() {
    inicializarTablaBancos();
});

// Si ya está activo al cargar
$(document).ready(function() {
    if ($('#nav-transaccion').hasClass('show active')) {
        inicializarTablaTransaccion();
    }
    if ($('#nav-bancos').hasClass('show active')) {
        inicializarTablaBancos();
    }
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
        success: function(response) {
            const data = JSON.parse(response);
            $('#id_banco').val(data.id);
            $('#nombre_banco').val(data.nombre);
            $('#numero_cuenta_banco').val(data.numero_cuenta);
            $('#cuenta_contable_banco').val(data.cuenta_contable).trigger('change');
            $('#cuenta_pos_banco').val(data.pos);
            $('#correlativo_cheque_banco').val(data.correlativo_cheque || 1);
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
    const correlativo_cheque = $('#correlativo_cheque_banco').val();

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
    formData.append('correlativo_cheque', correlativo_cheque);

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
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
