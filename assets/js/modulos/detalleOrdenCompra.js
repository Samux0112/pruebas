// JS para la vista detalle de orden de compra

document.addEventListener('DOMContentLoaded', function() {
    var btnAutorizarDetalle = document.getElementById('btnAutorizarDetalleOrden');
    if (btnAutorizarDetalle) {
        btnAutorizarDetalle.addEventListener('click', function() {
            var id = btnAutorizarDetalle.getAttribute('data-id');
            if (!id) return;
            if (!confirm('¿Está seguro que desea autorizar esta orden de compra?')) return;
            var url = base_url + 'ordenesCompra/autorizar';
            var http = new XMLHttpRequest();
            http.open('POST', url, true);
            http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    try {
                        var res = JSON.parse(this.responseText);
                        if (res.success) {
                            alert('Orden autorizada correctamente.');
                            window.location.reload();
                        } else {
                            alert('Error al autorizar la orden.');
                        }
                    } catch (e) {
                        alert('Error en la respuesta del servidor.');
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
            if (!confirm('¿Está seguro que desea rechazar esta orden de compra?')) return;
            var url = base_url + 'ordenesCompra/autorizar';
            var http = new XMLHttpRequest();
            http.open('POST', url, true);
            http.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    try {
                        var res = JSON.parse(this.responseText);
                        if (res.success) {
                            alert('Orden rechazada correctamente.');
                            window.location.reload();
                        } else {
                            alert('Error al rechazar la orden.');
                        }
                    } catch (e) {
                        alert('Error en la respuesta del servidor.');
                    }
                }
            };
            http.send('id=' + encodeURIComponent(id) + '&estado=rechazado');
        });
    }
});
