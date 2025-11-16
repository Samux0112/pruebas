// JS para la vista detalleCotizacion
// Puedes agregar aquí funciones para adjudicar productos, enviar orden de compra, validaciones, etc.

document.addEventListener('DOMContentLoaded', function() {
    var btnOrdenCompra = document.getElementById('btnOrdenCompra');
    if (btnOrdenCompra) {
        btnOrdenCompra.addEventListener('click', function () {
            var adjudicados = Array.from(document.querySelectorAll('input[name="adjudicar[]"]:checked')).map(function(cb) { return cb.value; });
            if (adjudicados.length === 0) {
                alertaPersonalizada('warning', 'Seleccione al menos un producto para adjudicar.');
                return;
            }
            var proveedor = document.querySelector('input[name="proveedor"]').value;
            var cotizacion = document.querySelector('input[name="cotizacion_id"]') ? document.querySelector('input[name="cotizacion_id"]').value : null;
            var url = base_url + 'ordenesCompra/crear';
            var http = new XMLHttpRequest();
            http.open('POST', url, true);
            http.setRequestHeader('Content-Type', 'application/json');
            http.send(JSON.stringify({
                productos: adjudicados,
                proveedor: proveedor,
                cotizacion: cotizacion
            }));
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var res = JSON.parse(this.responseText);
                    console.log(this.responseText);
                    if (res.success && res.idOrden) {
                        alertaPersonalizada('success', 'Orden de compra generada correctamente.');
                        setTimeout(function () {
                            window.open(base_url + 'ordenesCompra/generarPDF/' + res.idOrden, '_blank');
                        }, 1000);
                    } else {
                        alertaPersonalizada('error', 'Error al generar la orden de compra.');
                    }
                }
            }
        });
    }
});
