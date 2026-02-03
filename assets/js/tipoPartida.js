let tblTipoPartida;

document.addEventListener('DOMContentLoaded', function() {
    tblTipoPartida = $('#tblTipoPartida').DataTable({
        ajax: {
            url: base_url + 'tipoPartida/listar',
            dataSrc: '',
            error: function(xhr, error, thrown) {
                console.log('Error:', error, thrown);
            }
        },
        columns: [
            { data: 'id' },
            { data: 'nombre' },
            {
                data: null,
                render: function(data) {
                    return `
                        <button class="btn btn-warning btn-sm" onclick="editarTipoPartida('${data.id}')"><i class="fa-solid fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarTipoPartida('${data.id}')"><i class="fa-solid fa-trash"></i></button>
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

    $('#formTipoPartida').submit(function(e) {
        e.preventDefault();
        guardarTipoPartida();
    });
});

function modalTipoPartida() {
    $('#formTipoPartida')[0].reset();
    $('#id_original').val('');
    $('#id').prop('disabled', false);
    $('#titleModal').text('Nuevo Tipo de Partida');
    $('#btnAccion').text('Registrar');
    $('#modalTipoPartida').modal('show');
}

function editarTipoPartida(id) {
    $.ajax({
        url: base_url + 'tipoPartida/getById/' + id,
        type: 'GET',
        success: function(response) {
            const data = JSON.parse(response);
            $('#id_original').val(data.id);
            $('#id').val(data.id);
            $('#id').prop('disabled', true);
            $('#nombre').val(data.nombre);
            $('#titleModal').text('Editar Tipo de Partida');
            $('#btnAccion').text('Actualizar');
            $('#modalTipoPartida').modal('show');
        }
    });
}

function eliminarTipoPartida(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'El tipo de partida será eliminado',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: base_url + 'tipoPartida/eliminar/' + id,
                type: 'GET',
                success: function(response) {
                    const res = JSON.parse(response);
                    Swal.fire('Eliminado', res.msg, res.type);
                    tblTipoPartida.ajax.reload();
                }
            });
        }
    });
}

function guardarTipoPartida() {
    const id = $('#id').val().trim();
    const nombre = $('#nombre').val().trim();
    const idOriginal = $('#id_original').val().trim();

    if (id == '' || nombre == '') {
        Swal.fire('Atención', 'Todos los campos son requeridos', 'warning');
        return;
    }

    const url = base_url + 'tipoPartida/guardar';
    const formData = new FormData();
    formData.append('id', id);
    formData.append('nombre', nombre);
    formData.append('id_original', idOriginal);

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            try {
                const res = JSON.parse(response);
                Swal.fire('Éxito', res.msg, res.type);
                if (res.type === 'success') {
                    $('#modalTipoPartida').modal('hide');
                    tblTipoPartida.ajax.reload();
                }
            } catch (e) {
                Swal.fire('Error', 'Respuesta inválida del servidor', 'error');
                console.log(response);
            }
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'Error del servidor: ' + error, 'error');
            console.log(xhr.responseText);
        }
    });
}
