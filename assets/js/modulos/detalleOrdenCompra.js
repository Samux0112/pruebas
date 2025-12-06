// JS para la vista detalle de orden de compra

document.addEventListener('DOMContentLoaded', function() {
    var btnAutorizarDetalle = document.getElementById('btnAutorizarDetalleOrden');
    if (btnAutorizarDetalle) {
        btnAutorizarDetalle.addEventListener('click', function() {
            var id = btnAutorizarDetalle.getAttribute('data-id');
            if (!id) return;
            Swal.fire({
                title: '¿Está seguro que desea autorizar esta orden de compra?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, autorizar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = base_url + 'ordenesCompra/autorizar';
                    var http = new XMLHttpRequest();
                    http.open('POST', url, true);
                    http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    http.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            try {
                                var res = JSON.parse(this.responseText);
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Autorizada!',
                                        text: 'Orden autorizada correctamente.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => { window.location.reload(); });
                                } else {
                                    Swal.fire('Error', 'Error al autorizar la orden.', 'error');
                                }
                            } catch (e) {
                                Swal.fire('Error', 'Error en la respuesta del servidor.', 'error');
                            }
                        }
                    };
                    http.send('id=' + encodeURIComponent(id) + '&estado=aprobado');
                }
            });
        });
    }

    var btnRechazarDetalle = document.getElementById('btnRechazarDetalleOrden');
    if (btnRechazarDetalle) {
        btnRechazarDetalle.addEventListener('click', function() {
            var id = btnRechazarDetalle.getAttribute('data-id');
            if (!id) return;
            Swal.fire({
                title: '¿Está seguro que desea rechazar esta orden de compra?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, rechazar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = base_url + 'ordenesCompra/autorizar';
                    var http = new XMLHttpRequest();
                    http.open('POST', url, true);
                    http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    http.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            try {
                                var res = JSON.parse(this.responseText);
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Rechazada!',
                                        text: 'Orden rechazada correctamente.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {  window.location.href = base_url + 'ordenesCompra/listado'; });
                                } else {
                                    Swal.fire('Error', 'Error al rechazar la orden.', 'error');
                                }
                            } catch (e) {
                                Swal.fire('Error', 'Error en la respuesta del servidor.', 'error');
                            }
                        }
                    };
                    http.send('id=' + encodeURIComponent(id) + '&estado=rechazado');
                }
            });
        });
    }
    // (Las funciones de mensajes visuales y confirmación ya no son necesarias, todo se maneja con SweetAlert2)
});
