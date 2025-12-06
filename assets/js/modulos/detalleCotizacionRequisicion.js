// JS para la vista detalleCotizacion
// Puedes agregar aquí funciones para adjudicar productos, enviar orden de compra, validaciones, etc.

document.addEventListener('DOMContentLoaded', function() {
    var btnOrdenCompra = document.getElementById('btnOrdenCompra');
    if (btnOrdenCompra) {
        btnOrdenCompra.addEventListener('click', function () {
            var adjudicados = [];
            document.querySelectorAll('input[name="adjudicar[]"]:checked').forEach(function(cb) {
                var row = cb.closest('tr');
                // Ajustar índices según la nueva estructura de la tabla
                // Si el primer td (id) está oculto, sigue siendo children[0]
                adjudicados.push({
                   // id: cb.value,
                    id: row.children[0].textContent.trim(),
                    nombre: row.children[1].textContent.trim(),
                    cantidad: row.children[2].textContent.trim(),
                    descripcion: row.children[3].textContent.trim(),
                    precio: row.children[4].textContent.trim(),
                    descuento: row.children[5].textContent.trim(),
                    subtotal: row.children[6].textContent.trim()
                });
            });
            if (adjudicados.length === 0) {
                alertaPersonalizada('warning', 'Seleccione al menos un producto para adjudicar.');
                return;
            }
            var proveedor = document.querySelector('input[name="proveedor"]').value;
            var proveedor_id = null;
            // Buscar el id_proveedor en la cabecera si existe
            var cabecera = document.querySelector('.mb-3');
            if (cabecera) {
                var idProvLabel = cabecera.querySelector('strong:nth-child(2)');
                if (idProvLabel && idProvLabel.nextSibling && idProvLabel.nextSibling.nodeType === 3) {
                    proveedor_id = idProvLabel.nextSibling.textContent.trim();
                } else {
                    // Alternativamente, buscar el valor en un input hidden si lo agregas
                    var inputProvId = document.querySelector('input[name="id_proveedor"]');
                    if (inputProvId) proveedor_id = inputProvId.value;
                }
            }
            var cotizacion = document.querySelector('input[name="cotizacion_id"]') ? document.querySelector('input[name="cotizacion_id"]').value : null;
            var requisicion_id = document.querySelector('input[name="requisicion_id"]') ? document.querySelector('input[name="requisicion_id"]').value : null;
            var url = base_url + 'ordenesCompra/crear';
            var http = new XMLHttpRequest();
            http.open('POST', url, true);
            http.setRequestHeader('Content-Type', 'application/json');
            http.send(JSON.stringify({
                productos: adjudicados,
                proveedor: proveedor,
                proveedor_id: proveedor_id,
                cotizacion: cotizacion,
                requisicion_id: requisicion_id
            }));
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var res = JSON.parse(this.responseText);
                    console.log(this.responseText);
                    if (res.success && res.idOrden) {
                        alertaPersonalizada('success', 'Orden de compra generada correctamente.');
                        setTimeout(function () {
                            window.open(base_url + 'ordenesCompra/generarPDF/' + res.idOrden, '_blank');
                            window.history.back();
                        }, 1000);
                    } else {
                        alertaPersonalizada('error', 'Error al generar la orden de compra.');
                    }
                }
            }
        });
    }
});
