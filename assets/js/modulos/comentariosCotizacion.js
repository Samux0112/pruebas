
document.addEventListener('DOMContentLoaded', function () {
    const formComentario = document.getElementById('formComentario');
    const tblComentarios = document.getElementById('tblComentarios').getElementsByTagName('tbody')[0];
    // Debes definir cómo obtienes el id de cotización, por ejemplo desde un input hidden:
    const idCotizacion = document.getElementById('id_cotizacion').value;

    // Guardar comentario
    formComentario.addEventListener('submit', function (e) {
        e.preventDefault();
        const comentario = document.getElementById('comentario').value;
        const fecha = document.getElementById('fecha').value;
        if (comentario === '' || !idCotizacion) {
            Swal.fire('El comentario y la cotización son requeridos', '', 'warning');
            return;
        }
        $.ajax({
            url: base_url + 'cotizaciones/registrarComentario',
            type: 'POST',
            data: { comentario, fecha, id_cotizacion: idCotizacion },
            success: function (response) {
                const res = JSON.parse(response);
                Swal.fire(res.msg, '', res.type);
                if (res.type === 'success') {
                    formComentario.reset();
                    cargarComentarios();
                }
            }
        });
    });

    // Cargar comentarios
    function cargarComentarios() {
        $.ajax({
            url: base_url + 'cotizaciones/listarComentarios',
            type: 'GET',
            data: { id_cotizacion: idCotizacion },
            success: function (response) {
                const comentarios = JSON.parse(response);
                tblComentarios.innerHTML = '';
                comentarios.forEach(function (item) {
                    const row = tblComentarios.insertRow();
                    row.insertCell(0).textContent = item.fecha;
                    row.insertCell(1).textContent = item.comentario;
                    row.insertCell(2).textContent = item.usuario;
                });
            }
        });
    }

    cargarComentarios();
});
