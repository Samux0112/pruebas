// JS para la vista detalle de orden de compra

document.addEventListener('DOMContentLoaded', function() {
    var btnAutorizarDetalle = document.getElementById('btnAutorizarDetalleOrden');
    if (btnAutorizarDetalle) {
        btnAutorizarDetalle.addEventListener('click', function() {
            var id = btnAutorizarDetalle.getAttribute('data-id');
            if (!id) return;
            mostrarConfirmacionOrdenCompra('¿Está seguro que desea autorizar esta orden de compra?', function(confirmado) {
                if (!confirmado) return;
                var url = base_url + 'ordenesCompra/autorizar';
                var http = new XMLHttpRequest();
                http.open('POST', url, true);
                http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                http.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        try {
                            var res = JSON.parse(this.responseText);
                            if (res.success) {
                                mostrarMensajeOrdenCompra('Orden autorizada correctamente.', 'success');
                                setTimeout(function(){ window.location.reload(); }, 1500);
                            } else {
                                mostrarMensajeOrdenCompra('Error al autorizar la orden.', 'danger');
                            }
                        } catch (e) {
                            mostrarMensajeOrdenCompra('Error en la respuesta del servidor.', 'danger');
                        }
                    }
                };
                http.send('id=' + encodeURIComponent(id) + '&estado=aprobado');
            });
            var url = base_url + 'ordenesCompra/autorizar';
            var http = new XMLHttpRequest();
            http.open('POST', url, true);
            http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    try {
                        var res = JSON.parse(this.responseText);
                        if (res.success) {
                            mostrarMensajeOrdenCompra('Orden autorizada correctamente.', 'success');
                            setTimeout(function(){ window.location.reload(); }, 1500);
                        } else {
                            mostrarMensajeOrdenCompra('Error al autorizar la orden.', 'danger');
                        }
                    } catch (e) {
                        mostrarMensajeOrdenCompra('Error en la respuesta del servidor.', 'danger');
                    }
                }
            };
            http.send('id=' + encodeURIComponent(id) + '&estado=aprobado');
        });
    }

    var btnRechazarDetalle = document.getElementById('btnRechazarDetalleOrden');
    if (btnRechazarDetalle) {
        btnRechazarDetalle.addEventListener('click', function() {
            var id = btnRechazarDetalle.getAttribute('data-id');
            if (!id) return;
            mostrarConfirmacionOrdenCompra('¿Está seguro que desea rechazar esta orden de compra?', function(confirmado) {
                if (!confirmado) return;
                var url = base_url + 'ordenesCompra/autorizar';
                var http = new XMLHttpRequest();
                http.open('POST', url, true);
                http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                http.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        try {
                            var res = JSON.parse(this.responseText);
                            if (res.success) {
                                mostrarMensajeOrdenCompra('Orden rechazada correctamente.', 'success');
                                setTimeout(function(){ window.location.reload(); }, 1500);
                            } else {
                                mostrarMensajeOrdenCompra('Error al rechazar la orden.', 'danger');
                            }
                        } catch (e) {
                            mostrarMensajeOrdenCompra('Error en la respuesta del servidor.', 'danger');
                        }
                    }
                };
                http.send('id=' + encodeURIComponent(id) + '&estado=rechazado');
            });
            var url = base_url + 'ordenesCompra/autorizar';
            var http = new XMLHttpRequest();
            http.open('POST', url, true);
            http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    try {
                        var res = JSON.parse(this.responseText);
                        if (res.success) {
                            mostrarMensajeOrdenCompra('Orden rechazada correctamente.', 'success');
                            setTimeout(function(){ window.location.reload(); }, 1500);
                        } else {
                            mostrarMensajeOrdenCompra('Error al rechazar la orden.', 'danger');
                        }
                    } catch (e) {
                        mostrarMensajeOrdenCompra('Error en la respuesta del servidor.', 'danger');
                    }
                }
            };
            http.send('id=' + encodeURIComponent(id) + '&estado=rechazado');
        });
    }
    // Función para mostrar mensajes visuales en la plantilla
    function mostrarMensajeOrdenCompra(mensaje, tipo) {
        var contenedor = document.getElementById('mensaje-orden-compra');
        if (!contenedor) return;
        contenedor.innerHTML = '<div class="alert alert-' + tipo + ' alert-dismissible fade show" role="alert">'
            + mensaje +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '</div>';
    }

    // Función para mostrar confirmación visual en la plantilla
    function mostrarConfirmacionOrdenCompra(mensaje, callback) {
        var contenedor = document.getElementById('mensaje-orden-compra');
        if (!contenedor) return callback(false);
        contenedor.innerHTML = '<div class="alert alert-warning alert-dismissible fade show" role="alert">'
            + mensaje +
            '<div class="mt-3"><button type="button" class="btn btn-sm btn-primary me-2" id="btnConfirmarAccionOrden">Sí</button>'
            + '<button type="button" class="btn btn-sm btn-secondary" id="btnCancelarAccionOrden">No</button></div>'
            + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'
            + '</div>';
        document.getElementById('btnConfirmarAccionOrden').onclick = function() {
            contenedor.innerHTML = '';
            callback(true);
        };
        document.getElementById('btnCancelarAccionOrden').onclick = function() {
            contenedor.innerHTML = '';
            callback(false);
        };
    }
});
